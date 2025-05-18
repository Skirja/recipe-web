<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Get the recipes associated with the tag.
     */
    public function recipes(): BelongsToMany
    {
        return $this->belongsToMany(Recipe::class); // Assumes 'recipe_tag' pivot table
    }
}
