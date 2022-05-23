<?php

namespace App\Models;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'wallet_id',
        'type_operation_id',
        'currency_id',
        'reason_id',
        'amount',
        'created_at',
    ];
}
