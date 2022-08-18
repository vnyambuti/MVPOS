<?php

namespace App\Http\Controllers;

use App\Mail\PasswordChanged;
use App\Mail\RestMail;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ApiAuthController extends Controller
{

    use GeneralTrait;
    public function Register(Request $request)
    {


        return $this->newUser($request);
    }

    public function login(Request $request)
    {


        $rules = [

            "email" => "required",
            "password" => "required|min:8",

        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' =>  $validator->errors(), 'status' => 422]);
        }
        try {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $user = Auth::user();
                if (!$user) {
                    return response()->json(['success' => false, 'error' => 'user not found']);
                } else {
                    // dd($user->roles);

                    $token = $user->createToken(rand(9999, 10000))->accessToken;

                    //  dd($newperm);
                    return response()->json(['success' => true, 'data' => ['user' => $user, 'token' => $token]], 200);
                }
            } else {
                return response()->json(['success' => false, 'error' => 'Invalid credentials']);
            }
        } catch (\Exception $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()]);
        }
    }

    public function reset(Request $request)
    {
        try {
            $rules = [

                "email" => "required"

            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'error' =>  $validator->errors()], 422);
            }

            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return response()->json(['success' => false, 'error' => 'user not found']);
            } else {
                $code = rand(99999, 1000000);
                $user->reset_code = $code;
                $user->save();
                //    dd($user);
                Mail::to($user->email)->send(new RestMail($user));
                return response()->json(['success' => true, 'message' => 'reset code has been mailed successfully'], 200);
            }
        } catch (\Exception $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()]);
        }
    }


    public function resetpass(Request $request)
    {
        try {
            $rules = [
                'code' => 'required',

                'password' => 'required|confirmed|min:8',

            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'error' =>  $validator->errors()]);
            }

            $user = User::where('reset_code', $request->code)->first();
            if (!$user) {
                return response()->json(['success' => false, 'error' => 'invalid code,kindly request for a new one']);
            } else {
                $user->password = Hash::make($request->password);
                $user->reset_code = '   ';
                $user->save();
                Mail::to($user->email)->send(new PasswordChanged($user));

                return response()->json(['success' => true, 'message' => 'password has been reset successfully'], 200);
            }
        } catch (\Exception $th) {
            return response()->json(['success' => false, 'error' => $th->getMessage()]);
        }
    }
}
