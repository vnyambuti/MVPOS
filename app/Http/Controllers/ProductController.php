<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
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
           $products=Products::with('categories')->OrderBy('created_at','DESC')->limit(25)->get();
           return response()->json(['success'=>true,'data'=>['data'=>$products]]);
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
     * 'c_id','name','price','count','low_stock','image','shop_id'
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      try {
        $rules = [
            "categories_id" => "required",
            "name" => "required|unique:products",
            "price" => "required",
            "count" => "required|gt:0",
            "image" => "",
            "shop_id" => "required",
            "low_stock" => ""
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' =>  $validator->errors()], 422);
        }
        $product=new Products();
        $product->categories_id=$request->categories_id;
        $product->name=$request->name;
        $product->price=$request->price;
        $product->count=$request->count;
        $product->image=$request->image;
        $product->shop_id=$request->shop_id;
        $product->low_stock=$request->low_stock;
        $product->save();
        return response()->json(['success'=>true,'message'=>'product '.$product->name." Added",'data'=>['product'=>$product->with('categories')->limit(5)->OrderBy('created_at','DESC')->get()]]);
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

          return response()->json(['success'=>true,'data'=>['product'=>Products::where('id',$id)->with('categories')->with('shop')->first()]]);
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
                "categories_id" => "required",
                "name" => "required",
                "price" => "required",
                "count" => "required|gt:0",
                "image" => "",
                "shop_id" => "required",
                "low_stock" => ""
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'error' =>  $validator->errors()], 422);
            }
            $product=Products::findorFail($id);
            $product->update([
                "categories_id" => $request->categories_id,
                "name" => $request->name,
                "price" => $request->price,
                "count" => $request->count,
                "image" => $request->image,
                "shop_id" => $request->shop_id,
                "low_stock" => $request->low_stock
            ]);
            return response()->json(['success'=>true,'message'=>$product->name ." Updated",'data'=>['product'=>Products::where('id',$id)->with('categories')->with('shop')->first()]]);
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
           $product=Products::findorFail($id);
           $product->delete();
           return response()->json(['success'=>true,'message'=>$product->name. " Deleted ",'data'=>['product'=>$product]]);
        } catch (\Exception $th) {
           return $this->exceptionHandler($th);
        }
    }
}
