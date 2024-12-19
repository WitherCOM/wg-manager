<?php

namespace Database\Factories;

use App\Models\Peer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Peer>
 */
class PeerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
        ];
    }

        /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterMaking(function (Peer $peer) {
            // ...
            $peer->ipAllocate();
        });
    }
}
