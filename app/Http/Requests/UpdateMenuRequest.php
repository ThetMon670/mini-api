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
            'slug' => ['sometimes','string',"unique:menus,slug,$menuId"],
            'category_id' => ['sometimes','exists:categories,id'],
            'price' => ['sometimes','integer','min:0'],

            // ✅ optional file upload
            'image' => ['sometimes','file','image','max:2048'],
        ];
    }
}
