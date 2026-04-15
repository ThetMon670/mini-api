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
         $customerId = $this->route('customer')?->id;
        return [
            "name" => "sometimes|string|max:255",
            "email" => "sometimes|email",
            "phone" => "sometimes|string|max:255|unique:customers,phone,$customerId",
            "address" => "sometimes|string",
            "date_of_birth" => "sometimes|date",
            "image" => "nullable|string"
        ];
    }
}
