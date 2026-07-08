<?php

namespace App\Http\Requests\Kasir;

use App\Models\Sparepart;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tipe_transaksi'      => ['required', 'in:penjualan,servis'],
            'plat_nomor'          => ['nullable', 'string', 'max:20'],
            'jenis_mobil'         => ['nullable', 'string', 'max:100'],
            'ongkos_jasa'         => ['nullable', 'numeric', 'min:0'],
            'metode_pembayaran'   => ['required', 'in:cash,qris,xendit'],
            'uang_diterima'       => ['nullable', 'numeric', 'min:0'],
            'catatan'             => ['nullable', 'string', 'max:500'],
            'is_estimasi'         => ['nullable', 'boolean'],

            // Jasa servis yang dipilih dari master data
            'jasa_items'          => ['nullable', 'array'],
            'jasa_items.*.id'     => ['required_with:jasa_items', 'integer', 'exists:jasa_servis,id'],
            'jasa_items.*.nama_jasa'      => ['required_with:jasa_items', 'string'],
            'jasa_items.*.estimasi_biaya' => ['required_with:jasa_items', 'numeric', 'min:0'],

            // Array keranjang belanja (bisa kosong jika servis tanpa sparepart)
            'items'               => ['nullable', 'array'],
            'items.*.sparepart_id'=> ['required_with:items', 'integer', 'exists:spareparts,id'],
            'items.*.qty'         => ['required_with:items', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'tipe_transaksi.required'       => 'Tipe transaksi wajib dipilih.',
            'tipe_transaksi.in'             => 'Tipe transaksi tidak valid.',
            'metode_pembayaran.required'    => 'Metode pembayaran wajib dipilih.',
            'items.*.sparepart_id.exists'   => 'Sparepart tidak ditemukan.',
            'items.*.qty.min'               => 'Qty minimal 1.',
        ];
    }

    /**
     * Validasi tambahan setelah rules dasar lulus.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $v) {

            // Rule 1: Jika tipe servis, plat_nomor & jenis_mobil wajib
            if ($this->tipe_transaksi === 'servis') {
                if (empty($this->plat_nomor)) {
                    $v->errors()->add('plat_nomor', 'Plat nomor wajib diisi untuk transaksi servis.');
                }
                if (empty($this->jenis_mobil)) {
                    $v->errors()->add('jenis_mobil', 'Jenis mobil wajib diisi untuk transaksi servis.');
                }
            }

            // Rule 2: Transaksi harus punya minimal 1 item ATAU ongkos jasa > 0
            $hasItems    = !empty($this->items) && count($this->items) > 0;
            $hasOngkos   = floatval($this->ongkos_jasa) > 0;
            if (!$hasItems && !$hasOngkos) {
                $v->errors()->add('items', 'Transaksi harus memiliki minimal 1 sparepart atau ongkos jasa.');
            }

            // Rule 3: Cek stok mencukupi untuk setiap item
            if ($hasItems) {
                foreach ($this->items as $index => $item) {
                    $sparepart = Sparepart::find($item['sparepart_id']);
                    if ($sparepart && $sparepart->stok < $item['qty']) {
                        $v->errors()->add(
                            "items.{$index}.qty",
                            "Stok \"{$sparepart->nama_part}\" tidak cukup. Tersedia: {$sparepart->stok} {$sparepart->satuan}."
                        );
                    }
                }
            }

            // Rule 4: Jika cash, uang_diterima harus >= total yang dibayar
            // (Dilewati jika ini adalah estimasi — pembayaran belum terjadi)
            if ($this->metode_pembayaran === 'cash' && !$this->boolean('is_estimasi')) {
                if (empty($this->uang_diterima) || floatval($this->uang_diterima) <= 0) {
                    $v->errors()->add('uang_diterima', 'Uang diterima wajib diisi untuk pembayaran cash.');
                }
            }
        });
    }
}
