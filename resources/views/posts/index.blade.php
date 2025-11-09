<x-app-layout>
    <x-slot name="header">
        <!-- Titre en gris/noir -->
        <h2 class="font-bold text-xl text-gray-700 leading-tight"> 
            {{ __('Mes Articles') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- FORMULAIRE DE CRÉATION : Style neutre -->
            <div class="mb-10 bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-200">
                <div class="p-6 sm:p-8">
                    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <h3 class="text-2xl font-bold text-gray-800 mb-6 border-b border-gray-200 pb-4">
                            Créer un Nouvel Article
                        </h3>

                        <!-- Affichage des erreurs de validation : style plus élégant -->
                        @if ($errors->any())
                            <div class="mb-6 p-4 text-sm text-red-700 bg-red-100 rounded-lg border border-red-300" role="alert">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Titre -->
                        <div class="mb-6">
                            <label for="title" class="block mb-2 text-sm font-medium text-gray-700">Titre</label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}" 
                                class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-500 focus:border-gray-500 block w-full p-2.5 shadow-sm" 
                                placeholder="Un titre accrocheur pour votre article" required>
                        </div>

                        <!-- Contenu -->
                        <div class="mb-6">
                            <label for="content" class="block mb-2 text-sm font-medium text-gray-700">Contenu</label>
                            <textarea id="content" name="content" rows="6" 
                                class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-500 focus:border-gray-500 block w-full p-2.5 shadow-sm" 
                                placeholder="Rédigez le corps de votre article ici..." required>{{ old('content') }}</textarea>
                        </div>

                        <!-- Image de couverture -->
                        <div class="mb-6">
                            <label for="cover_image" class="block mb-2 text-sm font-medium text-gray-700">Image de couverture</label>
                            <!-- Style du bouton de fichier est géré par la classe 'file:' -->
                            <input type="file" id="cover_image" name="cover_image" 
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                        </div>

                        <!-- Bouton de soumission (Couleur primaire en noir/gris) -->
                        <div class="flex items-center justify-end mt-6">
                            <button type="submit" 
                                class="text-white bg-gray-800 hover:bg-gray-900 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 shadow-md transition duration-150">
                                Publier l'Article
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- LISTE DES ARTICLES : Cartes neutres -->
            <div class="space-y-8">
                @forelse ($posts as $post)
                    <!-- Carte Article : Style moderne, avec survol (hover) -->
                    <article class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition duration-300 ease-in-out transform hover:scale-[1.005] border border-gray-200">
                        <div class="flex flex-col sm:flex-row">
                            
                            <!-- AFFICHAGE DE L'IMAGE DE COUVERTURE -->
                            @if($post->cover_image)
                                <!-- Utilisation d'une division pour contrôler la taille de l'image -->
                                <div class="sm:w-1/3 w-full h-48 sm:h-auto overflow-hidden rounded-t-xl sm:rounded-l-xl sm:rounded-t-none">
                                    <img src="{{ asset('storage/' . $post->cover_image) }}" alt="Image de couverture" 
                                        class="w-full h-full object-cover transition duration-300 hover:opacity-90">
                                </div>
                            @endif

                            <div class="p-6 sm:p-8 flex-1">
                                <!-- Tags (Neutres) -->
                                <div class="flex flex-wrap gap-2 mb-3">
                                    @foreach($post->tags as $tag)
                                        <span class="px-3 py-1 text-xs font-semibold uppercase tracking-wider text-gray-700 bg-gray-100 rounded-full">
                                            #{{ $tag->name }}
                                        </span>
                                    @endforeach
                                </div>

                                <h2 class="text-2xl font-extrabold text-gray-900 mb-2 leading-tight">{{ $post->title }}</h2>
                                
                                <small class="text-sm text-gray-500 block mb-4">
                                    Écrit par : <strong class="font-semibold">{{ $post->user->name }}</strong> le {{ $post->created_at->format('d M Y') }}
                                </small>
                                
                                <!-- Contenu tronqué pour la liste. NOTE : Nécessite 'use Illuminate\Support\Str;' au début si vous utilisez pas de facade -->
                                <p class="text-gray-700 line-clamp-3 mb-6">
                                    {{ Str::limit($post->content, 250) }}
                                </p>
                                
                                <!-- Boutons d'action -->
                                <div class="mt-4 flex space-x-4">
                                    @can('update', $post)
                                        <a href="{{ route('posts.edit', $post) }}" 
                                            class="text-sm font-medium text-gray-600 hover:text-gray-800 transition duration-150">
                                            Modifier
                                        </a>
                                    @endcan

                                    @can('delete', $post)
                                        <form action="{{ route('posts.destroy', $post) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                class="text-sm font-medium text-red-600 hover:text-red-800 transition duration-150" 
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ? Cette action est irréversible.')">
                                                Supprimer
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                        <div class="p-6 text-gray-900 text-center">
                            <p class="font-medium">Aucun article pour le moment. Soyez le premier à en publier un !</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>