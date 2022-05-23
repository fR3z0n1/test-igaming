<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('wallet_users')) {
            Schema::create('wallet_users', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('wallet_id')->unsigned();
                $table->bigInteger('user_id')->unsigned();
                $table->timestamps();
            });

            Schema::table('wallet_users', function (Blueprint $table) {
                $table->foreign('user_id')->references('id')->on('users');
                $table->foreign('wallet_id')->references('id')->on('wallets');
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
        if (!Schema::hasTable('wallet_users')) {
            Schema::table('wallet_users', function (Blueprint $table) {
                $table->dropForeign('user_id');
                $table->dropForeign('wallet_id');
            });
        }
        Schema::dropIfExists('wallet_users');
    }
};
