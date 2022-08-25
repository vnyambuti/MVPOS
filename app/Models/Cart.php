<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    public $items = null;
    public $totalqty = 0;
    public $totalprice = 0;

    public function __constructor($oldcart)
    {
        if ($oldcart) {
            $this->items = $oldcart->items;
            $this->totalqty = $oldcart->totalqty;
            $this->totalprice = $oldcart->totalprice;
        }
    }

    public function add($item, $id)
    {
        $storeditem = ['qty' => 0, 'price' => $item->price, 'item' => $item];
        if ($this->items) {
            if (array_key_exists($id, $this->items)) {
                $storeditem = $this->items[$id];
            }
        }
        $storeditem['qty']++;
        $storeditem['price'] = $item->price * $storeditem['qty'];
        $this->items[$id] = $storeditem;
        $this->totalqty++;
        $this->totalprice += $item->price;
    }
    public function removeItem($id)
    {
        $this->items[$id]['qty']--;
        $this->items[$id]['price'] -= $this->items[$id]['item']['price'];
        $this->totalprice -= $this->items[$id]['item']['price'];
        $this->totalqty--;
        //check if qty of collection is 0 or less
        if ($this->items[$id]['qty'] <= 0) {
            unset($this->items[$id]);
        }
    }
    public function removeAllItem($id)
    {
        $this->totalqty -= $this->items[$id]['qty'];
        $this->totalprice -= $this->items[$id]['price'];
        unset($this->items[$id]);
    }
}
