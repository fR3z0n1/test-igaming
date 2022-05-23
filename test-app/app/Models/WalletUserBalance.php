<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletUserBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'currency_id',
        'wallet_id',
        'user_id',
        'balance',
    ];

    //Get info about currency by currency_id
    public function currency(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    //Get info about wallet by wallet_id
    public function wallet(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'wallet_id');
    }

    //Get total balance for user, by wallets or by user_id
    public static function getTotalUserBalance($user_id, $wallets = []): float
    {
        if (empty($wallets)) {
            $wallets = WalletUserBalance::with(['wallet', 'currency'])->where('user_id', $user_id)
                ->get();
        }

        $currency_prices = CurrencyPrice::get();
        $balance = 0;

        foreach ($wallets as $tmp) {

            //Looking for a course in the collection
            $cur = $currency_prices->where(function ($query) use ($tmp) {
                return $query->where('currency_1', $tmp->currency_id)->orWhere('currency_2', $tmp->currency_id);
            })->first();

            if (!$cur) {
                continue;
            }

            $balance += $cur->price * $tmp->balance;
        }
        return $balance;
    }
}
