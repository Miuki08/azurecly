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
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar_path')->nullable()->after('FaceDescription');
            $table->timestamp('last_login_at')->nullable()->after('avatar_path');
            $table->string('last_login_ip')->nullable()->after('last_login_at');
            $table->timestamp('last_profile_update_at')->nullable()->after('last_login_ip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'avatar_path',
                'last_login_at',
                'last_login_ip',
                'last_profile_update_at',
            ]);
        });
    }
};
