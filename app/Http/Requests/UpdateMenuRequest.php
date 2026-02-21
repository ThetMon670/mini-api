<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMenuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        $menuId = $this->route('menu')->id ?? null;

        return [
            'title' => ['sometimes','string','max:255'],
            'price' => ['sometimes','integer','min:0'],

            'image' => ['sometimes','file','image','max:2048'],
        ];
    }
}
