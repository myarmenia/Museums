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
        Schema::create('qr_black_lists', function (Blueprint $table) {
            $table->id();
            $table->string('mac');
            $table->foreign('mac')->references('mac')->on('turnstiles')->onUpdate('cascade')->onDelete('cascade');
            $table->string('qr');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_black_lists');
    }
};
