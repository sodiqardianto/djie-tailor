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
        Schema::create('shirt_models', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('collar_id');
            $table->unsignedBigInteger('placket_id');
            $table->timestamps();

            $table->foreign('collar_id')->references('id')->on('collars');
            $table->foreign('placket_id')->references('id')->on('plackets');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shirt_models');
    }
};
