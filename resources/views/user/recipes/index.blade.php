@extends('layouts.app')

@section('title', 'My Recipes')

@section('content')
<div class="bg-gray-50 py-8 md:py-12">
<div class="container mx-auto px-4">
    <div class="flex flex-col sm:flex-row justify-between items-center mb-10 pb-4 border-b border-gray-200">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4 sm:mb-0">My Shared Recipes</h1>
        <a href="{{ route('recipes.create') }}" class="bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2.5 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-150 ease-in-out text-sm">
            Share New Recipe
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-8 rounded-md shadow" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if ($recipes->isEmpty())
        <div class="text-center py-16 bg-white rounded-xl shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p class="text-2xl text-gray-700 mb-3">You haven't shared any recipes yet.</p>
            <p class="text-gray-500 mb-6">Why not share your culinary masterpiece with the world?</p>
            <a href="{{ route('recipes.create') }}" class="bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-150 ease-in-out">
                Share Your First Recipe!
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-10">
            @foreach ($recipes as $recipe)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden flex flex-col group transition-all duration-300 hover:shadow-2xl">
                    <a href="{{ route('recipes.show', $recipe) }}" class="block overflow-hidden">
                        @if ($recipe->thumbnail_image_path)
                            <img src="{{ asset('storage/' . $recipe->thumbnail_image_path) }}" alt="{{ $recipe->title }}" class="w-full h-56 object-cover transform group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-56 bg-gradient-to-br from-gray-300 to-gray-400 flex items-center justify-center text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                    </a>
                    <div class="p-5 flex flex-col flex-grow">
                        <h2 class="text-lg font-semibold text-gray-800 mb-2 truncate group-hover:text-orange-600 transition-colors">
                            <a href="{{ route('recipes.show', $recipe) }}">{{ $recipe->title }}</a>
                        </h2>
                        <p class="text-xs text-gray-500 mb-3">Last updated: {{ $recipe->updated_at->format('F j, Y') }}</p>
                        <p class="text-sm text-gray-600 mb-4 flex-grow">
                            {{ Str::limit($recipe->description, 90) }}
                        </p>
                        <div class="mt-auto pt-4 border-t border-gray-100 flex justify-between items-center">
                             <a href="{{ route('recipes.show', $recipe) }}" class="inline-block bg-orange-500 hover:bg-orange-600 text-white text-xs font-semibold py-2 px-4 rounded-md transition duration-150 ease-in-out">
                                View
                            </a>
                            <div class="flex space-x-2">
                                <a href="{{ route('recipes.edit', $recipe) }}" class="text-xs text-orange-600 hover:text-orange-800 font-medium py-2 px-3 rounded-md hover:bg-orange-50 transition-colors">Edit</a>
                                <form action="{{ route('recipes.destroy', $recipe) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this recipe?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-red-600 hover:text-red-800 font-medium py-2 px-3 rounded-md hover:bg-red-50 transition-colors">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination Links -->
        <div class="mt-12">
            {{ $recipes->links() }}
        </div>
    @endif
</div>
@endsection
