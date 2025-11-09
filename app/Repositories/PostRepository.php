<?php

namespace App\Repositories;

use App\Models\Post;

class PostRepository implements PostRepositoryInterface
{
    public function create(array $data): Post
    {
        // On utilise la relation de l'utilisateur connecté pour créer le post
        // Laravel s'occupe du user_id
        return Post::create($data); 
    }
}