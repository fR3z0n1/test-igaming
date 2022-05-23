<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Wallet;
use App\Models\WalletUser;
use App\Models\WalletUserBalance;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class WalletsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $wallets = [
            ['account_number' => mt_rand(100000000000, 999999999999), 'account_hash' => Str::uuid()],
            ['account_number' => mt_rand(100000000000, 999999999999), 'account_hash' => Str::uuid()],
            ['account_number' => mt_rand(100000000000, 999999999999), 'account_hash' => Str::uuid()],
        ];
        foreach ($wallets as $wallet) {
            Wallet::create($wallet);
        }

        $wallet_users = [
            ['wallet_id' => DatabaseSeeder::GLOBAL_SEED['wallet_id'], 'user_id' => DatabaseSeeder::GLOBAL_SEED['user_id']]
        ];
        foreach ($wallet_users as $wallet_user) {
            WalletUser::create($wallet_user);
        }

        $data = [
            ['currency_id' => Currency::where('key', 'RUB')->first()->id, 'wallet_id' => DatabaseSeeder::GLOBAL_SEED['wallet_id'], 'user_id' => DatabaseSeeder::GLOBAL_SEED['user_id'], 'balance' => 1500, 'created_at' => now(), 'updated_at' => now()],
            ['currency_id' => Currency::where('key', 'USD')->first()->id, 'wallet_id' => DatabaseSeeder::GLOBAL_SEED['wallet_id'], 'user_id' => DatabaseSeeder::GLOBAL_SEED['user_id'], 'balance' => 80, 'created_at' => now(), 'updated_at' => now()],
        ];
        WalletUserBalance::insert($data);
    }
}
