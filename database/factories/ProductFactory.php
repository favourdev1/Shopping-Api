<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        $this->faker->locale('en_US'); // Set the locale to English

        return [
            'name' => $this->faker->word,
            'category' => $this->faker->word,
            'description' => $this->faker->paragraph,
            'price' => $this->faker->randomFloat(2, 10, 100),
            'brand' => $this->faker->word,
            'image1' => $this->faker->imageUrl(),
            'image2' => $this->faker->imageUrl(),
            'image3' => $this->faker->imageUrl(),
            'image4' => $this->faker->imageUrl(),
            'image5' => $this->faker->imageUrl(),
            'weight' => $this->faker->randomFloat(2, 0.1, 10),
            'quantity_in_stock' => $this->faker->numberBetween(1, 100),
            'tags' => $this->faker->words(3, true),
            'refundable' => $this->faker->boolean,
        ];
    }
}
