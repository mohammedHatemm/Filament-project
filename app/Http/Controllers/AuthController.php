<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;




use Illuminate\Http\Request;

class AuthController extends Controller
{
    //
    public function register(Request $request)

    {
   $field= $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|confirmed|string|min:6',

    ]);
    $user=User::create($field);
    $token = $user->createToken($request->name);
        return [
            'user' => $user,
            'token' =>$token->plainTextToken
        ];
    }
    public function logIn(Request $request)
    {
         $request->validate([
            'email' => 'required|string|email|max:255|exists:users',
            'password' => 'required|string|min:6',

        ]);
        $user=User::where("email" ,$request->email)->first();
        if(!$user||!Hash::check($request->password ,$user->password)){
            return response([
               'message'=>'Invalid Credentials'
            ],401);
        }
        $token = $user->createToken($user->name);
        return [
            'user' => $user,
            'token' =>$token->plainTextToken
        ];

    }
    public function logOut(Request $request)
    {
        $request->user()->tokens()->delete();
        return [
            'message'=>'Logged out'
        ];
        }


}
