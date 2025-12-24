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
        Schema::table('gas_requests', function (Blueprint $table) {
            $table->string('name')->after('customer_id')->nullable();
            $table->string('contact')->after('name')->nullable();
            $table->string('community_id')->after('assigned_by')->nullable();
            $table->string('customer_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gas_requests', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('contact');
            $table->dropColumn('community_id');
            $table->string('customer_id')->nullable(false)->change();
        });
    }
};
