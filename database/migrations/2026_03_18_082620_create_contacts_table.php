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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('Name');
            $table->string('Email')->nullable();
            $table->string('Phone')->nullable();
            $table->string('Position')->nullable(); 
            $table->string('Institution')->nullable(); 
            $table->string('Category')->default('humas'); 
            $table->boolean('Favorite')->default(false);
            $table->text('Notes')->nullable();
            $table->foreignId('Created')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
