<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreRecipeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only authenticated users can create recipes.
        // The route itself should also be protected by 'auth' middleware.
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'], // Max length for description
            'ingredients' => ['required', 'string', 'max:5000'], // Max length for ingredients list
            'instructions' => ['required', 'string', 'max:20000'], // Max length for instructions
            'thumbnail_image_path' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:2048'], // Max 2MB
            'tags' => ['nullable', 'string', 'max:255'], // Comma-separated tags
            // Rules for recipe steps can be added here later if submitted with the main form
            // e.g., 'steps' => ['nullable', 'array'],
            // 'steps.*.description' => ['required_with:steps', 'string'],
            // 'steps.*.image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:2048'],
        ];
    }
}
