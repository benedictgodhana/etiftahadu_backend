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
        Schema::create('card_topups', function (Blueprint $table) {
            $table->bigIncrements('id'); // Primary key
            $table->unsignedBigInteger('card_id'); // Foreign key to nfc_cards
            $table->decimal('amount', 10, 2); // Amount of top-up
            $table->unsignedBigInteger('user_id'); // User who performed the top-up
            $table->string('transaction_ref')->nullable(); // Transaction reference
            $table->enum('payment_method', ['Cash', 'Card', 'Mobile']); // Payment method
            $table->enum('status', ['Pending', 'Completed', 'Failed'])->default('Pending'); // Transaction status
            $table->timestamps(); // Created_at and Updated_at fields

            // Foreign key constraints
            $table->foreign('card_id')->references('id')->on('nfc_cards')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_topups');
    }
};
