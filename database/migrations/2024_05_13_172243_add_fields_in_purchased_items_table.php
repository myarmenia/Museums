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
        Schema::table('purchased_items', function (Blueprint $table) {
          $table->integer('returned_quantity')->defoult(0)->after('total_price');
          $table->integer('returned_total_price')->defoult(0)->after('returned_quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchased_items', function (Blueprint $table) {
            //
        });
    }
};
