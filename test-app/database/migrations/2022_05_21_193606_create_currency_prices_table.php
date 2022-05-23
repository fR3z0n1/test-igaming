<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('currency_prices')) {
            Schema::create('currency_prices', function (Blueprint $table) {
                $table->mediumIncrements('id');
                $table->bigInteger('currency_1')->unsigned();
                $table->bigInteger('currency_2')->unsigned();
                $table->decimal('price')->default(0);
                $table->timestamps();
            });

            Schema::table('currency_prices', function (Blueprint $table) {
                $table->foreign('currency_1')->references('id')->on('currencies');
                $table->foreign('currency_2')->references('id')->on('currencies');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currency_prices');
    }
};
