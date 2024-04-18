<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        $characters = Character::all();
        return view('welcome', ['users' => $users, 'characters' => $characters]);
    }
}
