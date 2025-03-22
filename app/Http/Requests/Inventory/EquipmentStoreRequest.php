<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EquipmentStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'condition' => 'required|string|in:baik,rusak ringan,rusak berat',
            'category_id' => 'nullable|exists:equipment_categories,id',
            'location_id' => 'nullable|exists:equipment_locations,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];

    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama barang harus diisi',
            'name.string' => 'Nama barang harus berupa teks',
            'name.max' => 'Nama barang maksimal 255 karakter',
            'stock.required' => 'Stok barang harus diisi',
            'stock.integer' => 'Stok barang harus berupa angka',
            'stock.min' => 'Stok minimal 0',
            'condition.required' => 'Kondisi barang harus dipilih',
            'condition.string' => 'Kondisi barang harus berupa teks',
            'condition.in' => 'Kondisi barang harus dipilih dari pilihan yang tersedia',
            'category_id.exists' => 'Kategori tidak ditemukan',
            'location_id.exists' => 'Lokasi tidak ditemukan',
            'description.string' => 'Deskripsi harus berupa teks',
            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'Format gambar harus jpeg, png, atau jpg',
            'image.max' => 'Ukuran gambar maksimal 2MB',
        ];
    }
}