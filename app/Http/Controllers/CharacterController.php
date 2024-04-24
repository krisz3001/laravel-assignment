<?php

namespace App\Http\Controllers;

use App\Models\Character;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CharacterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('character.characters', ['characters' => auth()->user()->characters]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('character.characterform');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'defence' => 'required|integer|min:0|max:3',
            'strength' => 'required|integer|min:0|max:20',
            'accuracy' => 'required|integer|min:0|max:20',
            'magic' => 'required|integer|min:0|max:20',
        ]);

        $sum = $validated['defence'] + $validated['strength'] + $validated['accuracy'] + $validated['magic'];
        if ($sum != 20) {
            return redirect()->back()->withErrors(['sum' => 'The sum of attributes must be 20.'])->withInput($request->input());
        }

        $character = new Character();
        $character->name = $validated['name'];
        $character->defence = $validated['defence'];
        $character->strength = $validated['strength'];
        $character->accuracy = $validated['accuracy'];
        $character->magic = $validated['magic'];
        $character->enemy = $request->input('enemy', false) === '1';
        $character->user_id = auth()->id();
        $character->save();

        return redirect()->route('characters.show', $character);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $character = Character::find($id);
        if (!$character || $character->user_id !== auth()->id()) {
            return redirect()->route('characters.index');
        }

        $enemyCount = Character::all()->where('enemy', true)->count();
        $enoughEnemies =  ($character->enemy && $enemyCount > 1) || (!$character->enemy && $enemyCount > 0);

        return view('character.character', ['character' => auth()->user()->characters->find($id), 'characters' => Character::all(), 'enoughEnemies' => $enoughEnemies]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $character = Character::find($id);
        if (!$character || $character->user_id !== auth()->id()) {
            return redirect()->route('characters.index');
        }
        return view('character.characterform', ['character' => $character]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $character = Character::find($id);
        if (!$character || $character->user_id !== auth()->id()) {
            return redirect()->route('characters.index');
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'defence' => 'required|integer|min:0|max:3',
            'strength' => 'required|integer|min:0|max:20',
            'accuracy' => 'required|integer|min:0|max:20',
            'magic' => 'required|integer|min:0|max:20',
        ]);

        $sum = $validated['defence'] + $validated['strength'] + $validated['accuracy'] + $validated['magic'];
        if ($sum != 20) {
            return redirect()->back()->withErrors(['sum' => 'The sum of attributes must be 20.'])->withInput($request->input());
        }

        $character->name = $validated['name'];
        $character->defence = $validated['defence'];
        $character->strength = $validated['strength'];
        $character->accuracy = $validated['accuracy'];
        $character->magic = $validated['magic'];
        $character->enemy = $request->input('enemy', false) === '1';
        $character->update();

        return redirect()->route('characters.show', $character);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $character = Character::find($id);
        if (!$character || $character->user_id !== auth()->id()) {
            return redirect()->route('characters.index');
        }
        $character->delete();
        return redirect()->route('characters.index');
    }
}
