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
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $character = Character::find($request->input('character'));
        $isEnemy = $character->enemy;
        $hero = $character;
        if ($isEnemy) {
            $enemy = Character::all()->where('enemy', false)->random();
        } else {
            $enemy = Character::all()->where('enemy', true)->random();
        }
        $place = Place::all()->random();
        $contest = Contest::factory()
            ->hasAttached($hero, [
                'enemy_id' => $enemy->id,
            ])->create([
                'place_id' => $place->id,
                'win' => null,
                'history' => '',
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
        $hero = Character::find($characters->first()->id);
        $enemy = Character::find($characters->first()->pivot->enemy_id);
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

        $data = $contest->characters->first()->pivot;

        $enemy = Character::find($data->enemy_id);
        $hero = Character::find($data->character_id);

        $enemy_hp = $data->enemy_hp;
        $hero_hp = $data->hero_hp;


        // The hero attacks

        $enemy_damage = $this->calculateDamage($type, $hero, $enemy);

        $enemy_hp -= $enemy_damage;

        $contest->update([
            'history' => $contest->history . '[' . Carbon::now('Europe/Budapest')->format('Y.m.d. H:i:s') . '] ' . $hero->name . ' attacked ' . $enemy->name . ' with ' . $type . ' and dealt ' . $enemy_damage . ' damage.' . '<br>',
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

        return redirect()->route('contests.show', $id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
