<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddDurationToOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->integer('duration')->after('name')->default(0); // Duration in days
        });

        // Calculate expiry for existing rows
        DB::table('offers')->get()->each(function ($offer) {
            DB::table('offers')
                ->where('id', $offer->id)
                ->update(['expiry' => now()->addDays($offer->duration)]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->dropColumn('duration');
        });
    }
}
