<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; // Important : hérite du fichier ci-dessus     
use App\Models\Tag;
use App\Services\PostService;
class PostController extends Controller
{
    /**
     * Affiche la liste de tous les articles.
     */
    public function index()
    {
        $posts = Post::with('user')->latest()->get();

        return view('posts.index', ['posts' => $posts]);
    }

    /**
     * Stocke un nouvel article dans la base de données.
     */
   public function store(StorePostRequest $request, PostService $postService) // <-- Injecter le service
{
    // Le service s'occupe de tout !
    $postService->createPost($request->validated());

    return redirect()->route('posts.index')->with('success', 'Article créé ! Le traitement est en cours.');
}

    /**
     * Affiche le formulaire pour modifier un article spécifique.
     */
    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        $tags = Tag::all();

        return view('posts.edit', ['post' => $post,   'tags' => $tags ]);
    }

    /**
     * Met à jour un article spécifique dans la base de données.
     */
    public function update(StorePostRequest $request, Post $post)
    {
        $this->authorize('update', $post);

        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        $post->update($validatedData);

    // Synchronisation des tags
    $post->tags()->sync($request->tags); 
        return redirect()->route('posts.index');
    }

    /**
     * Supprime un article spécifique de la base de données.
     */
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        $post->delete();

        return redirect()->route('posts.index');
    }
}