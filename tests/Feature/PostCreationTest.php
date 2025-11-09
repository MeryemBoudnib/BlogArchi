<?php

namespace Tests\Feature;

use App\Events\PostCreated;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Queue; // <--- Import de la façade Queue
use Tests\TestCase;

// Si vous utilisez la syntaxe Pest, le contenu complet du fichier est :
it('un utilisateur connecté peut créer un article et l événement est déclenché', function () {
    // 1. Préparer l'environnement
    Storage::fake('public'); // Simuler le système de fichiers
    Event::fake(); // Empêcher l'événement d'être réellement déclenché
    Queue::fake(); // <--- NOUVEAU : Simuler la file d'attente pour éviter l'exécution du listener (et le sleep(5))
    
    $user = User::factory()->create(); 
    $this->actingAs($user); 

    // Données minimales (sans image pour éviter le problème GD)
    $postData = [
        'title' => 'Mon Premier Article Testé',
        'content' => 'Le contenu de mon article test.',
        // 'cover_image' n'est pas inclus si on ne teste pas le fichier
    ];

    // 2. Exécuter l'action (POST vers la route de store)
    $response = $this->post(route('posts.store'), $postData);

    // 3. Vérifier les assertions
    
  // $response->assertRedirect(route('posts.index')); 

    // Ajoutez cette ligne pour voir l'exception exacte si elle est levée
    $response->assertSessionHasNoErrors();
    $response->assertStatus(302); // Vérifie le statut exact de redirection
    
    // Vérifier que l'article a été créé en base de données
    $this->assertDatabaseHas('posts', [
        'title' => 'Mon Premier Article Testé',
        'user_id' => $user->id,
    ]);
    
    // Vérifier que l'événement a été déclenché
    Event::assertDispatched(PostCreated::class);

    // VÉRIFICATION SUPPLÉMENTAIRE : S'assurer que le Job/Listener est bien dans la queue
    // (Cette ligne nécessite l'import de l'écouteur SendPostCreationNotification)
    // Queue::assertPushed(\App\Listeners\SendPostCreationNotification::class); 
});

// Pour la syntaxe PHPUnit si vous voulez la restaurer :
// class PostCreationTest extends TestCase { use ...
//     /** @test */
//     public function un_utilisateur_connecte_peut_creer_un_article_et_l_evenement_est_declenche()
//     {
//         // Logique identique ci-dessus, mais dans la méthode.
//     }
// }