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
        Schema::create('ticket_transactions', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->unsignedBigInteger('route_id'); // Foreign key to routes table
            $table->unsignedBigInteger('card_id');  // Foreign key to cards table
            $table->string('ticket_number')->unique(); // Unique ticket number
            $table->decimal('amount', 10, 2)->nullable(); // Optional amount field
            $table->timestamps(); // Laravel's created_at and updated_at fields

            // Foreign key constraints
            $table->foreign('route_id')->references('id')->on('routes')->onDelete('cascade');
            $table->foreign('card_id')->references('id')->on('nfc_cards')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_transactions');
    }
};
