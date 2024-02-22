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
        Schema::create('ticket_accesses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_qr_id');
            $table->foreign('ticket_qr_id')->references('id')->on('ticket_qrs')->onUpdate('cascade');

            $table->unsignedBigInteger('museum_id');
            $table->foreign('museum_id')->references('id')->on('museums')->onUpdate('cascade');

            $table->timestamp('visited_date')->nullable();  // 8 hour
            $table->timestamp('access_period')->nullable();  //if ticket is event ticket
            $table->string('status')->default('valid');   // valid, expired, visited
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_accesses');
    }
};
