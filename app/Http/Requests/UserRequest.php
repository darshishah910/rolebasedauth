<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        // Check if update (PUT/PATCH)
        $userId = $this->route('id');

        return [
            'name' => 'required|string|max:255',

            'email' => 'required|email|unique:users,email,' . $userId,

            'phone' => 'required|digits:10|unique:users,phone,' . $userId,

            'bio' => 'nullable|string',

            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            'password' => $this->isMethod('post')
                ? 'required|min:6|max:10|regex:/^(?=.*[A-Z])(?=.*[0-9])(?=.*[@$!%*?&]).+$/'
                : 'nullable|min:6|max:10|regex:/^(?=.*[A-Z])(?=.*[0-9])(?=.*[@$!%*?&]).+$/',

            'role' => 'required|in:Admin,Manager,User',

            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required',

            'email.required' => 'Email is required',
            'email.email' => 'Enter valid email',
            'email.unique' => 'Email already exists',

            'phone.required' => 'Phone is required',
            'phone.digits' => 'Phone must be 10 digits',

            'password.required' => 'Password is required',
            'password.regex' => 'Password must contain uppercase, number & special character',

            'role.required' => 'Role is required',
            'role.in' => 'Invalid role selected',

            'image.image' => 'File must be an image',
        ];
    }
}
