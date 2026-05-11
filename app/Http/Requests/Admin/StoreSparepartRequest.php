<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreSparepartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kode_part'    => ['nullable', 'string', 'max:50', 'unique:spareparts,kode_part'],
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
            'stok.required'         => 'Stok awal wajib diisi.',
            'stok.min'              => 'Stok tidak boleh negatif.',
            'stok_minimum.required' => 'Stok minimum wajib diisi.',
            'satuan.required'       => 'Satuan wajib diisi.',
            'kode_part.unique'      => 'Kode part sudah digunakan, pilih kode lain.',
        ];
    }
}
