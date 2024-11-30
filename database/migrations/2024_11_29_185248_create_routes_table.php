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
        Schema::create('routes', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('from'); // Starting point of the route
            $table->string('to'); // Destination
            $table->decimal('fare', 8, 2); // Fare amount
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Reference to users table
            $table->softDeletes(); // For soft deleting records
            $table->timestamps(); // Created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('routes');
    }
};
