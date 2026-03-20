<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|max:8|regex:/^(?=.*[A-Z])(?=.*[0-9])(?=.*[@$!%*?&]).+$/'
        ];
    }

     public function messages(): array
    {
        return [
            'email.required' => 'Email is required',
            'email.email' => 'Enter a valid email',
            'email.unique' => 'Email id already exists',
            'password.required' => 'password is required',
            'password.min'=>'password must be at least 6 characters',
            'password.max' => 'password max length is 8 characters',
            'password.regex' => 'Password must contain at least one uppercase letter, one number, and one special character.',
        ];
    }
}
