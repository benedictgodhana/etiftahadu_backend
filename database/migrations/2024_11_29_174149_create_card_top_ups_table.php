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
        Schema::create('card_top_ups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('card_id')->constrained('nfc_cards')->onDelete('cascade'); // linking to NFC cards
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // linking to users
            $table->decimal('amount', 10, 2); // the amount being topped up
            $table->string('transaction_reference')->unique(); // unique transaction reference
            $table->string('status')->default('Active'); // default status
            $table->softDeletes(); // soft deletes field
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_top_ups');
    }
};
