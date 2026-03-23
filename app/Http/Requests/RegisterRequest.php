<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name'  => 'required|string|min:2|max:25',

            'email' => 'required|email|unique:users,email',

            'phone' => 'required|string|digits:10|unique:users,phone',

            'bio'   => 'required|string|max:500',

            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',

            'password' => 'required|string|min:6|max:10|confirmed|regex:/^(?=.*[A-Z])(?=.*[0-9])(?=.*[@$!%*?&]).+$/',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'name.min' => 'Name is at least 2 characters',
            'name.max' => 'name is too long',
            'email.required' => 'email is required',
            'email.email' => 'Enter the valid email address',
            'email.unique'=> 'This email is already Registered',
            'phone.required' => 'Phone Number is Required',
            'phone.digits' => 'Phone number is 10 digit',
            'phone.unique' => 'Phone Number is already in use',
            'image.image' => 'File must be image',
            'image.mimes' => 'Only JPG, JPEG, PNG files are allowed',
            'image.max' => 'Image must be less then 2MB',
            'password.required' => 'Password is Required',
            'password.min' => 'Password is minimum 6 characters',
            'password.max' => 'Password is maximum 10 characters',
            'password.regex' => 'Password has one Capital, one Special symbol, one number.',
            'password.confirmed' => 'Password is not matched.'
        ];
    }
}
