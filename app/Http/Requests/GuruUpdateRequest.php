<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuruUpdateRequest extends FormRequest
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
            'name' => 'required|max:255|string',
            'nip' => 'required|max:20|digits_between:1,20',
            'alamat' => 'required|max:255|string',
            'jenis_kelamin' => 'required|max:255',
            'agama' => 'required|max:255|string',
            'tgl_lahir' => 'required|max:255|date_format:d-M-Y',
            'foto_guru' => 'sometimes|file|image|required|max:255|mimes:png,jpg,jpeg,webp,png'
        ];
    }
}
