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

        $admin = User::find(1);
        Character::factory()->for($admin)->create(
            [
                'user_id' => $admin->id,
                'enemy' => true,
                'name' => 'Test Enemy',
            ]
        );

        $users = User::all();
        foreach ($users as $user) {
            for ($i = 0; $i < 5; $i++) {
                Character::factory()->for($user)->create(
                    [
                        'user_id' => $user->id,
                        'enemy' => $user['admin'] ? fake()->boolean() : false,
                    ]
                );
            }
        }
    }
}
