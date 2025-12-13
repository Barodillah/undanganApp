<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => 1,
            'title' => $this->faker->sentence(3),
            'slug' => $this->faker->slug,
            'type' => 'seminar',
            'description' => $this->faker->paragraph,
            'event_date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'status' => 'published',
        ];
    }

}
