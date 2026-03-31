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
       Schema::create('ticket_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('TicketId');
            $table->foreign('TicketId')->references('Id')->on('tickets')->onDelete('cascade');
            $table->string('Path');
            $table->string('Description')->nullable();
            $table->unsignedInteger('Order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_images');
    }
};
