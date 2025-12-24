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
        Schema::create('invoice_notes', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->nullable();
            $table->string('customer_id');
            $table->string('note_type');
            $table->string('amount');
            $table->text('reason')->nullable();
            $table->string('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_notes');
    }
};
