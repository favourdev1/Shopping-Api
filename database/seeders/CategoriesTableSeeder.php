<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    // public function run()
    // {
    //     $faker = Faker::create();

    //     foreach (range(1, 10) as $index) {
    //         Category::create([
    //             'category_name' => $faker->word,
    //             'description' => $faker->sentence,
    //             'slug' => $faker->slug,
    //             'category_image' => $faker->imageUrl(),
    //             'status' => $faker->randomElement(['active', 'inactive']),
    //         ]);
    //     }
    // }

    public function run()
    {
       Category::factory(5)->create();
    }
}
