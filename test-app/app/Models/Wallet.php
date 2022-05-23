<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $table = "wallets";
    protected $fillable = [
        'account_number',
        'account_hash',
    ];

    //Get available balances for certain wallet
    public function balances()
    {
        return $this->hasMany(WalletUserBalance::class);
    }

    //Get data currency
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'name');
    }
}
