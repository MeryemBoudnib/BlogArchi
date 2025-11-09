<?php

namespace App\Services;

use App\Events\PostCreated;
use App\Repositories\PostRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB; // <-- NECESSAIRE POUR LA TRANSACTION
use Illuminate\Support\Facades\Auth; // <-- NECESSAIRE POUR Auth::id()

class PostService
{
    protected PostRepositoryInterface $postRepository;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function createPost(array $data): void
    {
        // 1. Gérer l'upload de l'image
        if (isset($data['cover_image'])) {
            $path = $data['cover_image']->store('posts_covers', 'public');
            $data['cover_image'] = $path;
        } else {
            $data['cover_image'] = null;
        }

        // 2. Ajouter l'ID de l'utilisateur connecté
        $data['user_id'] = Auth::id(); 
        
        // 3. Encapsuler les opérations critiques dans une transaction
        DB::beginTransaction();

        try {
            // 4. Utiliser le Repository pour créer le post
            $post = $this->postRepository->create($data);

            // 5. Déclencher l'événement
            PostCreated::dispatch($post); 

            // Valider la transaction
            DB::commit(); 
            
        } catch (\Exception $e) {
            
            DB::rollBack(); 
            
            // Nettoyage du fichier uploadé si l'erreur est survenue après l'upload
            if (isset($path) && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            
            // Re-lancer l'exception pour le contrôleur
            throw $e; 
        }
    }
}