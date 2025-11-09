<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Factories\PostFactory;
class PostPolicyTest extends TestCase
{
use \Tests\Traits\UsesDatabaseMigrations;
    /** @test */
    public function un_proprietaire_peut_acceder_a_la_page_dedition_de_son_article()
    {
        $owner = User::factory()->create();
$post = \Database\Factories\PostFactory::new()->create(['user_id' => $owner->id]);

        $response = $this->actingAs($owner)->get(route('posts.edit', $post));

        $response->assertStatus(200); // Doit réussir
    }

    /** @test */
    public function un_utilisateur_non_proprietaire_ne_peut_pas_acceder_a_la_page_dedition()
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $owner->id]); // L'article appartient à $owner

        // Tenter d'accéder à la page d'édition en tant que $otherUser
        $response = $this->actingAs($otherUser)->get(route('posts.edit', $post));

        // Vérifier l'erreur 403 (Forbidden) ou la redirection vers 404
        $response->assertStatus(403); 
    }

    /** @test */
    public function un_utilisateur_non_proprietaire_ne_peut_pas_supprimer_un_article()
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $owner->id]);

        // Tenter de supprimer en tant que $otherUser
        $response = $this->actingAs($otherUser)->delete(route('posts.destroy', $post));

        // Vérifier l'erreur 403 (Forbidden)
        $response->assertStatus(403);
        
        // S'assurer que l'article est toujours dans la base de données
        $this->assertDatabaseHas('posts', ['id' => $post->id]);
    }
}