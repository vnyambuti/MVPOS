<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable=[
        'o_id','total','p_id','receipt'
    ];

    public function payment()
    {
       $this->hasOne(Payment::class);
    }

    public function order()
    {
       $this->hasOne(Order::class);
    }
}
