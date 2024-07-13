<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
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
            'old-password' => 'required|min:8|max:255',
            'password' => 'required|confirmed|min:8|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'old-password.required' => 'Kolom old-password wajib diisi.',
            'old-password.min' => 'Kolom old-password tidak boleh kurang dari 8 karakter.',
            'old-password.max' => 'Kolom old-password tidak boleh lebih dari 255 karakter.',
            'password.required' => 'Kolom password wajib diisi.',
            'password.min' => 'Kolom password tidak boleh kurang dari 8 karakter',
            'password.max' => 'Kolom password tidak boleh lebih dari 255 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ];
    }
}
