<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Like;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
// No need for Illuminate\Http\Request if not used directly

class RecipeLikeController extends Controller
{
    /**
     * Store a newly created like in storage.
     *
     * @param  \App\Models\Recipe  $recipe
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Recipe $recipe): RedirectResponse
    {
        // Check if already liked to prevent duplicate entries,
        // though the unique constraint in DB also handles this.
        if (!$recipe->likes()->where('user_id', Auth::id())->exists()) {
            $like = new Like();
            $like->user_id = Auth::id();
            // $like->recipe_id = $recipe->id; // This would also work
            $recipe->likes()->save($like);
        }
        return back()->with('success', 'Recipe liked!');
    }

    /**
     * Remove the specified like from storage.
     *
     * @param  \App\Models\Recipe  $recipe
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Recipe $recipe): RedirectResponse
    {
        $recipe->likes()->where('user_id', Auth::id())->delete();
        return back()->with('success', 'Recipe unliked!');
    }
}
