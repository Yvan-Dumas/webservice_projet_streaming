<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Album;
use App\Models\Artiste;
use App\Models\Style;
use App\Models\Musique;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // test utilisateur
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => bcrypt('password'),
        ]);

        // Création des styles
        $styles = ['Rock', 'Rap', 'Electro', 'Jazz', 'Pop'];
        foreach ($styles as $styleNom) {
            Style::create(['nom_style' => $styleNom]);
        }

        $tousLesStyles = Style::all();

        // Création de 3 artistes
        Artiste::factory(3)->create()->each(
            function ($artiste) use ($tousLesStyles) {
                // 2 albums par artiste
                Album::factory(2)->create(['artiste_id' => $artiste->id])->each(
                    function ($album) use ($tousLesStyles) {
                    // On génère 8 musiques associées à chaque album
                    for ($i = 1; $i <= 8; $i++) {
                        $attributs = [
                            'album_id' => $album->id,
                            'numero_musique' => $i,
                        ];

                        if ($i === 1) {
                            $attributs['prix'] = 0;
                        }

                        $musique = Musique::factory()->create($attributs);

                        $stylesAleatoires = $tousLesStyles->random(rand(1, 2));
                        $musique->styles()->attach($stylesAleatoires);
                    }
                }
                );
            }
        );
    }
}
