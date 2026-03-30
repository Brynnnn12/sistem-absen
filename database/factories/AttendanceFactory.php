<?php

namespace Database\Factories;

use App\Models\Attendance;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Attendance>
 */
class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_id' => \App\Models\Employee::factory(),
            'date' => fake()->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
            'check_in' => fake()->time('H:i:s'),
            'check_out' => fake()->optional(0.8)->time('H:i:s'), // 80% chance of having check-out
            'status' => fake()->randomElement(['present', 'late', 'absent']),
        ];
    }
}
