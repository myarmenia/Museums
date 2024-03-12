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
        Schema::create('ticket_subscription_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('museum_id');
            $table->foreign('museum_id')->references('id')->on('museums')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('price');
            $table->integer('valid_time_days')->default(365);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_subscription_settings');
    }
};
