<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-700 leading-tight">
            {{ __('Modifier l\'article') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Formulaire de Modification : style cohérent avec la création -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-200">
                <div class="p-6 sm:p-8 text-gray-900">
                
                    <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- Titre -->
                        <div class="mb-6">
                            <label for="title" class="block mb-2 text-sm font-medium text-gray-700">Titre :</label>
                            <input type="text" id="title" name="title" value="{{ old('title', $post->title) }}" 
                                class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-500 focus:border-gray-500 block w-full p-2.5 shadow-sm" 
                                required autofocus>
                        </div>

                        <!-- Contenu -->
                        <div class="mt-4 mb-6">
                            <label for="content" class="block mb-2 text-sm font-medium text-gray-700">Contenu :</label>
                            <textarea id="content" name="content" rows="6" 
                                class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-500 focus:border-gray-500 block w-full p-2.5 shadow-sm" 
                                >{{ old('content', $post->content) }}</textarea>
                        </div>
                        
                        <!-- Image de couverture -->
                        <div class="mt-4 mb-6">
                            <label for="cover_image" class="block mb-2 text-sm font-medium text-gray-700">Image de couverture (Laisser vide pour conserver l'actuelle)</label>
                            <input type="file" id="cover_image" name="cover_image" 
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                                
                            <!-- Afficher l'image actuelle si elle existe -->
                            @if($post->cover_image)
                                <p class="text-xs text-gray-500 mt-2">Image actuelle :</p>
                                <img src="{{ asset('storage/' . $post->cover_image) }}" alt="Couverture actuelle" class="mt-2 w-32 h-auto rounded-lg shadow">
                            @endif
                        </div>

                        <!-- Tags -->
                        <div class="mt-4 mb-6 border-t border-gray-200 pt-6">
                            <label class="block mb-3 text-sm font-medium text-gray-700">Tags</label>
                            <div class="flex flex-wrap gap-4">
                                @foreach ($tags as $tag)
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                            @if($post->tags->contains($tag)) checked @endif
                                            class="w-4 h-4 text-gray-600 bg-gray-100 border-gray-300 rounded focus:ring-gray-500 focus:ring-2"
                                        >
                                        <span class="ml-2 text-sm font-medium text-gray-900">{{ $tag->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Bouton de soumission -->
                        <div class="flex items-center justify-end mt-6 border-t border-gray-200 pt-6">
                            <button type="submit" 
                                class="text-white bg-gray-800 hover:bg-gray-900 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 shadow-md transition duration-150">
                                Mettre à jour l'Article
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>