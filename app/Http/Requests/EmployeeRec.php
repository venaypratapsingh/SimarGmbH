<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeRec extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $employeeId = $this->route('employee') ? $this->route('employee')->id : null;

        return [
            'name' => [
                'required', 'string', 'min:3', 'max:64',
                'regex:/^[\pL\s\-\'\.]+$/u',
                Rule::unique('employees', 'name')->ignore($employeeId),
            ],
            'restaurant' => 'required|string|min:3|max:64',
            'member_since' => 'required|date',
            'schedule' => 'nullable|exists:schedules,slug',
        ];
    }
}
