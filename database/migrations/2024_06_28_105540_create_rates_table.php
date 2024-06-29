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
        Schema::create('rates', function (Blueprint $table) {
            $table->id();
            $table->integer('site_rate');
            $table->integer('clean_rate');
            $table->integer('price_rate');
            $table->integer('service_rate');
            $table->float('total_rate')->nullable();
            $table->foreignId('user_id')->constraiend('users')->onDelete('cascade');
            $table->morphs('ratable');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rates');
    }
};
