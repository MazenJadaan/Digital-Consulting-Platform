<?php

namespace App\Http\Controllers\User;

use App\Models\Appointment;
use App\Models\Expert;
use App\Models\Fav_list;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallet;
use App\Trait\Response_Trait;

class User_info extends Controller
{
  use Response_Trait;
  public function get_wallet_info()
  {
    $wall_num = auth('user-api')->user()->wallet_id;
    $wallet_info = Wallet::where('id', $wall_num)->select('id', 'balance')->get();

    return response($wallet_info, 200);
  }
  public function add_balance_to_wallet(Request $request)
  {
    $value = $request->input('value');
    $wall_num = auth('user-api')->user()->wallet_id;
    $wallet_balance = Wallet::where('id', $wall_num)->value('balance');
    $wallet_balance = $wallet_balance + $value;

    Wallet::where('id', $wall_num)->update(['balance' => $wallet_balance]);

    return response()->json(['Balance has been added to your wallet successfully']);
  }
  public function get_fav_list_of_user()
  {
    $user_id = auth('user-api')->user()->id;
    $list = Fav_list::where('user_id', $user_id)->select('expert_id')->with(['expert' => function ($q) {
      $q->select('id', 'name', 'picture');
    }])->get();

    return $this->get_response($list, 200, 'this is the favorite list');
  }
}
