<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable=[
       'products','total','status','shop_id','teller_id'
    ];

    public function shop()
    {
       $this->belongsTo(Shop::class);
    }

    public function teller()
    {
        $this->belongsTo(Teller::class);
    }
}
