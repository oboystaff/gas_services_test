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
        Schema::table('cash_retirements', function (Blueprint $table) {
            $table->dropColumn('manager_id');
            $table->renameColumn('amount_collected', 'sales_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cash_retirements', function (Blueprint $table) {
            $table->string('manager_id')->nullable();
            $table->renameColumn('sales_date', 'amount_collected');
        });
    }
};
