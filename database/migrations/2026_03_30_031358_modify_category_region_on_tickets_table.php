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
        Schema::table('tickets', function (Blueprint $table) {
            $table->foreignId('CategoryId')->nullable()->after('Category')
                ->constrained('categories')->nullOnDelete();
            
            $table->foreignId('RegionId')->nullable()->after('Region')
                ->constrained('regions')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropConstrainedForeignId('CategoryId');
            $table->dropConstrainedForeignId('RegionId');
        });
    }
};
