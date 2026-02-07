<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangeProfileImageRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:20480',
        ];
    }

    /**
     * Custom validation messages.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'profile_image.required' => 'Please upload a profile image.',
            'profile_image.image' => 'The profile image must be a valid image file.',
            'profile_image.mimes' => 'The profile image must be a file of type: jpeg, png, jpg, gif.',
            'profile_image.max' => 'The profile image size must not exceed 20MB.',
        ];
    }
}