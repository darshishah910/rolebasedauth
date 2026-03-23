<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price'       => 'required|numeric|min:0',
            'quantity'    => 'required|integer|min:0',
            'in_stock'    => 'in:0,1',
            'image'       => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Product name is required',
            'name.max' => 'Product name must not exceed 255 characters',

            'price.required' => 'Price is required',
            'price.numeric' => 'Price must be a number',
            'price.min' => 'Price cannot be negative',

            'quantity.required' => 'Quantity is required',
            'quantity.integer' => 'Quantity must be a number',
            'quantity.min' => 'Quantity cannot be negative',

            // ✅ in_stock messages
            'in_stock.boolean' => 'Stock status must be true or false',

            'image.image' => 'File must be an image',
            'image.mimes' => 'Only JPG, JPEG, PNG allowed',
            'image.max' => 'Image size must be less than 2MB',
        ];
    }
}