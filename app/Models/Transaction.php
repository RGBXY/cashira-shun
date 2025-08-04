<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'cash',
        'total',
        'change',
    ];

    public function details(){
        return $this->hasMany(TransactionDetail::class);
    }
}
