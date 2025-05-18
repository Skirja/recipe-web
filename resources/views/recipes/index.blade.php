@extends('layouts.app')

@section('title', 'All Recipes')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Discover Recipes</h1>
        @auth
            <a href="{{ route('recipes.create') }}" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-4 rounded-lg shadow">
                Share Your Recipe
            </a>
        @endauth
    </div>

    <!-- Search Form -->
    <div class="mb-8">
        <form action="{{ route('recipes.index') }}" method="GET" class="flex items-center">
            <input type="text" name="search" value="{{ $searchTerm ?? '' }}" placeholder="Search recipes by title or description..."
                   class="w-full px-4 py-3 border border-gray-300 rounded-l-lg shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-base">
            <button type="submit"
                    class="px-6 py-3 bg-orange-500 text-white font-semibold rounded-r-lg hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 sm:text-base">
                Search
            </button>
            @if(!empty($searchTerm))
                <a href="{{ route('recipes.index') }}" class="ml-4 text-sm text-gray-600 hover:text-orange-600 underline self-center">Clear Search</a>
            @endif
        </form>
    </div>

    <!-- Filters and Sort Panel -->
    <div class="mb-10 p-6 bg-white rounded-lg shadow">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Tags Filter -->
            @if(isset($allTags) && $allTags->isNotEmpty())
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Filter by Tag:</h3>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('recipes.index', array_filter(request()->except(['tag', 'page']))) }}"
                       class="px-3 py-1.5 text-sm rounded-md shadow-sm
                              {{ !$currentTag ? 'bg-orange-500 text-white font-semibold' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        All Recipes
                    </a>
                    @foreach ($allTags as $tag)
                        <a href="{{ route('recipes.index', array_merge(array_filter(request()->except(['tag', 'page'])), ['tag' => $tag->slug])) }}"
                           class="px-3 py-1.5 text-sm rounded-md shadow-sm
                                  {{ $currentTag && $currentTag->slug == $tag->slug ? 'bg-orange-500 text-white font-semibold' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            {{ $tag->name }}
                        </a>
                    @endforeach
                </div>
                @if($currentTag)
                <div class="mt-3">
                    <p class="text-sm text-gray-600">Showing recipes tagged with: <strong class="font-medium">{{ $currentTag->name }}</strong>
                        <a href="{{ route('recipes.index', array_filter(request()->except(['tag', 'page']))) }}" class="ml-2 text-orange-600 hover:underline text-xs">(Clear tag filter)</a>
                    </p>
                </div>
                @endif
            </div>
            @endif

            <!-- Sort By Links -->
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Sort By:</h3>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('recipes.index', array_merge(request()->except(['sort', 'page']), ['sort' => 'latest'])) }}"
                       class="px-3 py-1.5 text-sm rounded-md shadow-sm
                              {{ $currentSort === 'latest' ? 'bg-orange-500 text-white font-semibold' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Latest
                    </a>
                    <a href="{{ route('recipes.index', array_merge(request()->except(['sort', 'page']), ['sort' => 'likes'])) }}"
                       class="px-3 py-1.5 text-sm rounded-md shadow-sm
                              {{ $currentSort === 'likes' ? 'bg-orange-500 text-white font-semibold' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Most Liked
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Display success message if any (e.g., after deleting a recipe) --}}
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if ($recipes->isEmpty())
        <div class="text-center py-16">
            @if(!empty($searchTerm) || $currentTag)
                <p class="text-2xl text-gray-700 mb-4">No recipes found matching your criteria.</p>
                <a href="{{ route('recipes.index') }}" class="text-orange-600 hover:underline font-semibold">View all recipes</a>
            @else
                <p class="text-2xl text-gray-700 mb-4">No recipes shared yet.</p>
            @endif
            @guest
                <p class="text-gray-500 mt-3">Be the first to share one! <a href="{{ route('register') }}" class="text-orange-600 hover:underline">Register</a> or <a href="{{ route('login') }}" class="text-orange-600 hover:underline">Login</a> to get started.</p>
            @else
                <p class="text-gray-500 mt-3">Why not <a href="{{ route('recipes.create') }}" class="text-orange-600 hover:underline">share your favorite recipe</a>?</p>
            @endguest
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
                        <p class="text-xs text-gray-500 mb-3">By <span class="font-medium">{{ $recipe->user->name }}</span></p>
                        <p class="text-sm text-gray-600 mb-4 flex-grow">
                            {{ Str::limit($recipe->description, 90) }}
                        </p>
                        <div class="mt-auto pt-3 border-t border-gray-100">
                             <a href="{{ route('recipes.show', $recipe) }}" class="inline-block w-full text-center bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium py-2.5 px-4 rounded-md transition duration-150 ease-in-out">
                                View Recipe
                            </a>
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
