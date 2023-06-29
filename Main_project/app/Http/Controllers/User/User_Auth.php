<?php

namespace App\Http\Controllers\User;

use App\Models\Wallet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Trait\Response_Trait;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;



class User_Auth extends Controller
{
  use Response_Trait;
  public function User_register(Request $request)
  {
    $valid = Validator::make($request->all(), [
      'name' => 'required|string',
      'email' => 'email|required|string|unique:users,email',
      'password' => 'required|string|confirmed',
      'password_confirmation' => 'required_with:password|same:password|min:8'
    ]);
    if ($valid->fails()) {
      return $this->get_response(null, 400, $valid->errors());
    }


    $wallet = Wallet::Create([]);
    $user = $wallet->user()->create([
      'name' => $request->input('name'),
      'email' => $request->input('email'),
      'password' => bcrypt($request->input('password'))
    ]);
    $token = $user->createToken('myapptoken', ['user'])->accessToken;
    $response = [
      'name' => $user,
      'wallet_number' => $user->wallet_id,
      'token' => $token
    ];
    return $this->get_response($response, 201, 'Account created');
  }


  public function User_login(Request $request)
  {
    $fields = $request->validate([
      'email' => 'required',
      'password' => 'required|string'
    ]);
    //check email 
    $user = User::where('email', $fields['email'])->first();
    //check password 
    if (!$user || !Hash::check($fields['password'], $user->password)) {
      return response([
        'message' => 'this information wrong you dont have account please register'
      ], 401);
    }
    $token = $user->createToken('myapptoken', ['user'])->accessToken;
    $response = [
      'id' => $user->id,
      'name' => $user->name,
      'email' => $user->email,
      'token' => $token
    ];
    return $this->get_response($response, 201, 'login is successfully');
  }


  public function User_logout()
  {
    //   auth()->user()->token()->delete();
    auth()->guard('user-api')->user()->token()->delete();
    return response()->json('Successfully logged out');
  }
}
