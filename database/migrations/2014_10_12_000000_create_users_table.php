<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            #IDs
            $table->id();
            $table->integer('store_id')->nullable();
            $table->integer('store_type_id')->nullable();
            $table->string('slug')->unique();

            #Basic Data
            $table->string('name');
            $table->string('email')->unique();
            $table->integer('country_id')->nullable();
            $table->integer('city_id')->nullable();
            $table->enum('user_type', config('database.users.types'));
            $table->string('image')->default('male.jpg');
            $table->enum('user_status', config('database.public.statuses'));
            $table->enum('gender', config('database.users.genders'));
            $table->date('birthdate')->nullable();
            $table->string('phone_number')->unique();

            $table->string('address_1')->nullable();
            $table->string('address_2')->nullable();
            $table->string('postCode')->nullable();

            $table->string('password')->nullable();

            #scoial media login
            $table->enum('social_login_provider', ['facebook', 'google', 'apple'])->nullable();
            $table->string('social_login_provider_code')->nullable();
            #verification
            $table->timestamp('email_verified_at')->nullable();

            #is deleted row
            $table->integer('is_delete')->default(0);

            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
