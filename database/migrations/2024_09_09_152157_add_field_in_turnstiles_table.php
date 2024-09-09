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
        Schema::table('turnstiles', function (Blueprint $table) {
          $table->integer('ticket_redemption_time')->nullable()->after('local_ip'); // add only minutes

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('turnstiles', function (Blueprint $table) {
            //
        });
    }
};
