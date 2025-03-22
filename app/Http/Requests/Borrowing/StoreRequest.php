<?php

namespace App\Http\Requests\Borrowing;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class StoreRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'event' => 'required|string|max:255',
            'borrow_date' => 'required|date_format:d-m-Y|after_or_equal:today',
            'start_time' => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) {
                    $this->validateTimeNotInPast('start_time', $value, $fail);
                },
            ],
            'end_time' => [
                'required',
                'date_format:H:i',
                'after:start_time',
                function ($attribute, $value, $fail) {
                    $this->validateTimeNotInPast('end_time', $value, $fail);
                },
            ],
            'notes' => 'nullable|string',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'event.required' => 'Acara harus diisi.',
            'event.max' => 'Acara tidak boleh lebih dari 255 karakter.',
            'borrow_date.required' => 'Tanggal peminjaman harus diisi.',
            'borrow_date.date_format' => 'Format tanggal harus DD-MM-YYYY.',
            'borrow_date.after_or_equal' => 'Tanggal peminjaman tidak boleh lebih awal dari hari ini.',
            'start_time.required' => 'Waktu mulai harus diisi.',
            'start_time.date_format' => 'Format waktu mulai harus dalam format jam:menit (HH:MM).',
            'end_time.required' => 'Waktu berakhir harus diisi.',
            'end_time.date_format' => 'Format waktu berakhir harus dalam format jam:menit (HH:MM).',
            'end_time.after' => 'Waktu berakhir harus setelah waktu mulai.',
        ];
    }


    private function validateTimeNotInPast(string $field, string $time, callable $fail): void
    {
        $borrowDate = $this->input('borrow_date');

        // Only validate times if the borrow date is today
        if ($borrowDate) {
            $today = Carbon::now()->format('d-m-Y');

            if ($borrowDate === $today && $time < Carbon::now()->format('H:i')) {
                $fieldLabel = $field === 'start_time' ? 'Waktu mulai' : 'Waktu berakhir';
                $fail("$fieldLabel tidak boleh lebih awal dari sekarang jika tanggal peminjaman adalah hari ini.");
            }
        }
    }
}
