<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'category_id' => $this->faker->numberBetween(1,2),
            'price' => $this->faker->randomFloat(2, 1000, 100000),
            'description' => $this->faker->text(),
            'img' => fake()->randomElement(
                ['https://images.unsplash.com/photo-1706128999187-327ac1ef054e', 
                        'https://images.unsplash.com/photo-1512058564366-18510be2db19', 
                        'https://images.unsplash.com/photo-1619540158662-bb74607515f2']),
            'is_active' => $this->faker->boolean(),
        ];
    }
}
