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
        Schema::create('recipe_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_id')->constrained()->onDelete('cascade'); // Foreign key to recipes table
            $table->unsignedInteger('step_number');
            $table->text('description');
            $table->string('image_path')->nullable(); // Optional image for the step
            $table->timestamps();

            $table->unique(['recipe_id', 'step_number']); // A recipe cannot have duplicate step numbers
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_steps');
    }
};
