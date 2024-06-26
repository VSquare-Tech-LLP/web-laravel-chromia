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
        Schema::create('swap_logs', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address');
            $table->string('device_id')->nullable();
            $table->string('swap_source')->nullable();
            $table->string('swap_source_id')->nullable();
            $table->string('swap_target')->nullable();
            $table->string('swap_result_id')->nullable();
            $table->string('swap_result')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('swap_logs');
    }
};
