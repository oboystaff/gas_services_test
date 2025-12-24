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
        Schema::table('payments', function (Blueprint $table) {
            $table->string('invoice_no')->nullable()->change();
            $table->string('amount')->nullable()->change();
            $table->string('amount_paid')->nullable()->change();
            $table->string('outstanding')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('invoice_no')->nullable(false)->change();
            $table->string('amount')->nullable(false)->change();
            $table->string('amount_paid')->nullable(false)->change();
            $table->string('outstanding')->nullable(false)->change();
        });
    }
};
