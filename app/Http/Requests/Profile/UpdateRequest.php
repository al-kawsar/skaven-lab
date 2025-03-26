<?php
namespace App\Http\Requests\Profile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
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
        // Cache the authenticated user to prevent multiple calls
        $user = Auth::user();
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[0-9\+\- ]+$/'],
            'bio' => ['nullable', 'string', 'max:1000'],
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
            'name.required' => 'Kolom nama wajib diisi.',
            'name.string' => 'Kolom nama harus berupa teks.',
            'name.max' => 'Kolom nama tidak boleh lebih dari 255 karakter.',
            // Email validation messages
            'email.required' => 'Kolom email wajib diisi.',
            'email.string' => 'Kolom email harus berupa teks.',
            'email.email' => 'Kolom email harus berisi alamat email yang valid.',
            'email.max' => 'Kolom email tidak boleh lebih dari 255 karakter.',
            'email.unique' => 'Email sudah terdaftar.',
            // Avatar validation messages
            'avatar.image' => 'File harus berupa gambar.',
            'avatar.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif.',
            'avatar.max' => 'Ukuran gambar tidak boleh lebih dari 2MB.',
            // Phone validation messages
            'phone.string' => 'Nomor telepon harus berupa teks.',
            'phone.max' => 'Nomor telepon tidak boleh lebih dari 20 karakter.',
            'phone.regex' => 'Format nomor telepon tidak valid.',
            // Bio validation messages
            'bio.string' => 'Biografi harus berupa teks.',
            'bio.max' => 'Biografi tidak boleh lebih dari 1000 karakter.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    // public function attributes(): array
    // {
    //     return [
    //         'name' => 'nama',
    //         'email' => 'alamat email',
    //         'avatar' => 'foto profil',
    //         'phone' => 'nomor telepon',
    //         'bio' => 'biografi',
    //     ];
    // }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        // Trim whitespace from inputs to prevent unnecessary validation errors
        if ($this->has('name')) {
            $this->merge([
                'name' => trim($this->name),
            ]);
        }
        if ($this->has('email')) {
            $this->merge([
                'email' => strtolower(trim($this->email)),
            ]);
        }
        if ($this->has('phone') && $this->phone !== null) {
            $this->merge([
                'phone' => trim($this->phone),
            ]);
        }
    }
}