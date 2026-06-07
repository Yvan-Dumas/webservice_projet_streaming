<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Playlist;
use App\Models\Musique;

class PlaylistController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $playlists = Playlist::where('user_id', $user->id)
            ->with('musiques.styles')->get();

        return response()->json([
            'message' => "Playlists de l'utilisateur:",
            'playlists' => $playlists
        ]);
    }

    public function show(Request $request, Playlist $playlist)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'message' => 'Vous devez être connecté pour accéder à cette playlist.'
            ], 401);
        }

        if ($playlist->user_id !== $user->id) {
            return response()->json([
                'message' => 'Action non autorisée. Cette playlist ne vous appartient pas.'
            ], 403);
        }

        $playlist->load('musiques.styles');

        return response()->json([
            'playlist' => $playlist
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom_playlist' => 'required|max:255',
        ]);

        $playlist = Playlist::create([
            'nom_playlist' => $request->nom_playlist,
            'user_id' => $request->user()->id
        ]);

        return response()->json([
            'message' => 'Playlist créée avec succès !',
            'playlist' => $playlist
        ], 201);
    }

    public function destroy(Request $request, Playlist $playlist)
    {
        // On vérifie que la playlist appartient bien à l'utilisateur connecté
        if ($playlist->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Action non autorisée.'
            ], 403);
        }

        $playlist->musiques()->detach();
        $playlist->delete();

        return response()->json([
            'message' => 'Playlist supprimée avec succès !'
        ]);
    }

    public function ajouterMusique(Request $request, Playlist $playlist)
    {
        // On vérifie que la musique demandée existe
        $request->validate([
            'musique_id' => 'required|exists:musiques,id',
        ]);

        $user = $request->user();

        // On vérifie que la playlist appartient bien à l'utilisateur connecté
        if ($playlist->user_id !== $user->id) {
            return response()->json([
                'message' => 'Action non autorisée.'
            ], 403);
        }

        // Vérifie si la musique est déjà dans la playlist
        if ($playlist->musiques()->where('musique_id', $request->musique_id)->exists()) {
            return response()->json([
                'message' => 'Cette musique est déjà dans la playlist.'
            ], 400);
        }

        // Vérifie que l'utilisateur possède la musique si elle est payante
        $musique = Musique::find($request->musique_id);
        if ($musique->prix > 0) {
            $dejaAchete = $user->musiquesAchetees()
                ->where('musique_id', $musique->id)
                ->exists();

            if (!$dejaAchete) {
                return response()->json([
                    'message' => 'Vous devez acheter cette musique avant de l\'ajouter à une playlist.'
                ], 403);
            }
        }

        // On ajoute la musique dans la table d'association
        $playlist->musiques()->attach($request->musique_id);

        // On incrémente le nombre de titres de la playlist
        $playlist->increment('nb_titres');

        $playlistLoaded = Playlist::with('musiques.styles')->find($playlist->id);

        return response()->json([
            'message' => 'Musique ajoutée à la playlist avec succès !',
            'playlist' => $playlistLoaded
        ]);
    }

    public function retirerMusique(Request $request, Playlist $playlist, Musique $musique)
    {
        $user = $request->user();

        // On vérifie que la playlist appartient bien à l'utilisateur connecté
        if ($playlist->user_id !== $user->id) {
            return response()->json([
                'message' => 'Action non autorisée.'
            ], 403);
        }

        // Vérifie si la musique est déjà dans la playlist
        if (!$playlist->musiques()->where('musique_id', $musique->id)->exists()) {
            return response()->json([
                'message' => 'Cette musique n\'est pas dans la playlist.'
            ], 404);
        }

        $playlist->musiques()->detach($musique->id);
        $playlist->decrement('nb_titres');
        $playlistLoaded = Playlist::with('musiques.styles')->find($playlist->id);

        return response()->json([
            'message' => 'Musique retirée de la playlist avec succès !',
            'playlist' => $playlistLoaded
        ]);
    }
}
