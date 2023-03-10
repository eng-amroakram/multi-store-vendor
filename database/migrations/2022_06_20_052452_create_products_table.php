<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            #IDs
            $table->id();
            $table->integer('store_admin');
            $table->integer('store_id');
            $table->integer('store_type_id');
            $table->string('slug')->unique();

            #Basic Data
            $table->string('product_name_en');
            $table->string('product_name_ar');
            $table->text('product_description_en');
            $table->text('product_description_ar');
            $table->enum('product_type', config('database.products.types'));
            $table->integer('product_category');
            $table->string('product_serial_number')->unique();
            $table->string('product_vat');
            $table->float('product_vat_value');
            $table->float('product_price');
            $table->float('product_price_after_vat');
            $table->float('wholesale_price');
            $table->json('product_size')->nullable();
            $table->integer('in_stock')->default(1);
            $table->string('product_3d_image');
            $table->string('product_main_image', 500)->nullable();
            $table->enum('product_status', config('database.public.statuses'));


            #Affiliates Data
            $table->integer('is_affiliate')->default(0);
            $table->enum('affiliate_type', ['commission', 'ratio'])->nullable();
            $table->float('affiliate_value');

            $table->integer('is_delete')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
