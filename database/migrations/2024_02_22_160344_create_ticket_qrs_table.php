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
        Schema::create('ticket_qrs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('museum_id')->nullable();
            $table->foreign('museum_id')->references('id')->on('museums')->onUpdate('cascade');

            $table->unsignedBigInteger('purchased_item_id');
            $table->foreign('purchased_item_id')->references('id')->on('purchased_items')->onUpdate('cascade');

            $table->unsignedBigInteger('item_relation_id')->nullable();
            $table->string('token')->unique();
            $table->string('path');
            $table->string('type');  // event , united, standart
            $table->enum('status',['active', 'expired', 'used', 'returned'])->default('active');
            $table->integer('price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_qrs');
    }
};
