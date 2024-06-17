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
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location');
            $table->foreignId('city_id')->constrained('cities')->cascadeOnDelete();
            $table->text('primary_description');
            $table->text('secondary_description');
            $table->string('cover_image');
            $table->string('logo');
            $table->integer('table_price');
            $table->string('menu');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};
