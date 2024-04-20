<?php

namespace Database\Seeders;

use App\Models\Character;
use App\Models\Contest;
use App\Models\Place;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $places = Place::all();
        for ($i = 0; $i < 10; $i++) {
            $place = $places->random();
            $enemy = Character::all()->where('enemy', true)->random();
            $hero = Character::all()->where('enemy', false)->random();
            $win = fake()->boolean();

            Contest::factory()
                ->hasAttached($hero, [
                    'enemy_id' => $enemy->id,
                    'hero_hp' => $win ? fake()->numberBetween(1, 20) : 0,
                    'enemy_hp' => $win ? 0 : fake()->numberBetween(1, 20),
                ])->create([
                    'place_id' => $place->id,
                    'win' => $win,
                    'history' => $win ? $hero->name . ' defeated ' . $enemy->name . '.' : $enemy->name . ' defeated ' . $hero->name . '.',
                ]);
        }
    }
}
