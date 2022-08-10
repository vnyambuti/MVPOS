<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;


    protected $fillable=[
         'type'
    ];

    public function transaction()
    {
       $this->belongsTo(Transaction::class);
    }

    public function order()
    {
        $this->belongsTo(Order::class);
    }
}
