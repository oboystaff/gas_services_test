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
        Schema::create('cash_retirements', function (Blueprint $table) {
            $table->id();
            $table->string('manager_id');
            $table->string('amount_collected');
            $table->string('bank_name')->nullable();
            $table->string('branch_name')->nullable();
            $table->string('amount_retired')->nullable();
            $table->string('date_retired')->nullable();
            $table->string('payment_slip')->nullable();
            $table->string('status')->default('Pending');
            $table->string('branch_id');
            $table->string('retired_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_retirements');
    }
};
