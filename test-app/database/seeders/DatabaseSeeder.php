<?php

namespace Database\Seeders;


use App\Models\PaymentReason;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    // Global variables for seeders
    const GLOBAL_SEED = [
        'wallet_id' => 1,
        'user_id' => 1,
    ];

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UsersSeeder::class,
            CurrenciesSeeder::class,
            WalletsSeeder::class,
            PaymentsSeeder::class,
        ]);
    }
}
