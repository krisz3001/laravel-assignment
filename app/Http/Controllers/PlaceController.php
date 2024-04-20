<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PlaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('place.places', ['places' => Place::all()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('place.placeform');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image',
        ]);

        $path = $request->file('image')->store('public');

        $place = Place::create([
            'name' => $validated['name'],
            'image' => $path,

        ]);
        $place->save();

        return redirect()->route('places.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('place.placeform', ['place' => Place::find($id)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image',
        ]);

        $place = Place::find($id);

        $file = $request->file('image');

        if ($file) {
            $path = $file->store('public');
            Storage::delete($place->image);
            $place->image = $path;
        }

        $place->name = $validated['name'];
        $place->update();

        return redirect()->route('places.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $place = Place::find($id);
        if (!$place) {
            return redirect()->route('places.index');
        }
        Storage::delete($place->image);
        $place->delete();

        return redirect()->route('places.index');
    }
}
