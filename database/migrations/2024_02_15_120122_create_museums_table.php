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
        Schema::create('museums', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->on('users')->onUpdate('cascade');
            $table->integer('museum_geographical_location_id');
            $table->string('email')->nullable();
            $table->string('account_number');
            // $table->string('working_hours');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('museums');
    }
};
