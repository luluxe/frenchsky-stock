<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderLimitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_limits', function (Blueprint $table) {
            $table->id();

            $table->string("stock", 20);
            $table->string("type", 5); // BUY SELL
            $table->string("owner", 36);
            $table->double("price");
            $table->double("quantity");

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
        Schema::dropIfExists('order_limits');
    }
}
