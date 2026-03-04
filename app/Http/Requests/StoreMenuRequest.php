<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMenuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required','string','max:255'],
            'unit' => 'required|string|max:255',
            'price' => ['required','integer','min:0'],
            'category_id' => 'required|integer|exists:categories,id',

            // required on store
            'image' => ['required','file','image','max:2048'],
        ];
    }
}
