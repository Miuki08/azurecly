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
        Schema::table('categories', function (Blueprint $table) {
            $table->foreignId('site_id')
                ->after('id')
                ->constrained('sites')
                ->cascadeOnDelete();

            $table->dropUnique(['Name']);
            $table->dropUnique(['Slug']);

            $table->unique(['site_id', 'Name']);
            $table->unique(['site_id', 'Slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropUnique(['site_id', 'Name']);
            $table->dropUnique(['site_id', 'Slug']);
            $table->dropConstrainedForeignId('site_id');

            $table->unique('Name');
            $table->unique('Slug');
        });
    }
};
