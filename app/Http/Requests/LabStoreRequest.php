<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LabStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'location' => 'required',
            'capacity' => 'required',
            'facilities' => 'required',
            'thumbnail' => 'required|image|mimes:jpeg,png,webp,jpg|max:2048'
        ];
    }

    public function messages()
    {
        return [
            'thumbnail.required' => 'Kolom thumbnail wajib di isi',
            'thumbnail.image' => 'thumbnail harus berupa gambar',
            'thumbnail.mimes' => 'thumbnail harus berupa gambar dengan ekstensi (.png,.jpg,.jpeg,.webp)',
            'thumbnail.max' => 'Maksimal ukuran gambar 2MB!',
        ];
    }
}
