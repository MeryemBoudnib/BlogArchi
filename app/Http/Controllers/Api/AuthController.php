<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Valider les données d'entrée
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Trouver l'utilisateur par son email
        $user = User::where('email', $request->email)->first();

        // 3. Vérifier si l'utilisateur existe ET si le mot de passe est correct
        if (! $user || ! Hash::check($request->password, $user->password)) {
            // Si l'authentification échoue, on lève une exception
            throw ValidationException::withMessages([
                'email' => ['Les identifiants fournis sont incorrects.'],
            ]);
        }

        // 4. Si tout est correct, on crée un nouveau jeton pour cet utilisateur
        $token = $user->createToken('api-token')->plainTextToken;

        // 5. On renvoie une réponse JSON avec le jeton
        return response()->json([
            'token' => $token,
        ]);
    }
}