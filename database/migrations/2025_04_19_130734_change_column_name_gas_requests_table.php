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
            $table->renameColumn('agent_assigned', 'driver_assigned');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gas_requests', function (Blueprint $table) {
            $table->renameColumn('driver_assigned', 'agent_assigned');
        });
    }
};
