<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
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
           $categories=Categories::with('shop')->with('products')->limit(20)->OrderBy('created_at','desc')->get();
           return response()->json(['success'=>true,'data'=>['categories'=>$categories]]);
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
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $rules = [
                "name" => "required",
                "image" => "",
                "shop_id" => "required"
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'error' =>  $validator->errors()], 422);
            }
            $category=new Categories();
            $category->name=$request->name;
            $category->image=$request->image;
            $category->shop_id=$request->shop_id;
            $category->save();
            return response()->json(['success'=>true,'mesasge'=>"category ".$category->name." Added",'data'=>['category'=>$category]]);
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
            $category=Categories::where('id',$id)->with([
                'shop',
                'shop.products'
            ])->first();
            return response()->json(['success'=>true,'data'=>['category'=>$category]]);
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
            "image" => "",
            "shop_id" => "required"
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' =>  $validator->errors()], 422);
        }
        $category=Categories::findorfail($id);
        $category->update(
            [
              'name'=>$request->name,
              'image'=>$request->image,
              'shop_id'=>$request->shop_id
            ]
            );
            return response()->json(['success'=>true,'message'=>'category '.$category->name.' Updated','data'=>['category'=>$category]]);
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
            $category=Categories::findorFail($id);
            $category->delete();
            return response()->json(['success'=>true,'message'=>'category '.$category->name.' Deleted','data'=>['category'=>$category]]);
        } catch (\Exception $th) {
           return $this->exceptionHandler($th);
        }
    }
}
