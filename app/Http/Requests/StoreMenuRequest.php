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
            'slug' => ['required','string','unique:menus,slug'],
            'category_id' => ['required','exists:categories,id'],
            'price' => ['required','integer','min:0'],

            // required on store
            'image' => ['required','file','image','max:2048'],
        ];
    }
}
