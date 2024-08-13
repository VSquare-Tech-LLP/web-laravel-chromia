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
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address');
            $table->string('device_id')->nullable();
            $table->string('prompt',3000)->nullable();
            $table->json('results')->nullable();
            $table->json('settings')->nullable();
            $table->boolean('is_paid')->nullable();
            $table->string('result_id')->nullable();
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
