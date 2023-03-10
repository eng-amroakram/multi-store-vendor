<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCustomMadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_custom_mades', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id')->nullable();
            $table->text('custom_made_description')->nullable();
            $table->string('description_image')->nullable();
            $table->text('fabric_options')->nullable();
            $table->string('fabric_image')->nullable();
            $table->text('embroidery_options')->nullable();
            $table->string('embroidery_image')->nullable();
            $table->text('accessories_options')->nullable();
            $table->string('accessories_image')->nullable();
            $table->string('implementation_period')->nullable();
            $table->integer('custom_made_size_id')->nullable();
            $table->string('custom_made_other_size')->nullable();

            $table->text('other_size_instructions')->nullable();
            $table->string('custom_made_other_size_image')->nullable();
            $table->text('other_size_notes')->nullable();
            $table->enum('status', config('database.public.statuses'));

            $table->string('custom_made_description_en')->nullable();
            $table->string('fabric_options_en')->nullable();
            $table->string('embroidery_options_en')->nullable();
            $table->string('accessories_options_en')->nullable();
            $table->string('implementation_period_en')->nullable();
            $table->string('other_size_instructions_en')->nullable();
            $table->string('other_size_notes_en')->nullable();
            $table->string('custom_made_other_size_en')->nullable();

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
        Schema::dropIfExists('product_custom_mades');
    }
}
