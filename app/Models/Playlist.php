<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    protected $fillable = [
        'nom_playlist',
        'user_id'
    ];

    public function musiques(){
        return $this->belongsToMany(Musique::class);
    }
}
