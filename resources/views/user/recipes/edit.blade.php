@extends('layouts.app')

@section('title', 'Edit Recipe: ' . $recipe->title)

@section('content')
<div class="bg-gray-50 py-8 md:py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto bg-white p-8 md:p-10 rounded-xl shadow-xl">
            <h1 class="text-3xl font-bold text-gray-900 mb-8 text-center">Edit Your Recipe: <span class="text-orange-600">{{ $recipe->title }}</span></h1>

            {{-- Display validation errors --}}
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-md relative mb-6" role="alert">
                    <strong class="font-bold">Oops! Something went wrong.</strong>
                    <ul class="mt-2 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('recipes.update', $recipe) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT') {{-- or PATCH --}}

                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Recipe Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $recipe->title) }}" required
                           class="mt-1 block w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm placeholder-gray-400">
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Short Description</label>
                    <textarea name="description" id="description" rows="3"
                              class="mt-1 block w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm placeholder-gray-400">{{ old('description', $recipe->description) }}</textarea>
                </div>

                <div>
                    <label for="ingredients" class="block text-sm font-medium text-gray-700 mb-1">Ingredients</label>
                    <textarea name="ingredients" id="ingredients" rows="6" required
                              class="mt-1 block w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm placeholder-gray-400"
                              placeholder="List each ingredient on a new line.">{{ old('ingredients', $recipe->ingredients) }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">List each ingredient on a new line.</p>
                </div>

                <div>
                    <label for="instructions_input" class="block text-sm font-medium text-gray-700 mb-1">Instructions</label>
                    <input id="instructions_input" type="hidden" name="instructions" value="{{ old('instructions', $recipe->instructions) }}">
                     <div class="mt-1 trix-editor-container rounded-lg border border-gray-300 shadow-sm focus-within:ring-1 focus-within:ring-orange-500 focus-within:border-orange-500">
                        <trix-editor input="instructions_input" class="trix-content min-h-[200px] p-2.5"></trix-editor>
                    </div>
                    @error('instructions')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="thumbnail_image_path" class="block text-sm font-medium text-gray-700 mb-1">Thumbnail Image</label>
                    @if ($recipe->thumbnail_image_path)
                        <img src="{{ asset('storage/' . $recipe->thumbnail_image_path) }}" alt="Current thumbnail" class="h-32 w-auto rounded-md mb-2 border p-1">
                        <p class="text-xs text-gray-500 mb-1">Upload a new image below to replace the current one, or leave blank to keep it.</p>
                    @else
                        <p class="text-xs text-gray-500 mb-1">No current thumbnail image. Upload one below.</p>
                    @endif
                    <input type="file" name="thumbnail_image_path" id="thumbnail_image_path"
                           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100 cursor-pointer border border-gray-300 rounded-lg">
                </div>

                <div>
                    <label for="tags" class="block text-sm font-medium text-gray-700 mb-1">Tags (comma-separated)</label>
                    <input type="text" name="tags" id="tags" value="{{ old('tags', $recipe->tags->pluck('name')->implode(', ')) }}"
                           class="mt-1 block w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm placeholder-gray-400"
                           placeholder="e.g., ayam, pedas, sarapan">
                    <p class="mt-1 text-xs text-gray-500">Enter tags separated by commas.</p>
                </div>
                
                {{-- Placeholder for Editing Recipe Steps - to be implemented later --}}

                <div class="pt-6">
                    <button type="submit"
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-orange-500 hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-150">
                        Update Recipe
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
