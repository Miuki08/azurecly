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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('Title');
            $table->text('Description');
            $table->enum('Sentiment', ['positive', 'neutral', 'negative'])->default('neutral');
            $table->string('Actor')->nullable();
            $table->string('Category');
            $table->enum('Priority', ['high', 'medium', 'low'])->default('medium');
            $table->string('Tag')->nullable();
            $table->string('Region')->nullable(); 
            $table->string('Location')->nullable(); 
            $table->string('Latitude')->nullable(); 
            $table->string('Longitude')->nullable();
            $table->integer('ViewCount')->default(0);
            $table->timestamp('PublishedDate')->nullable();
            $table->timestamp('EscalatedDate')->nullable();
            $table->foreignId('Created')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
