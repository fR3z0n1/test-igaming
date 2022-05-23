<?php

namespace App\Console\Commands;

use App\Models\Currency;
use App\Models\CurrencyPrice;
use Illuminate\Console\Command;

class CurrencyUpdatePrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:update-prices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update currency prices';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $rates = json_decode(file_get_contents('https://www.cbr-xml-daily.ru/daily_json.js'));
        $currencies = Currency::get();

        if ($currencies->count() == 0) {
            dd($this->name . " error command: not found currencies");
        }

        foreach ($rates->Valute as $rate) {
            $cur1 = $currencies->where('key', 'RUB')->first();
            $cur2 = $currencies->where('key', $rate->CharCode)->first();

            if (empty($cur1) || empty($cur2)) {
                dump($this->name . " error command: no found currency", $rate->CharCode);
                continue;
            }

            CurrencyPrice::updateOrCreate([
                'currency_1' => $cur1->id,
                'currency_2' => $cur2->id,
            ], [
                'currency_1' => $cur1->id,
                'currency_2' => $cur2->id,
                'price' => $rate->Value
            ]);
        }
    }
}
