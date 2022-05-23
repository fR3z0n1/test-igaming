<?php

use App\Models\Currency;
use Database\Seeders\DatabaseSeeder;
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
        if (!Schema::hasTable('payment_histories')) {
            Schema::create('payment_histories', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('wallet_id')->unsigned();
                $table->bigInteger('currency_id')->unsigned();
                $table->bigInteger('type_operation_id')->unsigned();
                $table->bigInteger('reason_id')->unsigned();
                $table->bigInteger('amount');
                $table->timestamp('created_at');
            });

            Schema::table('payment_histories', function (Blueprint $table) {
                $table->foreign('wallet_id')->references('id')->on('wallets');
                $table->foreign('currency_id')->references('id')->on('currencies');
                $table->foreign('reason_id')->references('id')->on('payment_reasons');
                $table->foreign('type_operation_id')->references('id')->on('payment_operations');
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
        Schema::dropIfExists('payment_histories');
    }
};
