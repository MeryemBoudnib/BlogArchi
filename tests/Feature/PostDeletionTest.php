<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PostDeletionTest extends TestCase
{
use \Tests\Traits\UsesDatabaseMigrations;
    /** @test */
    public function la_suppression_d_un_article_supprime_egalement_le_fichier_associe()
    {
        // 1. Préparer l'environnement
        Storage::fake('public'); // Simuler le système de fichiers
        $user = User::factory()->create();
        $this->actingAs($user); 
        
        // NOUVEAU : Simuler le chemin du fichier sans le créer réellement
        $filePath = 'posts_covers/simulated_cover_image.jpg';
        
        // NOUVEAU : Mettre un contenu bidon pour que le Storage le considère comme 'existant'
        Storage::disk('public')->put($filePath, 'dummy content'); 

        // Créer l'article en base de données, lié à l'utilisateur et au chemin du fichier
        $post = Post::factory()->create([
            'user_id' => $user->id,
            'cover_image' => $filePath, // Utiliser le chemin simulé
        ]);

        // 2. Vérifier l'état initial
        $this->assertDatabaseHas('posts', ['id' => $post->id]);
        Storage::disk('public')->assertExists($filePath); // Le fichier simulé est là

        // 3. Exécuter l'action (Suppression du Post)
        $response = $this->delete(route('posts.destroy', $post));

        // 4. Vérifier les assertions
        $response->assertRedirect(route('posts.index'));
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
        
        // VÉRIFICATION CRUCIALE : Le fichier doit avoir disparu
        Storage::disk('public')->assertMissing($filePath);
    }
}