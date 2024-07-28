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
        Schema::create('purchased_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('museum_id')->nullable();
            $table->foreign('museum_id')->references('id')->on('museums')->onUpdate('cascade');

            $table->unsignedBigInteger('item_relation_id')->nullable();

            // $table->unsignedBigInteger('product_id')->nullable();
            // $table->foreign('product_id')->references('id')->on('products')->onUpdate('cascade');

            $table->unsignedBigInteger('purchase_id')->nullable();
            $table->foreign('purchase_id')->references('id')->on('purchases')->onUpdate('cascade')->onDelete('cascade');

            // $table->unsignedBigInteger('event_config_id')->nullable();
            // $table->foreign('event_config_id')->references('id')->on('event_configs')->onUpdate('cascade');

            $table->integer('quantity');
            $table->integer('total_price');
            $table->string('type');       // product or ticket
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchased_items');
    }
};
