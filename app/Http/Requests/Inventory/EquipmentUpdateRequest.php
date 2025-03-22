<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EquipmentUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $equipmentId = $this->route('id') ?? $this->input('equipment_id');

        return [
            'name' => 'required|string|max:255',
            'code' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('equipment', 'code')->ignore($equipmentId)
            ],
            'stock' => 'required|integer|min:0',
            'condition' => 'required|in:baik,rusak ringan,rusak berat',
            'category_id' => 'nullable|exists:equipment_categories,id',
            'location_id' => 'nullable|exists:equipment_locations,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama barang harus diisi',
            'code.unique' => 'Kode barang sudah digunakan, silakan gunakan kode lain',
            'stock.required' => 'Stok barang harus diisi',
            'stock.min' => 'Stok minimal 0',
            'condition.required' => 'Kondisi barang harus dipilih',
            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'Format gambar harus jpeg, png, atau jpg',
            'image.max' => 'Ukuran gambar maksimal 2MB'
        ];
    }
}
