<?php

namespace App\Http\Controllers\Expert;

use App\Models\Expert;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use App\Trait\Response_Trait;
use Illuminate\Support\Facades\Validator;

class Expert_info extends Controller
{
    use Response_Trait;
    public function show_profile_of_expert()
    {
        $expert_id = auth('expert-api')->user()->id;
        $expert = Expert::select('name', 'picture', 'email', 'password', 'address', 'bio', 'cost', 'phone_num', 'rate', 'consulting_type')->find($expert_id);
        return $this->get_response($expert, 200, 'this is all information of this profile');
    }
    /*public function edit_profile_info(){
    $expert_id=auth('expert-api')->user()->id;
$expert=Expert::select('picture','name','password','phone_num','address','cost','bio')->find($expert_id);
  return response($expert,200);
 }*/
    public function update_profile_info(Request $request)
    {
        $expert_id = auth('expert-api')->user()->id;
        $valid = Validator::make($request->all(), [
            'name' => 'required|string',
            'password' => 'required|string|confirmed|min:8',
            'password_confirmation' => 'required_with:password|same:password',
            'phone_num' => 'required|numeric',
        ]);
        if ($valid->fails()) {
            return $this->get_response(null, 400, $valid->errors());
        }
        $update = Expert::where('id', $expert_id)->update([
            'name' => $request->input('name'),
            'password' => bcrypt($request->input('password')),
            'phone_num' => $request->input('phone_num'),
        ]);

        return $this->get_response($update, 200, 'update succesfully');
    }



    public function complete_update_profile_info(Request $request)
    {
        $expert_id = auth('expert-api')->user()->id;
        $valid = Validator::make($request->all(), [
            'picture' => 'image',
            'address' => 'required|string',
            'cost' => 'required|numeric',
            'bio' => 'required|string',
        ]);
        if ($valid->fails()) {
            return $this->get_response(null, 400, $valid->errors());
        }
        $picture = time() . '.' . $request->picture->getClientOriginalExtension();
        $path = public_path('images/experts');
        $request->picture->move($path, $picture);
        $picture_name = 'images/experts/' . $picture;

        $update = Expert::where('id', $expert_id)->update([
            'picture' => $picture_name,
            'address' => $request->input('address'),
            'cost' => $request->input('cost'),
            'bio' => $request->input('bio')
        ]);

        return $this->get_response($update, 200, 'update succesfully');
    }

    public function get_wallet_info()
    {
        $wall_num = auth('expert-api')->user()->wallet_id;
        $wallet_info = Wallet::where('id', $wall_num)->select('id', 'balance')->get();
        return response($wallet_info, 200);
    }
}
