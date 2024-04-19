<?php

namespace App\Http\Controllers;

use App\Models\Character;
use Illuminate\Http\Request;

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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        return view('character.character', ['character' => auth()->user()->characters->find($id)]);
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
        $character = Character::find($id);
        if (!$character || $character->user_id !== auth()->id()) {
            return redirect()->route('characters.index');
        }
        Character::find($id)->delete();
        return redirect()->route('characters.index');
    }
}
