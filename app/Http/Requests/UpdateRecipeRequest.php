<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule; // Required for Rule::unique

class UpdateRecipeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Authorization for *who* can update *which* recipe is best handled
        // in the controller method or via a Policy.
        // This FormRequest authorize method typically checks if the *type* of user
        // is allowed to make this *type* of request in general.
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // $recipeId = $this->route('recipe') ? $this->route('recipe')->id : null;

        return [
            'title' => ['required', 'string', 'max:255'],
            // If slugs must be unique and are derived from title, this rule might need adjustment:
            // 'title' => ['required', 'string', 'max:255', Rule::unique('recipes', 'title')->ignore($recipeId)],
            'description' => ['nullable', 'string', 'max:5000'],
            'ingredients' => ['required', 'string', 'max:5000'],
            'instructions' => ['required', 'string', 'max:20000'],
            'thumbnail_image_path' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:2048'],
            'tags' => ['nullable', 'string', 'max:255'], // Comma-separated tags
        ];
    }
}
