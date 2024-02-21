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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('museum_id');
            $table->foreign('museum_id')->references('id')->on('museums')->onUpdate('cascade');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('ticket_type_id')->nullable();
            $table->foreign('ticket_type_id')->references('id')->on('ticket_types')->onUpdate('cascade');

            // $table->unsignedBigInteger('museum_branche_id')->nullable();
            // $table->foreign('museum_branche_id')->references('id')->on('museum_branches')->onUpdate('cascade');

            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onUpdate('cascade');

            $table->string('email');
            $table->string('token');
            $table->integer('quantity');
            $table->integer('total_price');
            $table->string('type');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
