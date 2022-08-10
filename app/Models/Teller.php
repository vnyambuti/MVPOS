<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teller extends Model
{
    use HasFactory;

    protected $fillable=[
        'u_id','shop_id','status'

    ];

    public function shop()
    {
       $this->belongsTo(Shop::class);
    }

    public function user()
    {
       $this->belongsTo(User::class);
    }
}
