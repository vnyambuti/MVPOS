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
            $newuser= $this->newUser($request);

            return response()->json(['success'=>true,'message'=>'Account Created','data'=>['user'=>$newuser]]);
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
             $user=$this->updateUser($request,$id);

            return response()->json(['success' => true, 'message' => 'user ' . $user->username . ' Edited', 'data' => ['user' => $user]]);
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
