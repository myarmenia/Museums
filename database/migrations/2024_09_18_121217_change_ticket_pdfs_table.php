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
        Schema::table('ticket_pdfs', function (Blueprint $table) {

            $table->unsignedBigInteger('purchased_id')->nullable()->after('museum_id');
            $table->foreign('purchased_id')->references('id')->on('purchases')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('museum_id')->nullable()->change();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
