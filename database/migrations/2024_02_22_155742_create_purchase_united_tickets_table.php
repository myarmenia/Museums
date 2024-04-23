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
        Schema::create('purchase_united_tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('museum_id');
            $table->foreign('museum_id')->references('id')->on('museums')->onUpdate('cascade');

            $table->unsignedBigInteger('purchased_item_id');
            $table->foreign('purchased_item_id')->references('id')->on('purchased_items')->onUpdate('cascade');
            $table->integer('quantity');
            $table->integer('total_price');
            $table->integer('price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_united_tickets');
    }
};
