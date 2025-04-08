<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){

        $validatedData =  $request->validate([
            'name' => ['required','max:255'],
            'email' => ['required','email','unique:users'],
            'password' => ['required', 'confirmed']
        ]);

        $user = User::create($validatedData);

        $token = $user->createToken($request->name)->plainTextToken;

        return [
            'user' =>  $user,
            'token' => $token
        ];
    }

    public function login(Request $request){
       $request->validate([
            'email' => ['required','email','exists:users'],
            'password' => ['required']
        ]);

        $user = User::where('email',$request->email)->first();
        if(!$user || !Hash::check($request->password, $user->password)){
            return [
                'errors' => [
                    'email' => ['The provided crendentials are incorrect.']
                ]
            ];
            // return [
            //     'message' => 'The provided credentials are incorrect.'
            // ];
        }

        $token = $user->createToken($user->name)->plainTextToken;

        return [
            'user' =>  $user,
            'token' => $token
        ];

    }

    public function logout(Request $request){
        $request->user()->tokens()->delete();

        return[
            'message' => 'You are logged out'
        ];
    }
}

