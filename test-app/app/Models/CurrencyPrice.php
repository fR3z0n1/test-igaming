<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrencyPrice extends Model
{
    use HasFactory;

    protected $table = 'currency_prices';
    protected $fillable = [
        'currency_1',
        'currency_2',
        'price',
    ];
}
