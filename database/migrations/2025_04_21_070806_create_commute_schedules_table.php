<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommuteSchedulesTable extends Migration
{
    public function up()
    {
        Schema::create('commute_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('route_id');
            $table->string('route_number')->nullable();
            $table->time('departure_time');
            $table->time('arrival_time');
            $table->enum('status', ['On Time', 'Delayed', 'Cancelled'])->default('On Time');
            $table->decimal('fare', 8, 2)->nullable();
            $table->timestamps();

            $table->foreign('route_id')->references('id')->on('routes')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('commute_schedules');
    }
}
