<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('escalation_logs', function (Blueprint $table) {
            $table->string('Channel')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('escalation_logs', function (Blueprint $table) {
            DB::statement("ALTER TABLE escalation_logs MODIFY COLUMN Channel ENUM('email', 'whatsapp', 'both')");
            });
    }
};
