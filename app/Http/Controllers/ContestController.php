<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Contest;
use App\Models\Place;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ContestController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $hero = Character::find($request->input('character'));
        if ($hero->enemy && Character::all()->where('enemy', true)->count() < 2) {
            return redirect()->route('characters.index');
        }
        $enemy = Character::all()->where('enemy', true)->where('id', '!=', $hero->id)->random();
        $place = Place::all()->random();
        $contest = Contest::factory()->create([
            'place_id' => $place->id,
            'win' => null,
            'history' => '',
        ]);

        $hp = 20;

        $contest->characters()->attach($hero->id, [
            'hero_hp' => $hp,
            'enemy_hp' => $hp,
        ]);

        $contest->characters()->attach($enemy->id, [
            'hero_hp' => $hp,
            'enemy_hp' => $hp,
        ]);

        return redirect()->route('contests.show', $contest);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $contest = Contest::find($id);
        if (!$contest) {
            return redirect()->route('characters.index');
        }

        $characters = $contest->characters;

        if (auth()->user()->id !== $characters->first()->user_id) {
            return redirect()->route('characters.index');
        }

        if ($characters->where('enemy', true)->count() === 2) {
            $hero = $characters->where('enemy', true)->first();
            $enemy = $characters->where('enemy', true)->last();
            return view('contest.contest', ['contest' => $contest, 'hero' => $hero, 'enemy' => $enemy]);
        } else if ($characters->where('enemy', false)->count() === 1) {
            $hero = $characters->where('enemy', false)->first();
            $enemy = $characters->where('enemy', true)->first();
            return view('contest.contest', ['contest' => $contest, 'hero' => $hero, 'enemy' => $enemy]);
        }

        $hero = $characters->first();
        $enemy = $characters->last();
        return view('contest.contest', ['contest' => $contest, 'hero' => $hero, 'enemy' => $enemy]);
    }

    private function calculateDamage($type, $att, $def): float
    {
        if ($type === 'melee') {
            $damage = ($att->strength * 0.7 + $att->accuracy * 0.1 + $att->magic * 0.1) - $def->defence;
        } elseif ($type === 'ranged') {
            $damage = ($att->strength * 0.1 + $att->accuracy * 0.7 + $att->magic * 0.1) - $def->defence;
        } elseif ($type === 'magic') {
            $damage = ($att->strength * 0.1 + $att->accuracy * 0.1 + $att->magic * 0.7) - $def->defence;
        }

        return $damage >= 0 ? $damage : 0;
    }

    public function attack($id, $type)
    {
        $contest = Contest::find($id);
        if (!$contest || $contest->win !== null || !in_array($type, ['melee', 'ranged', 'magic'])) {
            return redirect()->route('contests.show', $id);
        }

        $characters = $contest->characters;


        if ($characters->where('enemy', true)->count() === 2) {
            $hero = $characters->where('enemy', true)->first();
            $enemy = $characters->where('enemy', true)->last();

            $hero_hp = $hero->pivot->hero_hp;
            $enemy_hp = $enemy->pivot->enemy_hp;
        } else if ($characters->where('enemy', false)->count() === 1) {
            $hero = $characters->where('enemy', false)->first();
            $enemy = $characters->where('enemy', true)->first();

            $hero_hp = $hero->pivot->hero_hp;
            $enemy_hp = $enemy->pivot->enemy_hp;
        } else {
            $hero = $characters->first();
            $enemy = $characters->last();

            $hero_hp = $hero->pivot->hero_hp;
            $enemy_hp = $enemy->pivot->enemy_hp;
        }


        // The hero attacks

        $enemy_damage = $this->calculateDamage($type, $hero, $enemy);

        $enemy_hp -= $enemy_damage;

        $contest->update([
            'history' => $contest->history . '[' . Carbon::now('Europe/Budapest')->format('Y.m.d. H:i:s') . '] ' . $hero->name . ' attacked ' . $enemy->name . ' with ' . $type . ' and dealt ' . $enemy_damage . ' damage.' . '<br>',
        ]);

        $contest->characters()->updateExistingPivot($enemy->id, [
            'enemy_hp' => $enemy_hp <= 0 ? 0 : $enemy_hp,
        ]);

        $contest->characters()->updateExistingPivot($hero->id, [
            'enemy_hp' => $enemy_hp <= 0 ? 0 : $enemy_hp,
        ]);

        if ($enemy_hp <= 0) {
            $enemy_hp = 0;
            $contest->update([
                'win' => 1,
                'history' => $contest->history . '[' . Carbon::now('Europe/Budapest')->format('Y.m.d. H:i:s') . '] ' . $hero->name . ' defeated ' . $enemy->name . '.' . '<br>',
            ]);

            return redirect()->route('contests.show', $id);
        }


        // The enemy attacks

        $enemy_attack_type = ['melee', 'ranged', 'magic'][rand(0, 2)];

        $hero_damage = $this->calculateDamage($enemy_attack_type, $enemy, $hero);

        $hero_hp -= $hero_damage;

        $contest->update([
            'history' => $contest->history . '[' . Carbon::now('Europe/Budapest')->format('Y.m.d. H:i:s') . '] ' . $enemy->name . ' attacked ' . $hero->name . ' with ' . $enemy_attack_type . ' and dealt ' . $hero_damage . ' damage.' . '<br>',
        ]);

        if ($hero_hp <= 0) {
            $hero_hp = 0;
            $contest->update([
                'win' => 0,
                'history' => $contest->history . '[' . Carbon::now('Europe/Budapest')->format('Y.m.d. H:i:s') . '] ' . $enemy->name . ' defeated ' . $hero->name . '.' . '<br>',
            ]);
        }

        $contest->characters()->updateExistingPivot($hero->id, [
            'hero_hp' => $hero_hp,
        ]);

        $contest->characters()->updateExistingPivot($enemy->id, [
            'hero_hp' => $hero_hp,
        ]);

        return redirect()->route('contests.show', $id);
    }
}
