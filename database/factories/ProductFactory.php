<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition() : array
    {
        return [
            'code' => $this->faker->unique()->numerify('#############'), // 13 dígitos
            'url' => $this->faker->url,
            'creator' => $this->faker->name,
            'created_t' => now()->timestamp,
            'last_modified_t' => now()->timestamp,
            'product_name' => $this->faker->word,
            'quantity' => $this->faker->randomDigitNotNull() . 'g',
            'brands' => $this->faker->company,
            'categories' => $this->faker->word,
            'labels' => $this->faker->word,
            'cities' => $this->faker->city,
            'purchase_places' => $this->faker->city,
            'stores' => $this->faker->company,
            'ingredients_text' => $this->faker->sentence,
            'traces' => $this->faker->word,
            'serving_size' => $this->faker->randomDigitNotNull() . 'g',
            'serving_quantity' => $this->faker->randomFloat(2, 1, 500),
            'nutriscore_score' => $this->faker->numberBetween(-10, 40),
            'nutriscore_grade' => $this->faker->randomElement(['a', 'b', 'c', 'd', 'e']),
            'main_category' => $this->faker->word,
            'image_url' => $this->faker->imageUrl(),
            'imported_t' => now(),
            'status' => 'published',
        ];
    }
}
