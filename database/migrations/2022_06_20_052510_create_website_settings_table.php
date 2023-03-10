<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebsiteSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('website_settings', function (Blueprint $table) {
            $table->id();
            $table->enum('default_language', ['en', 'ar']);
            $table->integer('default_currency');
            $table->integer('default_package');
            $table->integer('package_period');
            $table->integer('default_products_num');
            $table->integer('default_services_num');
            $table->integer('default_orders_num');
            $table->integer('default_customers_num');
            $table->integer('default_copons_num');
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
        Schema::dropIfExists('website_settings');
    }
}
