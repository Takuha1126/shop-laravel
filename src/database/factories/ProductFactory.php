<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'status' => 'available',
            'product_name' => $this->faker->word,
            'brand' => $this->faker->company,
            'description' => $this->faker->sentence,
            'price' => $this->faker->numberBetween(100, 10000),
            'image' => $this->faker->imageUrl(),
        ];
    }
}

