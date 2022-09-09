<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;


    protected $fillable=[
      'title','slug'
    ];


    public function user()
    {
      $this->belongsToMany(User::class);
    }


    public function permissions() {
        return $this->belongsToMany(Permission::class);
     }

}
