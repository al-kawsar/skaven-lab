<?php

namespace App\Http\Requests\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
        $userId = $this->getUserByRequest();

        return [
            'name' => 'required|max:255|string',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($userId),
            ],
            'password' => 'sometimes|nullable|string|min:8|max:255',
            'role_id' => 'required|exists:roles,id',
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            // Name validation messages
            'name.required' => 'Nama harus diisi.',
            'name.max' => 'Nama tidak boleh lebih dari 255 karakter.',
            'name.string' => 'Nama harus berupa teks.',

            // Email validation messages
            'email.required' => 'Email harus diisi.',
            'email.string' => 'Email harus berupa teks.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email tidak boleh lebih dari 255 karakter.',
            'email.unique' => 'Email sudah digunakan oleh pengguna lain.',

            // Password validation messages
            'password.string' => 'Password harus berupa teks.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.max' => 'Password tidak boleh lebih dari 255 karakter.',

            // Role validation messages
            'role_id.required' => 'Role harus dipilih.',
            'role_id.exists' => 'Role yang dipilih tidak valid atau tidak ditemukan.',
        ];
    }

    /**
     * Get the user ID from the request route parameter.
     *
     * @return int
     */
    private function getUserByRequest(): int
    {
        return (int) $this->route('user');
    }
}
