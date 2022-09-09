<?php

namespace App\Models;

use Faker\Core\Color;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    protected $fillable=[
        'categories_id','name','price','count','low_stock','image','shop_id','colours_id'
    ];


    public function categories()
    {

      return $this->belongsTo(Categories::class);
    }

    public function shop()
    {
       return $this->belongsTo(Shop::class);
    }

    public function popular()
    {
      return $this->hasMany(Popular::class);
    }

    public function colours()
    {
      return $this->belongsToMany(Colours::class);
    }

}
