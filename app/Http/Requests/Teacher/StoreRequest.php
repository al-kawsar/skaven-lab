<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{

    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:255|string',
            'nip' => 'required|digits_between:1,20',
            'alamat' => 'required|max:255|string',
            'jenis_kelamin' => 'required|max:255',
            'agama' => 'required|max:255|string',
            'tgl_lahir' => 'required|max:255|date_format:d-m-Y',
            'foto_guru' => 'sometimes|file|image|required|max:255|mimes:png,jpg,jpeg,webp,png'
        ];
    }
}
