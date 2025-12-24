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
            $table->string('payment_source')->after('payment_mode')->nullable();
            $table->string('transaction_id')->after('payment_source')->nullable();
            $table->string('transaction_status')->after('transaction_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('payment_source');
            $table->dropColumn('transaction_id');
            $table->dropColumn('transaction_status');
        });
    }
};
