<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage; // <--- 1. Import de Storage

class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'content',
        'cover_image', 
            'user_id', 
    ];
    
    // 2. NOUVEAU : Méthode 'boot' pour gérer les événements du modèle
    protected static function boot()
    {
        parent::boot();

        // Lorsque le Post est sur le point d'être supprimé (l'événement 'deleting' se déclenche)
        static::deleting(function (Post $post) {
            
            // Si une image de couverture existe (que le champ n'est pas vide)
            if ($post->cover_image) {
                // Supprimer le fichier du système de fichiers 'public'
                // La valeur de $post->cover_image est le chemin (ex: posts_covers/xyz.jpg)
                Storage::disk('public')->delete($post->cover_image); 
            }
            
            // Détacher les relations Many-to-Many avant la suppression
            // (Assure que les entrées dans la table pivot post_tag sont nettoyées)
            $post->tags()->detach(); 
        });
    }

    // --- Vos relations (inchangées) ---

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}