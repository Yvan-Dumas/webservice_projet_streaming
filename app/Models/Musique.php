<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Musique extends Model
{
    use HasFactory;

    public function styles()
    {
        return $this->belongsToMany(Style::class);
    }
}
