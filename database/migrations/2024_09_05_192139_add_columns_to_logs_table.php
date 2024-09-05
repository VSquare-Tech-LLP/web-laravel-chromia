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
        Schema::table('logs', function (Blueprint $table) {
            $table->string('result_status')->nullable();
            $table->string('result_by')->nullable();
            $table->string('plan',500)->nullable();
            $table->timestamp('purchase_date')->nullable();
            $table->string('app_ver')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logs', function (Blueprint $table) {
            $table->dropColumn('result_status');
            $table->dropColumn('result_by');
            $table->dropColumn('plan');
            $table->dropColumn('purchase_date');
            $table->dropColumn('app_ver');
        });
    }
};
