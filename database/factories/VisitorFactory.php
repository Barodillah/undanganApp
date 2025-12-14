<?php

namespace Database\Factories;

use App\Models\Visitor;
use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

class VisitorFactory extends Factory
{
    protected $model = Visitor::class;

    public function definition(): array
    {
        return [
            'event_id' => Event::factory(),
            'name' => $this->faker->name(),
            'address' => $this->faker->address(),
            'attendance_status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * State: visitor sudah hadir
     */
    public function hadir(): static
    {
        return $this->state(fn () => [
            'attendance_status' => 'hadir',
            'updated_at' => now(),
        ]);
    }
}
