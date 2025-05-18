@extends('layouts.app')

@section('title', 'Create New Recipe')

@section('content')
<div class="bg-gray-50 py-8 md:py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto bg-white p-8 md:p-10 rounded-xl shadow-xl">
            <h1 class="text-3xl font-bold text-gray-900 mb-8 text-center">Share Your Culinary Creation</h1>

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

            <form action="{{ route('recipes.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Recipe Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
                           class="mt-1 block w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm placeholder-gray-400">
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Short Description</label>
                    <textarea name="description" id="description" rows="3"
                              class="mt-1 block w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm placeholder-gray-400" placeholder="A brief summary of your recipe..."></textarea>
                </div>

                <div>
                    <label for="ingredients" class="block text-sm font-medium text-gray-700 mb-1">Ingredients</label>
                    <textarea name="ingredients" id="ingredients" rows="6" required
                              class="mt-1 block w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm placeholder-gray-400"
                              placeholder="List each ingredient on a new line. E.g.,&#10;1 cup flour&#10;2 large eggs&#10;1/2 tsp salt">{{ old('ingredients') }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">List each ingredient on a new line.</p>
                </div>

                <div>
                    <label for="instructions_input" class="block text-sm font-medium text-gray-700 mb-1">Instructions</label>
                    <input id="instructions_input" type="hidden" name="instructions" value="{{ old('instructions') }}">
                    <div class="mt-1 trix-editor-container rounded-lg border border-gray-300 shadow-sm focus-within:ring-1 focus-within:ring-orange-500 focus-within:border-orange-500">
                        <trix-editor input="instructions_input" class="trix-content min-h-[200px] p-2.5"></trix-editor>
                    </div>
                    @error('instructions')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="thumbnail_image_path" class="block text-sm font-medium text-gray-700 mb-1">Thumbnail Image</label>
                    <input type="file" name="thumbnail_image_path" id="thumbnail_image_path"
                           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100 cursor-pointer border border-gray-300 rounded-lg">
                </div>

                <div>
                    <label for="tags" class="block text-sm font-medium text-gray-700 mb-1">Tags (comma-separated)</label>
                    <input type="text" name="tags" id="tags" value="{{ old('tags') }}"
                           class="mt-1 block w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm placeholder-gray-400"
                           placeholder="e.g., ayam, pedas, sarapan">
                    <p class="mt-1 text-xs text-gray-500">Enter tags separated by commas.</p>
                </div>
                
                {{-- Placeholder for Recipe Steps - to be implemented later --}}
                {{-- 
                <div class="border-t pt-6 mt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Recipe Steps (Optional)</h3>
                    <div id="recipe-steps-container" class="space-y-4">
                        </div>
                    <button type="button" id="add-step-button" class="mt-2 text-sm text-orange-600 hover:text-orange-800">Add Step</button>
                </div>
                --}}

                <div class="pt-6">
                    <button type="submit"
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-orange-500 hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-150">
                        Publish Recipe
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- @section('scripts')
<script>
    // Basic JS for adding recipe steps dynamically - to be enhanced
    // document.getElementById('add-step-button')?.addEventListener('click', function() {
    //     const container = document.getElementById('recipe-steps-container');
    //     const stepCount = container.children.length;
    //     const newStepHtml = `
    //         <div class="border p-4 rounded-md">
    //             <label for="step_description_${stepCount}" class="block text-sm font-medium text-gray-700">Step ${stepCount + 1} Description</label>
    //             <textarea name="steps[${stepCount}][description]" id="step_description_${stepCount}" rows="2" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
    //             <label for="step_image_${stepCount}" class="block text-sm font-medium text-gray-700 mt-2">Step ${stepCount + 1} Image (Optional)</label>
    //             <input type="file" name="steps[${stepCount}][image]" id="step_image_${stepCount}" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
    //         </div>
    //     `;
    //     container.insertAdjacentHTML('beforeend', newStepHtml);
    // });
</script>
@endsection --}}
@endsection
