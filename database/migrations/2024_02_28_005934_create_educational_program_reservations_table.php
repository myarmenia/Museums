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
        Schema::create('educational_program_reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('educational_program_id')->nullable();
            $table->foreign('educational_program_id')->references('id')->on('educational_programs')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('museum_id');
            $table->foreign('museum_id')->references('id')->on('museums')->onDelete('cascade')->onUpdate('cascade');

            $table->integer('visitor_quantity')->nullable();
            $table->date('date');
            $table->time('time');
            $table->longText('description');
            $table->string('type');             // educational_program or excursion
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('educational_program_reservations');
    }
};
