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
            $table->string('name');
            $table->integer('quantity');
            $table->timestamp('deadline');
            $table->string('image')->nullable();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('model_type_id');
            $table->unsignedBigInteger('size_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('model_type_id')->references('id')->on('model_types')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('size_id')->references('id')->on('sizes')->onDelete('cascade');
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
