<?php

use App\Http\Controllers\CharacterController;
use App\Http\Controllers\ContestController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\isAdmin;
use App\Models\Contest;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    $users = User::all();
    $contests = Contest::all();
    return view('character.default', ['users_count' => $users->count(), 'contests_count' => $contests->count()]);
});

Route::get('/dashboard', function () {
    return redirect()->route('characters.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/contests/{contest}/attack/{type}', [ContestController::class, 'attack'])->name('contests.attack');

    Route::resource('characters', CharacterController::class);
    Route::resource('contests', ContestController::class)->except(['index', 'create', 'edit', 'update', 'destroy']);;


    Route::resource('places', PlaceController::class)->middleware(isAdmin::class);
});

Route::fallback(function () {
    return redirect('/');
});

require __DIR__ . '/auth.php';
