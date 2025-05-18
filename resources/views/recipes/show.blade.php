@extends('layouts.app')

@section('title', $recipe->title)

@section('content')
<div class="bg-gray-50 py-8 md:py-12">
    <div class="container mx-auto px-4">
        <article class="bg-white p-6 md:p-10 rounded-xl shadow-xl">
            {{-- Display success message if any --}}
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-8 rounded-md" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <!-- Recipe Title -->
            <h1 class="text-3xl md:text-5xl font-bold text-gray-900 mb-3 leading-tight">{{ $recipe->title }}</h1>

            <!-- Meta Information: Author and Date -->
            <div class="mb-8 text-sm text-gray-500 flex flex-wrap items-center gap-x-4 gap-y-2">
                <span>By <a href="#" class="font-medium text-orange-600 hover:underline">{{ $recipe->user->name }}</a></span>
                <span class="hidden sm:inline">|</span>
                <span>Published on {{ $recipe->published_at ? $recipe->published_at->format('F j, Y') : $recipe->created_at->format('F j, Y') }}</span>
                @auth
                    @if(Auth::id() == $recipe->user_id)
                        <span class="hidden sm:inline">|</span>
                        <a href="{{ route('recipes.edit', $recipe) }}" class="font-medium text-orange-600 hover:text-orange-700">Edit Recipe</a>
                        <form action="{{ route('recipes.destroy', $recipe) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this recipe?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="font-medium text-red-600 hover:text-red-700">Delete Recipe</button>
                        </form>
                    @endif
                @endauth
            </div>

            <!-- Tags -->
            @if ($recipe->tags->isNotEmpty())
                <div class="mb-8">
                    <span class="font-semibold text-gray-700 mr-2">Tags:</span>
                    @foreach ($recipe->tags as $tag)
                        <a href="{{ route('recipes.index', ['tag' => $tag->slug]) }}" 
                           class="inline-block bg-orange-100 text-orange-700 text-xs font-semibold mr-2 mb-2 px-3 py-1 rounded-full hover:bg-orange-200 transition-colors">
                            {{ $tag->name }}
                        </a>
                    @endforeach
                </div>
            @endif

            <!-- Thumbnail Image -->
            @if ($recipe->thumbnail_image_path)
                <div class="mb-8 rounded-lg overflow-hidden shadow-lg">
                    <img src="{{ asset('storage/' . $recipe->thumbnail_image_path) }}" alt="{{ $recipe->title }}" class="w-full max-h-[600px] object-cover">
                </div>
            @endif

            <!-- Description -->
        @if ($recipe->description)
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-700 mb-3">Description</h2>
                <div class="prose max-w-none text-gray-700">
                    {!! nl2br(e($recipe->description)) !!}
                </div>
            </section>
        @endif

        <!-- Ingredients -->
        <section class="mb-10">
            <h2 class="text-2xl md:text-3xl font-semibold text-gray-800 mb-4 border-b pb-2">Ingredients</h2>
            <div class="prose prose-orange max-w-none text-gray-700">
                {{-- Assuming ingredients are stored as a block of text, each on a new line --}}
                <ul class="list-disc pl-5 space-y-1">
                    @foreach(explode("\n", $recipe->ingredients) as $ingredient)
                        @if(trim($ingredient))
                            <li>{{ trim($ingredient) }}</li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </section>

        <!-- Instructions -->
        <section class="mb-10">
            <h2 class="text-2xl md:text-3xl font-semibold text-gray-800 mb-4 border-b pb-2">Instructions</h2>
            <div class="prose prose-orange max-w-none text-gray-700 trix-content">
                {!! $recipe->instructions !!}
            </div>
        </section>

        <!-- Recipe Steps (if any) -->
        @if ($recipe->steps->isNotEmpty())
            <section class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-700 mb-3">Steps</h2>
                <div class="space-y-6">
                    @foreach ($recipe->steps as $step)
                        <div class="p-6 bg-gray-50 rounded-lg border border-gray-200">
                            <h3 class="text-xl font-semibold text-gray-800 mb-3">Step {{ $step->step_number }}</h3>
                            @if ($step->image_path)
                                <img src="{{ asset('storage/' . $step->image_path) }}" alt="Step {{ $step->step_number }} image" class="w-full md:w-2/3 lg:w-1/2 rounded-lg mb-3 shadow-md">
                            @endif
                            <div class="prose prose-orange max-w-none text-gray-700">
                                {!! nl2br(e($step->description)) !!} {{-- Assuming steps are plain text for now --}}
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Like Button / Unlike Button -->
        <div class="my-8 text-center">
            @auth
                @php
                    $userHasLiked = $recipe->likes->contains('user_id', Auth::id());
                @endphp
                @if ($userHasLiked)
                    {{-- Unlike Form --}}
                    <form action="{{ route('recipes.unlike', $recipe) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-2 px-4 rounded-full transition duration-150 ease-in-out">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1 fill-current text-orange-500" viewBox="0 0 20 20" fill="currentColor"> {{-- Icon color changed --}}
                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                            </svg>
                            Unlike ({{ $recipe->likes->count() }})
                        </button>
                    </form>
                @else
                    {{-- Like Form --}}
                    <form action="{{ route('recipes.like', $recipe) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-4 rounded-full transition duration-150 ease-in-out">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                            </svg>
                            Like ({{ $recipe->likes->count() }})
                        </button>
                    </form>
                @endif
            @else
                {{-- Guest view of Like button --}}
                <a href="{{ route('login', ['redirect' => url()->current()]) }}" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-4 rounded-full transition duration-150 ease-in-out">
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                    </svg>
                    Like ({{ $recipe->likes->count() }})
                </a>
                <p class="text-xs text-gray-500 mt-1">Login to like this recipe.</p>
            @endauth
        </div>

        <!-- Comments Section -->
        <section id="comments" class="pt-8 border-t">
            <h2 class="text-2xl md:text-3xl font-semibold text-gray-800 mb-6 border-b pb-2">Comments ({{ $recipe->comments->count() }})</h2>
            
            <!-- Comment Form (for logged-in users) -->
            @auth
                <form action="{{ route('comments.store') }}" method="POST" class="mb-8 p-6 bg-gray-50 rounded-lg border">
                    @csrf
                    <input type="hidden" name="recipe_id" value="{{ $recipe->id }}">
                    <div>
                        <label for="comment_body" class="block text-sm font-medium text-gray-700 mb-1">Your Comment</label>
                        <textarea name="body" id="comment_body" rows="4" class="w-full p-3 border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 @error('body') border-red-500 @enderror" placeholder="Share your thoughts on this recipe..." required>{{ old('body') }}</textarea>
                        @error('body')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="py-2.5 px-6 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-md shadow-sm transition duration-150">Post Comment</button>
                    </div>
                </form>
            @else
                <p class="mb-8 text-gray-600 bg-gray-100 p-4 rounded-md">Please <a href="{{ route('login', ['redirect' => url()->current()]) }}" class="text-orange-600 hover:underline font-medium">login</a> or <a href="{{ route('register') }}" class="text-orange-600 hover:underline font-medium">register</a> to post a comment.</p>
            @endauth

            <!-- Display Comments -->
            <div class="space-y-6">
                @forelse ($recipe->comments as $comment)
                    <div class="p-5 bg-white rounded-lg border border-gray-200 shadow-sm">
                        <div class="flex items-center mb-2">
                            {{-- Placeholder for user avatar --}}
                            <div class="w-10 h-10 rounded-full bg-orange-500 text-white flex items-center justify-center text-lg font-semibold mr-3">
                                {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">{{ $comment->user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <p class="text-gray-700 leading-relaxed">{!! nl2br(e($comment->body)) !!}</p>
                        {{-- Add delete/edit comment buttons if authorized --}}
                        {{-- 
                        @auth
                            @if(Auth::id() == $comment->user_id)
                                // Edit/Delete form for comment
                            @endif
                        @endauth
                        --}}
                    </div>
                @empty
                    <p class="text-gray-600">No comments yet. Be the first to comment!</p>
                @endforelse
            </div>
        </section>

    </article>
</div>
@endsection
