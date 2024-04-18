<?php

namespace Database\Seeders;

use App\Models\Character;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CharacterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $users = User::all();
        foreach ($users as $user) {
            Character::factory(5)
                ->for($user)
                ->create([
                    'user_id' => $user->id,
                    'enemy' => $user['admin'] ? fake()->boolean() : false,
                ]);
        }
    }
}
