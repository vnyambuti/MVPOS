<?php

namespace App\Http\Controllers;

use App\Models\Teller;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\Types\This;

class TellerController extends Controller
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
           $tellers=Teller::with('shop')->with('user')->limit(25)->OrderBy('created_at','DESC')->get();
           return response()->json(['success'=>true,'data'=>['teller'=>$tellers]]);
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
     * 'u_id','shop_id','status'
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {

            $rules = [

                "shop_id" => "required",
                "status" => "",

            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'error' =>  $validator->errors()], 422);
            }
            $user=$this->newUser($request);

            $teller=new Teller();
            $teller->user_id=$user->id;
            $teller->shop_id=$request->shop_id;
            $teller->status=$request->status;
            $teller->save();
            return response()->json(['success'=>true,'message'=>'Teller '.$user->username. ' Created','data'=>['teller'=>$teller]]);

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
           $teller=Teller::where('id',$id)->with('user')->with('shop')->first();
           return response()->json(['success'=>true,'data'=>['teller'=>$teller]]);
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

                "shop_id" => "required",
                "status" => "",

            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'error' =>  $validator->errors()], 422);
            }
           ;
            $teller=Teller::findorFail($id);
            $user=$this->updateUser($request,$teller->user_id);
            $teller->update(
                [
                    'user_id'=>$user->id,
                   'shop_id'=>$request->shop_id,
                   'status'=>$request->status
                ]
                );
            return response()->json(['success'=>true,'message'=>'Teller ' .$user->username. " updated",'data'=>['teller'=>$teller]]);
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
            $teller=Teller::where('id',$id)->with('user')->with('shop')->first();
            $teller->user->delete();
            $teller->delete();
            return response()->json(['success'=>true,'message'=>'teller '.$teller->user->username." Deleted",'data'=>['teller'=>$teller]]);
        } catch (\Exception $th) {
            return $this->exceptionHandler($th);
        }
    }
}
