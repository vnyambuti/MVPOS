<?php

namespace App\Traits;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 *
 */
trait GeneralTrait
{


    public function exceptionHandler($th)
    {
        return response()->json(['success' => false, 'error' => $th->getMessage()]);
    }

    public function newUser($request)
    {
        $rules = [

            "firstname" => "required|unique:users",
            "lastname" => "required|unique:users",
            "phone" => ['required', 'regex:/^(?:254|0|\+254)?([0-9](?:(?:[129][0-9])|(?:0[0-8])|(4[0-1]))[0-9]{6})$/', 'Unique:users'],
            "email" => "required|unique:users",
            "password" => "",


        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' =>  $validator->errors()], 422);
        }

        $newuser = new User();

        $newuser->firstname = $request->firstname;
        $newuser->username = $request->firstname ;
        $newuser->lastname = $request->lastname;
        $newuser->phone = $request->phone;
        $newuser->email = $request->email;
        $newuser->password = Hash::make($request->password);

        try{

        $newuser->save();
        $newuser->roles()->sync($request->role,[]);
        // $accessToken = $newuser->createToken('authToken')->accessToken;
            // $validated['password'] = bcrypt($request->password);

            // $newuser = User::create($validated);
            // $accessToken = $newuser->createToken('authToken')->accessToken;
            // $newuser->reset_code = rand(100000, 999999);
            // $newuser->reset_code_expires_at = now()->addMinutes(10);


                // $newuser->save();
                // Mail::to($newuser->email)->send(new EmailVerification($newuser, $newuser->email_code));
                return $newuser;
            } catch (\Exception $th) {
                // dd($th);
                return response()->json(['success' => false, 'error' => ['error' => $th->getMessage()]], 500);
            }

    }


    public function updateUser($request,$id)
    {
        $rules = [
            "firstname" => "required",
            "lastname" => "required",
            "phone" => ['required', 'regex:/^(?:254|0|\+254)?([0-9](?:(?:[129][0-9])|(?:0[0-8])|(4[0-1]))[0-9]{6})$/'],
            "email" => "required",
            "password" => ""

        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' =>  $validator->errors()], 422);
        }

        $user = User::findorFail($id);
        $user->update(['firstname' => $request->firstname, 'lastname' => $request->lastname, 'phone' => $request->phone, 'email' => $request->email, Hash::make($request->password)]);
        $user->roles()->sync($request->role);
        return $user;
    }

}
