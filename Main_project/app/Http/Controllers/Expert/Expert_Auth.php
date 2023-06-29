<?php

namespace App\Http\Controllers\Expert;


use App\Models\Wallet;
use App\Models\Expert;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Trait\Response_Trait;



use Illuminate\Support\Facades\Auth;

class Expert_Auth extends Controller
{
  use Response_Trait;

  public function Expert_register(Request $request)
  {
    $valid = Validator::make($request->all(), [
      'name' => 'required|string',
      'email' => 'email|required|string|unique:experts,email',
      'phone_num' => 'required|numeric',
      'password' => 'required|string|confirmed|min:8',
      'password_confirmation' => 'required_with:password|same:password',
    ]);
    if ($valid->fails()) {
      return $this->get_response(null, 400, $valid->errors());
    }

    $wallet = Wallet::Create([]);
    $expert = $wallet->expert()->create([
      'name' => $request->input('name'),
      'email' => $request->input('email'),
      'password' => bcrypt($request->input('password')),
      'phone_num' => $request->input('phone_num'),
    ]);
    $token = $expert->createToken('myapptoken', ['expert'])->accessToken;
    $response = [
      'name' => $expert,
      'wallet_number' => $expert->wallet_id,
      'token' => $token
    ];
    return $this->get_response($response, 201, 'Account created');
  }




  public function Complet_expert_info(Request $request)
  {
    $valid = Validator::make($request->all(), [
      'consulting_type' => 'required|string',
      'bio' => 'required|string',
      'cost' => 'required|numeric',
      'address' => 'required|string',
      'picture' => 'unique:experts,picture|image'
    ]);
    if ($valid->fails()) {
      return $this->get_response(null, 400, $valid->errors());
    }

    ##save image in folder by use save_image method that exist in save_image trait##

    $picture = time() . '.' . $request->picture->getClientOriginalExtension();
    $path = public_path('images/experts');
    $request->picture->move($path, $picture);
    $picture_name = 'images/experts/' . $picture;

    $ex = Auth::guard('expert-api')->user();

    $expert = Expert::where('id', $ex->id)->update([
      'consulting_type' => $request->input('consulting_type'),
      'bio' => $request->input('bio'),
      'cost' => $request->input('cost'),
      'address' => $request->input('address'),
      'picture' => $picture_name
    ]);

    $expert = Expert::where('id', $ex->id)->get();

    return $this->get_response($expert, 201, 'Information added successfully');
  }


  public function Expert_login(Request $request)
  {
    $fields = $request->validate([
      'email' => 'required|string|email',
      'password' => 'required|string'
    ]);


    //check email 
    $expert = Expert::where('email', $fields['email'])->first();
    //check password 
    if (!$expert || !Hash::check($fields['password'], $expert->password)) {
      return response([
        'message' => 'this information wrong you dont have account please register'
      ], 401);
    }
    $token = $expert->createToken('myapptoken', ['expert'])->accessToken;
    $response = [
      'id' => $expert->id,
      'name' => $expert->name,
      'email' => $expert->email,
      'token' => $token
    ];
    return $this->get_response($response, 201, 'login is successfully');
  }



  public function Expert_logout()
  {
    //auth()->user()->token()->delete();

    auth()->guard('expert-api')->user()->token()->delete();
    return response()->json('Successfully logged out');
  }
}

   
   /* public function User_register(){
        
    }
   // public function User_register(){
        
    }*/
