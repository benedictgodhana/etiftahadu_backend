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
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
        $table->string('name');
        $table->string('email');
        $table->string('tel');
        $table->string('status');
        $table->string('serial_number')->unique();

        // Add user_id as a foreign key
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

        // Add deleted_at column for soft deletes
        $table->softDeletes(); // This adds the deleted_at column

        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
