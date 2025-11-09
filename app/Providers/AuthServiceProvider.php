<?php

namespace App\Providers;

use App\Models\Post;         // <--- AJOUTER CETTE LIGNE
use App\Policies\PostPolicy; // <--- AJOUTER CETTE LIGNE
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Post::class => PostPolicy::class, // <--- AJOUTER CETTE LIGNE DANS CE TABLEAU
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}