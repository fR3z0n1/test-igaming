<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $table = "currencies";
    protected $fillable = [
        'name',
        'key'
    ];

    public function balance()
    {
        return $this->hasMany(WalletUserBalance::class);
    }
}
