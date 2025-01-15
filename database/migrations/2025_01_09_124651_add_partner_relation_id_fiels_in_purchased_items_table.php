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
              $table->unsignedBigInteger('partner_relation_id')->nullable()->after('partner_id');
              $table->string('partner_relation_sub_type')->nullable()->after('partner_relation_id');
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
