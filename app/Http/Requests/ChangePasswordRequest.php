<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'current_password' => 'required',

            'new_password' => [
                'required',
                'min:6',
                'max:10',
                'regex:/^(?=.*[A-Z])(?=.*[0-9])(?=.*[@$!%*?&]).+$/'
            ],

            'confirm_password' => 'required|same:new_password',
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.required' => 'Current password required',

            'new_password.required' => 'New password required',
            'new_password.regex' => 'Password must contain uppercase, number & special char',

            'confirm_password.same' => 'Passwords do not match',
        ];
    }
}