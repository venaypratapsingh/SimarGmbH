<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleEmp extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'slug' => 'required|string|max:255',
            'time_in' => 'required|date_format:H:i',
            'time_out' => 'required|date_format:H:i|after:time_in',
            'break_start' => 'nullable|date_format:H:i',
            'break_end' => 'nullable|date_format:H:i|after:break_start',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'slug.required' => 'The schedule name is required.',
            'time_in.required' => 'Time in is required.',
            'time_in.date_format' => 'Time in must be in HH:MM format.',
            'time_out.required' => 'Time out is required.',
            'time_out.date_format' => 'Time out must be in HH:MM format.',
            'time_out.after' => 'Time out must be after time in.',
            'break_start.date_format' => 'Break start must be in HH:MM format.',
            'break_end.date_format' => 'Break end must be in HH:MM format.',
            'break_end.after' => 'Break end must be after break start.',
        ];
    }
}
