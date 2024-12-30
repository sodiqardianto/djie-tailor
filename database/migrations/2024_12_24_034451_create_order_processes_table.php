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
        Schema::create('order_processes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_in_id');
            $table->unsignedBigInteger('user_id');
            $table->string('quantity');
            $table->unsignedBigInteger('process_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('order_in_id')->references('id')->on('order_ins')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_processes');
    }
};
