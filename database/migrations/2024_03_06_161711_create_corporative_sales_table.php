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
        Schema::create('corporative_sales', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            //taxpayer identification number
            $table->string('tin');
            $table->string('file_path')->nullable();
            $table->string('email');
            $table->string('contract_number')->nullable();
            $table->unsignedInteger('tickets_count')->min(100);
            $table->unsignedInteger('visitors_count')->default(0);
            $table->integer('price');
            $table->string('coupon')->unique()->index();
            $table->date('ttl_at');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('corporative_sales');
    }
};
