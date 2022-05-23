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
        if (!Schema::hasTable('wallet_user_balances')) {
            Schema::create('wallet_user_balances', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('currency_id')->unsigned();
                $table->bigInteger('wallet_id')->unsigned();
                $table->bigInteger('user_id')->unsigned();
                $table->decimal('balance')->unsigned();
                $table->timestamps();
            });

            Schema::table('wallet_user_balances', function (Blueprint $table) {
                $table->foreign('user_id')->references('id')->on('users');
                $table->foreign('wallet_id')->references('id')->on('wallets');
                $table->foreign('currency_id')->references('id')->on('currencies');
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
        Schema::dropIfExists('wallet_user_balances');
    }
};
