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
        Schema::create('ticket_united_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('valid_time_days')->default(365);
            $table->integer('min_museum_quantity');
            $table->string('percent');
            $table->string('coefficient');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_united_settings');
    }
};
