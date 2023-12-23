<?php

// database/migrations/xxxx_xx_xx_create_products_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->unsignedBigInteger('category_id');
            $table->text('description')->nullable();
            $table->decimal('regular_price', 8, 2);
            $table->string('brand')->nullable();
            $table->string('product_img1')->nullable();
            $table->string('product_img2')->nullable();
            $table->string('product_img3')->nullable();
            $table->string('product_img4')->nullable();
            $table->string('product_img5')->nullable();
            $table->decimal('weight', 8, 2)->nullable();
            $table->integer('quantity_in_stock');
            $table->text('tags')->nullable()->nullable();
            $table->boolean('refundable')->nullable();
            $table->enum('status', ['active', 'inactive']);
            $table->decimal('sales_price', 8, 2);
            $table->string('meta_title');
            $table->text('meta_description');
            

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}