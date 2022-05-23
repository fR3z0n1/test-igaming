<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTypeOperation extends Model
{
    use HasFactory;

    public const OPERATION_IDS = [
        'debit' => 1,
        'credit' => 2
    ];

    protected $table = 'payment_operations';
    protected $fillable = [
        'name'
    ];
}
