<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_pengeluaran'    => ['required', 'string', 'max:200'],
            'kategori'            => ['nullable', 'string', 'max:100'],
            'jumlah'              => ['required', 'numeric', 'min:1'],
            'tanggal_pengeluaran' => ['required', 'date', 'before_or_equal:today'],
            'keterangan'          => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_pengeluaran.required'           => 'Nama pengeluaran wajib diisi.',
            'jumlah.required'                     => 'Jumlah rupiah wajib diisi.',
            'jumlah.min'                          => 'Jumlah harus lebih dari 0.',
            'tanggal_pengeluaran.required'        => 'Tanggal pengeluaran wajib diisi.',
            'tanggal_pengeluaran.before_or_equal' => 'Tanggal tidak boleh melebihi hari ini.',
        ];
    }
}
