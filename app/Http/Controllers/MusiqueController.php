<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Musique;

class MusiqueController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user('sanctum');

        if ($user) {
            // Utilisateur connecté, on récupère aussi les musiques payantes
            $musiques = Musique::with('styles')->get();
        } else {
            // Utilisateur non-connecté on récupère uniquement les musiques gratuites
            $musiques = Musique::with('styles')->where('prix', 0)->get();
        }

        return response()->json([
            'musiques' => $musiques,
        ]);
    }

    public function show(Request $request, Musique $musique)
    {
        // Si la musique n'existe pas
        if (!$musique) {
            return response()->json([
                'message' => 'Musique introuvable.'
            ], 404);
        }

        if ($musique->prix > 0) {
            // musique payante, on vérifie que l'user est connecté
            $user = $request->user('sanctum');

            if (!$user) {
                return response()->json([
                    'message' => 'Vous devez être connecté pour accéder à cette musique payante.'
                ], 401);
            }
        }

        return response()->json([
            'musique' => $musique
        ]);
    }

    public function acheter(Request $request, Musique $musique)
    {
        $user = $request->user();

        if ($musique->prix == 0) {
            return response()->json([
                'message' => 'Cette musique est gratuite, pas besoin de l\'acheter !'
            ], 400); // 400 Bad Request
        }

        // Vérifie si l'utilisateur possède déjà cette musique
        $dejaAchete = $user->musiquesAchetees()
            ->where('musique_id', $musique->id)
            ->exists();

        if ($dejaAchete) {
            return response()->json([
                'message' => 'Vous possédez déjà cette musique.'
            ], 400);
        }

        $user->musiquesAchetees()->attach(
            $musique->id,
            [
                'prix' => $musique->prix,
                'date_achat' => now()
            ]
        );

        return response()->json([
            'message' => "La musique id:'{$musique->id}' a été achetée avec succès !"
        ], 201);
    }
}
