<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Define the number of dummy products you want to create
        $numberOfProducts = 10;

        // Create dummy products
        \App\Models\Product::factory($numberOfProducts)->create();
    }
}
