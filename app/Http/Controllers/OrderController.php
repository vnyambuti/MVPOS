<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Products;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{

    use GeneralTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
         $orders=Order::OrderBy('created_at','desc')->limit(25)->get();
         return response()->json(['success'=>true,'data'=>['order'=>$orders]]);

        } catch (\Exception $th) {
           return $this->exceptionHandler($th);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * 'products','total','status','mode','shop_id','teller_id'
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $rules = [

                "shop_id" => "required",
                "mode" => "required",
                "teller_id"=>"required"


            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'error' =>  $validator->errors()], 422);
            }

           $order=Session::get('cart');
           $total=0;
           foreach ($order as $key => $value) {
            $product=Products::find($value['product_id']);
            $newstock = (int)$product->count - $value['quantity'];
                $product->update([
                    'count' => $newstock,
                ]);
             $total=$total+$value['price'];
           }
           $neworder=new Order();
           $neworder->products=serialize($order);
           $neworder->total=$total;
           $neworder->status='Paid';
           $neworder->mode=$request->mode;
           $neworder->shop_id=$request->shop_id;
           $neworder->teller_id=$request->teller_id;
           $neworder->save();
           Session::forget('cart');
           return response()->json(['success'=>true,'message'=>'Completed','data'=>['order'=>$neworder]]);
        } catch (\Exception $th) {
           return $this->exceptionHandler($th);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
          $order=Order::where('id',$id)->with('shop')->with('teller')->first();
          return response()->json(['success'=>true,'data'=>['order'=>$order]]);
        } catch (\Exception $th) {
           return $this->exceptionHandler($th);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function add(Request $request)
    {
        try {
            $rules = [

                "product_id" => "required",
                "quantity" => "required|gt:0"


            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'error' =>  $validator->errors()], 422);
            }
            $product = Products::findorFail($request->product_id);
            $id = $request->product_id;
            $cart = Session::get('cart');

            // if cart is empty then this the first product
            if (!$cart) {

                $cart = [
                    $id => [
                        "product_id"=>$id,
                        "name" => $product->name,
                        "quantity" => $request->quantity,
                        "price" => $product->price,
                        "photo" => $product->image
                    ]
                ];
                $request->session()->put('cart', $cart);
                // $newstock = (int)$product->count - $request->quantity;
                // $product->update([
                //     'count' => $newstock,
                // ]);

                return response()->json(['success' => true, 'message' => 'Product added to cart successfully!', 'data' => ['sale' => $cart]]);
            }
            // if cart not empty then check if this product exist then increment quantity
            if (isset($cart[$id])) {
                $cart[$id]['quantity'] =$cart[$id]['quantity'] + $request->quantity;
                $request->session()->put('cart', $cart);
                // $newstock = (int)$product->count - $request->quantity;
                // $product->update([
                //     'count' => $newstock,
                // ]);

                return response()->json(['success' => true, 'message' => 'Product added to cart successfully!', 'data' => ['sale' => $cart]]);
            }
            // if item not exist in cart then add to cart with quantity = 1
            $cart[$id] = [
                "product_id"=>$id,
                "name" => $product->name,
                "quantity" => $request->quantity,
                "price" => $product->price,
                "photo" => $product->photo
            ];
            $request->session()->put('cart', $cart);
            // $newstock = (int)$product->count - $request->quantity;
            // $product->update([
            //     'count' => $newstock,
            // ]);

            return response()->json(['success' => true, 'message' => 'Product added to cart successfully!', 'data' => ['sale' => $cart]]);
        } catch (\Exception $th) {
            return $this->exceptionHandler($th);
        }
    }



    public function remove(Request $request)
    {
        try {
            $rules = [

                "product_id" => "required",
                "quantity" => "required|gt:0"


            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'error' =>  $validator->errors()], 422);
            }
            $product = Products::findorFail($request->product_id);
            $id = $request->product_id;
            $cart = Session::get('cart');

            // if cart is empty then this the first product
            if (!$cart) {

                // $cart = [
                //     $id => [
                //         "name" => $product->name,
                //         "quantity" => $request->quantity,
                //         "price" => $product->price,
                //         "photo" => $product->image
                //     ]
                // ];
                // $request->session()->put('cart', $cart);
                // $newstock = (int)$product->count - $request->quantity;
                // $product->update([
                //     'count' => $newstock,
                // ]);

                return response()->json(['success' => true, 'message' => 'No Product Added Yet', 'data' => ['sale' => $cart]]);
            }
            // if cart not empty then check if this product exist then increment quantity
            if (isset($cart[$id])) {
                $cart[$id]['quantity'] =$cart[$id]['quantity'] - $request->quantity;
                $request->session()->put('cart', $cart);
                // $newstock = (int)$product->count - $request->quantity;
                // $product->update([
                //     'count' => $newstock,
                // ]);

                return response()->json(['success' => true, 'message' => 'Product removed successfully!', 'data' => ['sale' => $cart]]);
            }
            // if item not exist in cart then add to cart with quantity = 1
            // $cart[$id] = [
            //     "name" => $product->name,
            //     "quantity" => $request->quantity,
            //     "price" => $product->price,
            //     "photo" => $product->photo
            // ];
            // $request->session()->put('cart', $cart);
            // $newstock = (int)$product->count - $request->quantity;
            // $product->update([
            //     'count' => $newstock,
            // ]);

            return response()->json(['success' => true, 'message' => 'Item Not found on order', 'data' => ['sale' => $cart]]);
        } catch (\Exception $th) {
            return $this->exceptionHandler($th);
        }
    }


    public function clear(Request $request)
    {
      try {
       Session::forget('cart');
       return response()->json(['success'=>true,'message'=>'Cleard']);
      } catch (\Exception $th) {
       return $this->exceptionHandler($th);
      }
    }
}
