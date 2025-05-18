<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRecipeRequest;
use App\Http\Requests\UpdateRecipeRequest;
use App\Models\Recipe;
use App\Models\Tag;
use Illuminate\Http\Request; // Added for index method search
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Recipe::whereNotNull('published_at')->with('user');

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }
        
        // Add other filters here later (e.g., by tag, by likes)
        $currentTag = null;
        if ($request->filled('tag')) {
            $tagSlug = $request->input('tag');
            $currentTag = Tag::where('slug', $tagSlug)->first();
            if ($currentTag) {
                $query->whereHas('tags', function ($q) use ($tagSlug) {
                    $q->where('slug', $tagSlug);
                });
            }
        }

        $sortOrder = $request->input('sort', 'latest'); // Default to latest

        if ($sortOrder === 'likes') {
            $query->withCount('likes')->orderBy('likes_count', 'desc');
        } else { // Default or 'latest'
            $query->latest('published_at');
        }
        
        $recipes = $query->paginate(10)->withQueryString();
        $allTags = Tag::orderBy('name')->get(); 

        return view('recipes.index', [
            'recipes' => $recipes,
            'searchTerm' => $request->input('search', ''),
            'currentTag' => $currentTag,
            'allTags' => $allTags,
            'currentSort' => $sortOrder // Pass current sort order to view
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.recipes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRecipeRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail_image_path')) {
            // Store in 'public/thumbnails' which symlinks to 'storage/app/public/thumbnails'
            $thumbnailPath = $request->file('thumbnail_image_path')->store('thumbnails', 'public');
        }

        $recipe = Recipe::create([
            'user_id' => Auth::id(),
            'title' => $validatedData['title'],
            'slug' => Str::slug($validatedData['title']), // Simple slug generation
            'description' => $validatedData['description'],
            'ingredients' => $validatedData['ingredients'],
            'instructions' => $validatedData['instructions'],
            'thumbnail_image_path' => $thumbnailPath,
            'published_at' => now(), // Publish immediately for now
        ]);

        // Handle tags
        if (!empty($validatedData['tags'])) {
            $tagNames = array_map('trim', explode(',', $validatedData['tags']));
            $tagIds = [];
            foreach ($tagNames as $tagName) {
                if (empty($tagName)) continue;
                $tag = Tag::firstOrCreate(
                    ['slug' => Str::slug($tagName)],
                    ['name' => $tagName]
                );
                $tagIds[] = $tag->id;
            }
            $recipe->tags()->sync($tagIds);
        } else {
            // If tags field is empty or not provided, detach all tags
            $recipe->tags()->sync([]);
        }


        // Later, handle RecipeSteps creation here if they are part of the form

        return redirect()->route('recipes.show', $recipe)
                         ->with('success', 'Recipe created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Recipe $recipe) // Route model binding by slug
    {
        // Eager load necessary relationships
        $recipe->load(['user', 'steps', 'comments.user', 'tags', 'likes']);
        
        return view('recipes.show', compact('recipe'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Recipe $recipe) // Route model binding by slug
    {
        // Authorization: Only the owner can edit the recipe
        if (Auth::id() !== $recipe->user_id) {
            // Or use $this->authorize('update', $recipe); if using Policies
            abort(403, 'Unauthorized action.');
        }

        return view('user.recipes.edit', compact('recipe'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRecipeRequest $request, Recipe $recipe): RedirectResponse
    {
        // Authorization: Only the owner can update the recipe
        if (Auth::id() !== $recipe->user_id) {
            // Or use $this->authorize('update', $recipe); if using Policies
            abort(403, 'Unauthorized action.');
        }

        $validatedData = $request->validated();
        
        $updateData = [
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'ingredients' => $validatedData['ingredients'],
            'instructions' => $validatedData['instructions'],
        ];

        // Update slug if title changed
        if ($recipe->title !== $validatedData['title']) {
            $updateData['slug'] = Str::slug($validatedData['title']);
            // Potentially add logic here to ensure new slug is unique if titles aren't strictly unique
        }

        if ($request->hasFile('thumbnail_image_path')) {
            // Delete old thumbnail if it exists
            if ($recipe->thumbnail_image_path) {
                Storage::disk('public')->delete($recipe->thumbnail_image_path);
            }
            // Store new thumbnail
            $updateData['thumbnail_image_path'] = $request->file('thumbnail_image_path')->store('thumbnails', 'public');
        }

        $recipe->update($updateData);

        // Handle tags
        if (!empty($validatedData['tags'])) {
            $tagNames = array_map('trim', explode(',', $validatedData['tags']));
            $tagIds = [];
            foreach ($tagNames as $tagName) {
                if (empty($tagName)) continue;
                $tag = Tag::firstOrCreate(
                    ['slug' => Str::slug($tagName)],
                    ['name' => $tagName]
                );
                $tagIds[] = $tag->id;
            }
            $recipe->tags()->sync($tagIds);
        } else {
            // If tags field is empty or not provided, detach all tags
            $recipe->tags()->sync([]);
        }

        // Later, handle RecipeSteps update/creation/deletion here

        return redirect()->route('recipes.show', $recipe)
                         ->with('success', 'Recipe updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Recipe $recipe): RedirectResponse // Changed string $id to Recipe $recipe
    {
        // Authorization: Only the owner can delete the recipe
        if (Auth::id() !== $recipe->user_id) {
            // Or use $this->authorize('delete', $recipe); if using Policies
            abort(403, 'Unauthorized action.');
        }

        // Delete thumbnail image if it exists
        if ($recipe->thumbnail_image_path) {
            Storage::disk('public')->delete($recipe->thumbnail_image_path);
        }

        // Delete associated recipe steps' images (if any and if stored)
        // foreach ($recipe->steps as $step) {
        //     if ($step->image_path) {
        //         Storage::disk('public')->delete($step->image_path);
        //     }
        // }
        // Note: Recipe steps and comments will be deleted by cascade due to DB constraints.

        $recipe->delete();

        return redirect()->route('recipes.index') // Or user's dashboard/recipe list
                         ->with('success', 'Recipe deleted successfully!');
    }

    /**
     * Display a listing of the authenticated user's recipes.
     */
    public function myRecipes(Request $request)
    {
        $recipes = Recipe::where('user_id', Auth::id())
                         ->latest('updated_at') // Show most recently updated first
                         ->paginate(10);

        return view('user.recipes.index', compact('recipes'));
    }
}
