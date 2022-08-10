<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ApiAuthController extends Controller
{
    public function Register(Request $request)
    {


        $rules = [

            "username" => "",
            "firstname" => "required|unique:users",
            "lastname" => "required|unique:users",
            "phone" => ['required', 'regex:/^(?:254|0|\+254)?([0-9](?:(?:[129][0-9])|(?:0[0-8])|(4[0-1]))[0-9]{6})$/', 'Unique:users'],
            "email" => "required|unique:users",
            "password" => "required|min:8",


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
        // dd($newuser);
        $newuser->save();
        $accessToken = $newuser->createToken('authToken')->accessToken;
            $validated['password'] = bcrypt($request->password);

            $newuser = User::create($validated);
            $accessToken = $newuser->createToken('authToken')->accessToken;
            // $newuser->reset_code = rand(100000, 999999);
            // $newuser->reset_code_expires_at = now()->addMinutes(10);

            try {
                $newuser->save();
                // Mail::to($newuser->email)->send(new EmailVerification($newuser, $newuser->email_code));
                return response()->json(['success' => true, 'data' => ['user' => $newuser, 'token' => $accessToken]], 200);
            } catch (\Exception $th) {
                // dd($th);
                return response()->json(['success' => false, 'error' => ['error' => $th]], 500);
            }

    }

    // public function login(Request $request)
    // {


    //     $rules = [

    //         "email" => "required",
    //         "password" => "required|min:8",

    //     ];

    //     $validator = Validator::make($request->all(), $rules);
    //     if ($validator->fails()) {
    //         return response()->json(['success' => false, 'error' =>  $validator->errors(), 'status' => 422]);
    //     }
    //     try {
    //         if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
    //             $user = Auth::user();
    //             if (!$user) {
    //                 return response()->json(['success' => false, 'error' => 'user not found']);
    //             } else {
    //                 // dd($user->roles);

    //                 $token = $user->createToken(rand(9999, 10000))->accessToken;

    //                 //  dd($newperm);
    //                 return response()->json(['success' => true, 'data' => ['user' => $user, 'token' => $token]], 200);
    //             }
    //         } else {
    //             return response()->json(['success' => false, 'error' => 'Invalid credentials']);
    //         }
    //     } catch (\Exception $th) {
    //         return response()->json(['success' => false, 'error' => $th]);
    //     }
    // }

    // public function reset(Request $request)
    // {
    //     try {
    //         $rules = [

    //             "email" => "required"

    //         ];

    //         $validator = Validator::make($request->all(), $rules);
    //         if ($validator->fails()) {
    //             return response()->json(['success' => false, 'error' =>  $validator->errors()], 422);
    //         }

    //         $user = User::where('email', $request->email)->first();
    //         if (!$user) {
    //             return response()->json(['success' => false, 'error' => 'user not found']);
    //         } else {
    //             $code = rand(99999, 1000000);
    //             $user->reset_code = $code;
    //             $user->save();
    //             //    dd($user);
    //             Mail::to($user->email)->send(new ResetMail($user, $user->reset_code));
    //             return response()->json(['success' => true, 'message' => 'reset code has been mailed successfully'], 200);
    //         }
    //     } catch (\Exception $th) {
    //         return response()->json(['success' => false, 'error' => $th->getMessage()]);
    //     }
    // }


    // public function resetpass(Request $request)
    // {
    //     try {
    //         $rules = [
    //             'code' => 'required',

    //             'password' => 'required|confirmed|min:8',

    //         ];

    //         $validator = Validator::make($request->all(), $rules);
    //         if ($validator->fails()) {
    //             return response()->json(['success' => false, 'error' =>  $validator->errors()]);
    //         }

    //         $user = User::where('reset_code', $request->code)->first();
    //         if (!$user) {
    //             return response()->json(['success' => false, 'error' => 'invalid code,kindly request for a new one']);
    //         } else {
    //             $user->password = Hash::make($request->password);
    //             $user->reset_code = null;
    //             $user->save();
    //             Mail::to($user->email)->send(new PasswordChanged($user));

    //             return response()->json(['success' => true, 'message' => 'password has been reset successfully'], 200);
    //         }
    //     } catch (\Exception $th) {
    //         return response()->json(['success' => false, 'error' => $th->getMessage()]);
    //     }
    // }


    // public function users(Request $request)
    // {

    //     try {
    //         $users = User::whereHas('roles', function ($q) {
    //             $q->where('slug', '!=', 'super_admin');
    //         })->with('roles')->get();
    //         return response()->json(["success"=>true,"data"=>['users'=>$users]]);
    //     } catch (\Exception $th) {
    //         return response()->json(['success' => false, 'error' => $th->getMessage()], 400);
    //     }
    // }
}
