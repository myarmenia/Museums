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
        Schema::create('turnstiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('museum_id');
            $table->foreign('museum_id')->references('id')->on('museums')->onUpdate('cascade')->onDelete('cascade');
            $table->string('mac')->unique();
            $table->string('local_ip')->nullable();
            // $table->string('name')->unique();
            // $table->string('password');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('turnstiles');
    }
};
