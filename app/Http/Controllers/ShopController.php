<?php

namespace App\Http\Controllers;

use App\Http\Resources\ShopResource;
use App\Models\Shop;
use App\Traits\GeneralTrait;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShopController extends Controller
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
            $shops = Shop::with('user')->with('categories')->with('products')->limit(10)->OrderBy('created_at','desc')->get();
            return response()->json(['success' => true, 'data' => ['shops' => $shops]]);
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
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 'name','address','phone','email','logo','user_id'
        try {
            $rules = [
                "name" => "required|unique:shops",
                "address" => "required",
                "phone" => "required|unique:shops",
                "email" => "required|email|unique:shops",
                "logo" => "",
                "user_id" => "required"
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'error' =>  $validator->errors()], 422);
            }
            $shop = new Shop();
            $shop->name = $request->name;
            $shop->address = $request->address;
            $shop->phone = $request->phone;
            $shop->email = $request->email;
            $shop->user_id = $request->user_id;
            $shop->save();
            return response()->json(['success' => true, 'message' => 'Shop ' . $shop->name . ' Added', 'data' => ['shop' => $shop]]);
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
           $shop=Shop::where('id',$id)->with('user')->with('categories')->with('products')->with('tellers')->with('orders')->first();
           return response()->json(['success'=>true,'data'=>['shop'=>$shop]]);
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
        try {
            $rules = [
                "name" => "required",
                "address" => "required",
                "phone" => "required",
                "email" => "required|email",
                "logo" => "",
                "user_id" => "required"
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'error' =>  $validator->errors()], 422);
            }
            $shop=Shop::find($id);

            $shop->update([
              "name"=>$request->name,
              "address"=>$request->address,
              "phone"=>$request->phone,
              "email"=>$request->email,
              "logo"=>$request->logo,
              "user_id"=>$request->user_id
            ]);
            return response()->json(['success'=>true,'message'=>'shop '.$shop->name." Updated",'data'=>['shop'=>$shop->with('user')->get()]]);
        } catch (\Exception $th) {
           return $this->exceptionHandler($th);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
           $shop=Shop::findorFail($id);
           $shop->delete();
           return response()->json(['success'=>true,'message'=>'Shop '.$shop->name." deleted",'data'=>['shop'=>$shop]]);
        } catch (\Exception $th) {
          return $this->exceptionHandler($th);
        }
    }
}
