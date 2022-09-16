<?php

namespace App\Http\Controllers;

use App\Models\Popular;
use App\Models\Products;
use App\Models\Shop;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
            $products = Products::with('colours')->OrderBy('created_at', 'DESC')->limit(25)->get();
            return response()->json(['success' => true, 'data' => ['data' => $products]]);
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
                "image" => "required",
                "shop_id" => "required",
                "low_stock" => "",

            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'error' =>  $validator->errors()], 422);
            }

            $product = new Products();
            $product->categories_id = $request->categories_id;
            $product->name = $request->name;
            $product->price = $request->price;
            $product->count = $request->count;
            $product->image = $request->image;
            $product->shop_id = $request->shop_id;
            $product->low_stock = $request->low_stock;
            $basename = Str::random();
            $profile = $request->file('image');
            $profilename = $basename . '.' . $profile->getClientOriginalExtension();
            $profile->move(public_path('/products'),  $profilename);
            $product->image = $request->root() . '/products/' . $profilename;
            $product->save();
            $product->colours()->sync($request->colors, []);
            return response()->json(['success' => true, 'message' => 'product ' . $product->name . " Added", 'data' => ['product' => $product]]);
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

            return response()->json(['success' => true, 'data' => ['product' => Products::where('id', $id)->with([
                'shop',
                'categories',
                'categories.products.colours',

            ])->first()]]);
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
                "low_stock" => "",

            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'error' =>  $validator->errors()], 422);
            }
            $product = Products::findorFail($id);
            $product->update([
                "categories_id" => $request->categories_id,
                "name" => $request->name,
                "price" => $request->price,
                "count" => $request->count,
                "image" => $request->image,
                "shop_id" => $request->shop_id,
                "low_stock" => $request->low_stock,
                "colour_id" => $request->colour_id

            ]);
            $product->colours()->sync($request->colors,);
            return response()->json(['success' => true, 'message' => $product->name . " Updated", 'data' => ['product' => Products::where('id', $id)->with([
                'shop',
                'categories',
                'categories.products.colours',
            ])->first()]]);
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
            $product = Products::findorFail($id);
            $product->delete();
            return response()->json(['success' => true, 'message' => $product->name . " Deleted ", 'data' => ['product' => $product]]);
        } catch (\Exception $th) {
            return $this->exceptionHandler($th);
        }
    }

    public function getPopularProducts(Request $request)
    {
        try {
           $products=Popular::where('shop_id',$request->shop_id)->with([
            'product',
            'product.colours'
           ])->OrderBy('count','DESC')->limit(4)->get();
           return response()->json(['success' => true, 'popular' => $products]);
        } catch (\Exception $th) {
           return $this->exceptionHandler($th);
        }
    }

    public function productsByShop(Request $request)
    {
        try {
            $products=Shop::where('id',$request->shop_id)->with([
             'categories',
             'categories.products.colours'
            ])->get();
            return response()->json(['success' => true, 'products' => $products]);
         } catch (\Exception $th) {
            return $this->exceptionHandler($th);
         }
    }
}
