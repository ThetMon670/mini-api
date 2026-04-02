<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:255',

            'email' => 'nullable|email',

            'phone' => 'nullable|string|unique:customers,phone,' . $this->route('customer'),


            'address' => 'nullable|string',
            'image' => ['nullable', 'file', 'image', 'max:2048'],

            'date_of_birth' => 'nullable|date|before_or_equal:' . now()->subYears(18)->toDateString(),
        ];
    }
}
