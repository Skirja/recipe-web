<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key to users table
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('ingredients'); // Can be simple text, or JSON if more structure is needed later
            $table->longText('instructions');
            $table->string('thumbnail_image_path')->nullable();
            $table->timestamp('published_at')->nullable(); // To allow drafts
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
