<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use League\Event\GeneratorTrait;

class UserController extends Controller
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
            $users = UserResource::collection(User::all());
            return response()->json(['success' => true, 'data' => ['users' => $users]]);
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
        try {
            return  $this->newUser($request);
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

          return response()->json(['success'=>true,'data'=>['user'=>new UserResource(User::findorFail($id))]]);
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
                "firstname" => "required",
                "lastname" => "required",
                "phone" => ['required', 'regex:/^(?:254|0|\+254)?([0-9](?:(?:[129][0-9])|(?:0[0-8])|(4[0-1]))[0-9]{6})$/'],
                "email" => "required",
                "password" => "required|min:8"

            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'error' =>  $validator->errors()], 422);
            }

            $user = User::findorFail($id);
            $user->update(['firstname' => $request->firstname, 'lastname' => $request->lastname, 'phone' => $request->phone, 'email' => $request->email, Hash::make($request->password)]);
            $user->roles()->sync($request->role);
            return response()->json(['success' => true, 'message' => 'user ' . $user->username . 'Edited', 'data' => ['user' => $user]]);
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
           $user=User::findorFail($id);
           $user->delete();
           return response()->json(['success'=>true,'message'=>'user '.$user->username. ' deleted','data'=>['user'=>$user]]);
        } catch (\Exception $th) {
            return $this->exceptionHandler($th);
        }
    }
}
