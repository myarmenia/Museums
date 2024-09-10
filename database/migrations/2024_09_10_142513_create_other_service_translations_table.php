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
        Schema::create('other_service_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('other_service_id');
            $table->foreign('other_service_id')->references('id')->on('other_services')->onDelete('cascade')->onUpdate('cascade');
            $table->string('lang');
            $table->string('name');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('other_service_translations');
    }
};
