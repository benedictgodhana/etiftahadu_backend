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
        Schema::table('card_top_ups', function (Blueprint $table) {
            $table->unsignedBigInteger('offer_id')->nullable()->after('id'); // Add the offer_id field
            $table->foreign('offer_id')->references('id')->on('offers')->onDelete('set null'); // Add foreign key constraint
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('card_top_ups', function (Blueprint $table) {
            $table->dropForeign(['offer_id']); // Drop foreign key constraint
            $table->dropColumn('offer_id');   // Drop the offer_id column
        });
    }
};
