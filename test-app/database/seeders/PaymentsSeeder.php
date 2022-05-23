<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\CurrencyPrice;
use App\Models\PaymentHistory;
use App\Models\PaymentReason;
use App\Models\PaymentTypeOperation;
use Illuminate\Database\Seeder;

class PaymentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $reasons = [
            ['name' => 'replenishment', 'created_at' => now()],
            ['name' => 'withdrawal', 'created_at' => now()],
            ['name' => 'refund', 'created_at' => now()]
        ];
        PaymentReason::insert($reasons);

        $operations = [
            ['name' => 'debit', 'created_at' => now()],
            ['name' => 'credit', 'created_at' => now()],
        ];
        PaymentTypeOperation::insert($operations);

        $payment_history = [
            [
                'wallet_id' => DatabaseSeeder::GLOBAL_SEED['wallet_id'],
                'type_operation_id' => 1,
                'reason_id' => 1,
                'currency_id' => Currency::where('key', 'RUB')->first()->id,
                'amount' => 12000,
                'created_at' => now()
            ],
            [
                'wallet_id' => DatabaseSeeder::GLOBAL_SEED['wallet_id'],
                'type_operation_id' => 1,
                'currency_id' => Currency::where('key', 'USD')->first()->id,
                'reason_id' => 1,
                'amount' => 120,
                'created_at' => now()
            ],
            [
                'wallet_id' => DatabaseSeeder::GLOBAL_SEED['wallet_id'],
                'type_operation_id' => 2,
                'currency_id' => Currency::where('key', 'USD')->first()->id,
                'reason_id' => 2,
                'amount' => 120,
                'created_at' => now()
            ],
        ];
        PaymentHistory::insert($payment_history);
    }
}
