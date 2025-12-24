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
        Schema::create('gas_requests', function (Blueprint $table) {
            $table->id();
            $table->string('customer_id');
            $table->string('kg');
            $table->string('amount');
            $table->string('status')->default('Pending');
            $table->string('agent_assigned')->nullable();
            $table->string('assigned_by')->nullable();
            $table->string('branch_id');
            $table->string('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gas_requests');
    }
};
