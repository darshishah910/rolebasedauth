<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = auth()->id();

        return [
            'name'  => 'required|string|max:255',

            'email' => 'required|email|unique:users,email,' . $userId,

            'phone' => 'required|digits:10|unique:users,phone,' . $userId,

            'bio'   => 'nullable|string',

            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'  => 'Name is required',

            'email.required' => 'Email is required',
            'email.email'    => 'Enter valid email',
            'email.unique'   => 'Email already exists',

            'phone.required' => 'Phone is required',
            'phone.digits'   => 'Phone must be 10 digits',
            'phone.unique'   => 'Phone already exists',

            'image.image'    => 'File must be an image',
        ];
    }
}