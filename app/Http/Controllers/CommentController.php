<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Models\Recipe;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
// No need for Illuminate\Http\Request if only using FormRequest for store

class CommentController extends Controller
{
    /**
     * Store a newly created comment in storage.
     *
     * @param  \App\Http\Requests\StoreCommentRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreCommentRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        Comment::create([
            'user_id' => Auth::id(),
            'recipe_id' => $validatedData['recipe_id'],
            'body' => $validatedData['body'],
        ]);

        // Fetch the recipe to redirect back to its show page
        $recipe = Recipe::findOrFail($validatedData['recipe_id']);

        return redirect()->route('recipes.show', $recipe)
                         ->with('success', 'Comment posted successfully!');
    }

    // Other methods like edit, update, destroy for comments can be added later if needed.
}
