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
        Schema::create('order_ins', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('model_type_id');
            $table->integer('quantity');
            $table->string('image')->nullable();
            $table->unsignedBigInteger('customer_id');
            $table->enum('status', ['pesanan diterima', 'menunggu', 'proses menjahit', 'selesai', 'dibatalkan', 'dikembalikan'])->default('pesanan diterima');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('model_id')->references('id')->on('model_types')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_ins');
    }
};
