<?php

namespace App\Listeners;

use App\Events\PostCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendPostCreationNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
      public function handle(PostCreated $event): void
    {
        // Pour l'instant, on va juste simuler une tâche longue
        // en écrivant dans le fichier de log.
        // Dans un vrai projet, on pousserait un Job ici.
        sleep(5); // Simule une tâche de 5 secondes
        \Log::info("Notification pour le post '{$event->post->title}' envoyée !");
    }
}
