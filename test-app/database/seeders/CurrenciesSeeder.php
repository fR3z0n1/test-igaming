<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\CurrencyPrice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class CurrenciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Currency::insert([
            'name' => "Российский рубль",
            'key' => "RUB",
            'created_at' => now()
        ]);

        $rates = json_decode(file_get_contents('https://www.cbr-xml-daily.ru/daily_json.js'));

        foreach ($rates->Valute as $rate) {
            Currency::insert(['name' => $rate->Name, 'key' => $rate->CharCode, 'created_at' => now()]);
        }

        Artisan::call('currency:update-prices');
    }
}
