<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class UpdateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // You can customize authorization logic here if needed
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // Safely access the category ID
        $categoryId = Route::current()->parameter('category');

        if (is_null($categoryId)) {
            // Handle case where category ID is not found
            abort(400, 'Category ID is required.');
        }

        return [
            'title' => 'nullable|string',
            'slug' => 'nullable|string|unique:categories,slug,' . $this->route('categories'),
            'user_id' => 'nullable|exists:users,id',
        ];
    }
}