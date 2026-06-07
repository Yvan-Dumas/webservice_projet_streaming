<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function mesAchats(Request $request)
    {
        $user = $request->user();

        // On récupère les musiques achetées par l'utilisateur
        $achats = $user->musiquesAchetees;

        return response()->json([
            'factures' => $achats
        ]);
    }
}
