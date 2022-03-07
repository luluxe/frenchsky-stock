<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHourStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hour_stats', function (Blueprint $table) {
            $table->id();

            $table->date("date");
            $table->integer("hour");

            $table->double("opening_price")->default(0);
            $table->double("closing_price")->default(0);
            $table->double("minimum_price")->default(0);
            $table->double("maximum_price")->default(0);

            $table->double("volume")->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hour_stats');
    }
}
