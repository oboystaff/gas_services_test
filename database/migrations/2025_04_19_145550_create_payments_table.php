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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no');
            $table->string('customer_id');
            $table->string('amount');
            $table->string('amount_paid');
            $table->string('outstanding');
            $table->string('payment_mode');
            $table->string('cheque_no')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('branch_id');
            $table->string('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
