<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Musique;

class MusiqueController extends Controller
{
    public function index(Request $request){
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
}
