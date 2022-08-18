<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //   'name','address','phone','email','logo','user_id'
        return [
            'id'=>$this->id,
            'username'=>$this->name,
            'firstname'=>$this->address,
            'lastname'=>$this->phone,
            'phone'=>$this->email,
            'email'=>$this->logo,
            'user'=>$this->user

        ];
    }
}
