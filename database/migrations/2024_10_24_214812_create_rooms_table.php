<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {

            $table->id();

            $table->string('name_ar');
            $table->string('name_en');

            $table->text('description_ar');
            $table->text('description_en');

            $table->integer('space');
            $table->integer('allowed_persons');
            $table->integer('availability');

            $table->boolean('view')->default(false);
            $table->boolean('bathroom')->default(false);
            $table->boolean('kitchen')->default(false);
            $table->boolean('tv')->default(false);
            $table->boolean('air_condition')->default(false);
            $table->boolean('wifi')->default(false);
            $table->boolean('smoke')->default(false);
            $table->boolean('disabled')->default(false);

            $table->integer('king_bed')->default(0);
            $table->integer('single_bed')->default(0);
            $table->integer('sofa_bed')->default(0);

            $table->text('bathroom_details_ar')->nullable();
            $table->text('bathroom_details_en')->nullable();

            $table->text('kitchen_details_ar')->nullable();
            $table->text('kitchen_details_en')->nullable();

            $table->text('preparations_ar')->nullable();
            $table->text('preparations_en')->nullable();

            $table->text('media_tech_ar')->nullable();
            $table->text('media_tech_en')->nullable();

            $table->string('image');
            $table->text('alt_images');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
