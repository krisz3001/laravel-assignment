<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Character>
 */
class CharacterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $attributes = $this->getAttributes();

        return [
            'name' => fake()->name(),
            'defence' => $attributes['defence'],
            'strength' => $attributes['strength'],
            'accuracy' => $attributes['accuracy'],
            'magic' => $attributes['magic'],
        ];
    }

    private function getAttributes(): array
    {
        $attributes = [
            'defence' => 0,
            'strength' => 0,
            'accuracy' => 0,
            'magic' => 0,
        ];

        $balanceDefence = 0;
        while (array_sum($attributes) !== 20) {

            $rand = random_int(0, 3);

            if ($rand === 0) $balanceDefence++;

            if ($rand === 0 && $balanceDefence === 4 && $attributes['defence'] < 3) {
                $attributes['defence']++;
                $balanceDefence = 0;
            } elseif ($rand === 1 && $attributes['strength'] < 20) {
                $attributes['strength']++;
            } elseif ($rand === 2 && $attributes['accuracy'] < 20) {
                $attributes['accuracy']++;
            } elseif ($rand === 3 && $attributes['magic'] < 20) {
                $attributes['magic']++;
            }
        }

        return $attributes;
    }
}
