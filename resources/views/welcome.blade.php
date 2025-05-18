@extends('layouts.app')

@section('title', 'Welcome to ResepKita - Your Daily Cooking Inspiration!')

@section('content')
<div class="bg-gray-50">
    <!-- Top Search Bar Section -->
    <div class="bg-white py-6 shadow-md">
        <div class="container mx-auto px-4">
            <form action="{{ route('recipes.index') }}" method="GET" class="max-w-2xl mx-auto flex">
                <input type="text" name="search" value="{{ request('search', '') }}" 
                       placeholder="Cari resep, bahan, pengguna..."
                       class="w-full px-4 py-3 border border-gray-300 rounded-l-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-lg">
                <button type="submit"
                        class="px-6 py-3 bg-orange-500 text-white font-semibold rounded-r-lg hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 text-lg">
                    Cari
                </button>
            </form>
        </div>
    </div>

    
    <div class="container mx-auto px-4 py-12">
        <!-- Pencarian Populer -->
        @if(!empty($popularSearchTerms))
        <section class="mb-12">
            <h3 class="text-2xl font-semibold text-gray-800 mb-6">Pencarian Populer</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($popularSearchTerms as $item)
                <a href="{{ route('recipes.index', ['search' => $item->search_query]) }}" class="block bg-white p-3 rounded-lg shadow hover:shadow-lg transition-shadow duration-150">
                    @if($item->image_url)
                        <img src="{{ $item->image_url }}" alt="{{ $item->alt_text }}" class="w-full h-24 object-cover rounded-md mb-2">
                    @else
                        {{-- Fallback placeholder if no image_url is provided --}}
                        <img src="https://via.placeholder.com/150/E2E8F0/4A5568?text={{ urlencode(Str::limit($item->term, 15)) }}" alt="{{ $item->term }}" class="w-full h-24 object-cover rounded-md mb-2">
                    @endif
                    <span class="text-sm font-medium text-gray-700 block text-center truncate">{{ Str::title($item->term) }}</span>
                </a>
                @endforeach
            </div>
        </section>
        @endif

        <!-- Resep Populer Saat Ini -->
        @if(isset($latestRecipes) && $latestRecipes->isNotEmpty()) {{-- Controller passes popularRecipes as latestRecipes for this view --}}
        <section class="mb-12">
            <h3 class="text-2xl font-semibold text-gray-800 mb-6">Resep Populer Saat Ini</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($latestRecipes as $recipe)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden flex flex-col transition-all duration-300 hover:shadow-2xl">
                    <a href="{{ route('recipes.show', $recipe) }}" class="block">
                        @if ($recipe->thumbnail_image_path)
                            <img src="{{ asset('storage/' . $recipe->thumbnail_image_path) }}" alt="{{ $recipe->title }}" class="w-full h-56 object-cover transform hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-56 bg-gradient-to-br from-gray-300 to-gray-400 flex items-center justify-center text-gray-500 text-center p-4">
                                <span class="text-lg">{{ $recipe->title }}<br><span class="text-sm">No Image Available</span></span>
                            </div>
                        @endif
                    </a>
                    <div class="p-6 flex flex-col flex-grow">
                        @if($recipe->tags->isNotEmpty())
                        <div class="mb-3">
                            @foreach($recipe->tags->take(3) as $tag) {{-- Show max 3 tags --}}
                                <span class="inline-block bg-orange-100 text-orange-700 text-xs font-semibold mr-1 px-2 py-0.5 rounded-full">{{ $tag->name }}</span>
                            @endforeach
                        </div>
                        @endif
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">
                            <a href="{{ route('recipes.show', $recipe) }}" class="hover:text-orange-600 transition-colors duration-150">{{ $recipe->title }}</a>
                        </h3>
                        <p class="text-sm text-gray-500 mb-2">By {{ $recipe->user->name }}</p>
                        <p class="text-gray-700 text-sm mb-4 flex-grow">
                            {{ Str::limit($recipe->description, 100) }}
                        </p>
                        <div class="mt-auto">
                             <a href="{{ route('recipes.show', $recipe) }}" class="inline-block bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-150 ease-in-out">
                                View Recipe
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        <!-- Kategori (Based on Tags) -->
        @if(isset($allTags) && $allTags->isNotEmpty())
        <section class="mb-12">
            <h3 class="text-2xl font-semibold text-gray-800 mb-6">Telusuri Berdasarkan Kategori</h3>
            <div class="flex flex-wrap gap-3">
                @foreach($allTags->take(15) as $tag) {{-- Show a limited number of tags for brevity --}}
                <a href="{{ route('recipes.index', ['tag' => $tag->slug]) }}"
                   class="px-4 py-2 text-sm rounded-full font-medium
                          bg-gray-200 text-gray-700 hover:bg-orange-500 hover:text-white transition-colors duration-150">
                    {{ $tag->name }} ({{ $tag->recipes_count }})
                </a>
                @endforeach
            </div>
        </section>
        @endif
        
        <div class="text-center mt-12 py-8">
            <a href="{{ route('recipes.index') }}" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-8 rounded-lg text-lg shadow-md hover:shadow-lg transition-all duration-150 ease-in-out">
                Lihat Semua Resep &rarr;
            </a>
        </div>

    </div> <!-- End Container -->
</div> <!-- End bg-gray-50 -->
@endsection
