<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
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
        //
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
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
            Session::forget('cart');

            // if cart is empty then this the first product
            if (!$cart) {

                $cart = [
                    $id => [
                        "name" => $product->name,
                        "quantity" => $request->quantity,
                        "price" => $product->price,
                        "photo" => $product->image
                    ]
                ];
                $request->session()->put('cart', $cart);
                $newstock = (int)$product->count - $request->quantity;
                $product->update([
                    'count' => $newstock,
                ]);

                return response()->json(['success' => true, 'message' => 'Product added to cart successfully!', 'data' => ['sale' => $cart]]);
            }
            // if cart not empty then check if this product exist then increment quantity
            if (isset($cart[$id])) {
                $cart[$id]['quantity'] =$cart[$id]['quantity'] + $request->quantity;
                $request->session()->put('cart', $cart);
                $newstock = (int)$product->count - $request->quantity;
                $product->update([
                    'count' => $newstock,
                ]);

                return response()->json(['success' => true, 'message' => 'Product added to cart successfully!', 'data' => ['sale' => $cart]]);
            }
            // if item not exist in cart then add to cart with quantity = 1
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => $request->quantity,
                "price" => $product->price,
                "photo" => $product->photo
            ];
            $request->session()->put('cart', $cart);
            $newstock = (int)$product->count - $request->quantity;
            $product->update([
                'count' => $newstock,
            ]);

            return response()->json(['success' => true, 'message' => 'Product added to cart successfully!', 'data' => ['sale' => $cart]]);
        } catch (\Exception $th) {
            return $this->exceptionHandler($th);
        }
    }
}
