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
        Schema::create('educational_program_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('educational_program_id');
            $table->foreign('educational_program_id')->references('id')->on('educational_programs')->onDelete('cascade')->onUpdate('cascade');
            $table->string('name');
            $table->longText('description');
            $table->string('lang')->index();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('educational_program_translations');
    }
};
