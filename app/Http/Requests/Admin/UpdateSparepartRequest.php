<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSparepartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Ambil ID sparepart dari route parameter untuk ignore unique pada record sendiri
        $sparepartId = $this->route('sparepart')?->id;

        return [
            'kode_part'    => ['nullable', 'string', 'max:50', "unique:spareparts,kode_part,{$sparepartId}"],
            'nama_part'    => ['required', 'string', 'max:150'],
            'merek'        => ['nullable', 'string', 'max:100'],
            'kategori'     => ['nullable', 'string', 'max:100'],
            'satuan'       => ['required', 'string', 'max:20'],
            'harga_beli'   => ['required', 'numeric', 'min:0'],
            'stok'         => ['required', 'integer', 'min:0'],
            'stok_minimum' => ['required', 'integer', 'min:0'],
            'keterangan'   => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_part.required'    => 'Nama sparepart wajib diisi.',
            'harga_beli.required'   => 'Harga beli wajib diisi.',
            'harga_beli.min'        => 'Harga beli tidak boleh negatif.',
            'stok.required'         => 'Stok wajib diisi.',
            'stok.min'              => 'Stok tidak boleh negatif.',
            'stok_minimum.required' => 'Stok minimum wajib diisi.',
            'satuan.required'       => 'Satuan wajib diisi.',
            'kode_part.unique'      => 'Kode part sudah digunakan, pilih kode lain.',
        ];
    }
}
