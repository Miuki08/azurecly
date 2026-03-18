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
        Schema::create('escalation_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('TicketId')->constrained('tickets')->cascadeOnDelete();
            $table->foreignId('ContactId')->nullable()->constrained('contacts');
            $table->enum('Channel', ['email', 'whatsapp', 'both']);
            $table->text('Message');
            $table->string('Recipient');
            $table->enum('Status', ['pending', 'sent', 'failed'])->default('pending');
            $table->text('Response')->nullable(); 
            $table->timestamp('SentDate')->nullable();
            $table->foreignId('Escalated')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('escalation_logs');
    }
};
