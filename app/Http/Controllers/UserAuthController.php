<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserAuthController extends Controller
{
    public function register(Request $request){

        $registerUserData = $request->validate([
            'name'=>'required|string',
            'email'=>'required|string|email|unique:users',
            'password'=>'required|min:8',
            'role'=>'required|string'
        ]);

        $role = $registerUserData['role'];

        $RoleExists = Role::whereRaw('LOWER(role_name) = ?', $role)->exists();

        if (!$RoleExists) {
            $role = Role::create(['role_name' => $role]);
            $roleId = $role->id;
        } else {
            $roleId = Role::whereRaw('LOWER(role_name) = ?', $role)->first()->id;
        }

        $user = User::create([
            'name' => $registerUserData['name'],
            'email' => $registerUserData['email'],
            'password' => Hash::make($registerUserData['password']),
            'role_id' => $roleId
        ]);

        $randomString = Str::random(10).$user->id;

        Wallet::create([
            'user_id' => $user->id,
            'serial' => strtoupper($randomString),
            'amount' => 0.00,
        ]);

        return response()->json([
            'message' => 'User Created & Wallet created successfully',
        ]);
    }

    public function login(Request $request){
        $loginUserData = $request->validate([
            'email'=>'required|string|email',
            'password'=>'required|min:8'
        ]);
        $user = User::where('email',$loginUserData['email'])->first();
        if(!$user || !Hash::check($loginUserData['password'],$user->password)){
            return response()->json([
                'message' => 'Invalid Credentials'
            ],401);
        }
        $token = $user->createToken($user->name.'-AuthToken')->plainTextToken;
        return response()->json([
            'access_token' => $token,
        ]);
    }

    public function logout(){
        auth()->user()->tokens()->delete();
        //$request->user()->currentAccessToken()->delete();

        return response()->json([
            "message"=>"logged out"
        ]);
    }
}
