<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // For potential complex queries later
use Illuminate\Support\Str; // Added

class HomeController extends Controller
{
    /**
     * Show the application's landing page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Fetch popular recipes (e.g., top 6 by likes)
        $popularRecipes = Recipe::whereNotNull('published_at')
            ->withCount('likes')
            ->orderBy('likes_count', 'desc')
            ->with(['user', 'tags']) // Eager load user and tags
            ->take(6) // Get top 6
            ->get();

        // Fetch "Pencarian Populer" - terms with associated images
        $popularSearchRecipes = Recipe::whereNotNull('published_at')
                                ->orderBy('created_at', 'desc') // Or by views/likes if tracked
                                ->with('user') // In case needed for display, though not directly used for term/image
                                ->take(6) // Number of popular search items to show
                                ->get();

        $popularSearchTerms = $popularSearchRecipes->map(function($recipe) {
            // Create a shorter term from title, e.g., first 2-3 words
            $term = Str::words($recipe->title, 3, '...');
            return (object)[
                'term' => $term, // The display text for the popular search
                'search_query' => $recipe->title, // The actual query to use when clicked
                'image_url' => $recipe->thumbnail_image_path ? asset('storage/' . $recipe->thumbnail_image_path) : null,
                'alt_text' => $recipe->title
            ];
        });
        
        // Fetch all tags for categories (or a curated list)
        // For now, let's fetch all tags that are associated with at least one recipe
        $categories = Tag::whereHas('recipes')
                           ->withCount('recipes')
                           ->orderBy('recipes_count', 'desc') // Order by popularity
                           ->get();
        
        // For the "Kategori Masakan" section, we might want to group tags or have predefined main categories.
        // The example image shows main categories like "Masakan Rumahan Sehari-hari", "Kue", etc.
        // This logic might be more complex than just listing all tags.
        // For now, I'll pass all fetched tags and the view can decide how to display them or a subset.
        // A more robust solution would involve a dedicated category system or a way to mark certain tags as "main categories".

        // For "Resep Populer Saat Ini" section in welcome.blade.php, we can reuse $popularRecipes or fetch another set.
        // The current welcome.blade.php uses $latestRecipes. Let's pass $popularRecipes as $latestRecipes for consistency with the view.
        
        return view('welcome', [
            'latestRecipes' => $popularRecipes, // Renaming for the view's existing variable
            'popularRecipes' => $popularRecipes, // Explicitly for "Resep Populer Saat Ini" if needed differently
            'popularSearchTerms' => $popularSearchTerms,
            'allTags' => $categories, // Passing fetched tags as 'allTags' for category section
        ]);
    }
}
