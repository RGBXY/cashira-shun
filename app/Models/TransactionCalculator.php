<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionCalculator extends Model
{
    protected $fillable = [
        'expressions',
        'total',
    ];

    protected $casts = [
        'expressions' => 'array',
    ];
}
