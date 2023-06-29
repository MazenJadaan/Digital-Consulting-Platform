<?php

namespace App\Http\Controllers\User;

use App\Models\Expert;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Trait\Response_Trait;

use function PHPUnit\Framework\assertNotNull;

class Search_and_get_experts extends Controller
{
  use Response_Trait;

  public function get_Experts_by_consulting($consult_type)
  {

    if ($consult_type == 'Medical Consulting') {
      $ex = expert::where('consulting_type', 'Medical Consulting')->select('id', 'name', 'picture', 'rate')->get();
      if (count($ex)) {
        return $this->get_response($ex, 200, 'that all the experts of this type');
      } else {
        return response()->json(['There are no experts of this type of consulting']);
      }
    } elseif ($consult_type == 'Family Consulting') {
      $ex = expert::where('consulting_type', 'Family Consulting')->select('id', 'name', 'picture', 'rate')->get();
      if (count($ex)) {
        return $this->get_response($ex, 200, 'that all the experts of this type');
      } else {
        return response()->json(['There are no experts of this type of consulting']);
      }
    } elseif ($consult_type == 'technical Consulting') {
      $ex = expert::where('consulting_type', 'technical Consulting')->select('id', 'name', 'picture', 'rate')->get();
      if (count($ex)) {
        return $this->get_response($ex, 200, 'that all the experts of this type');
      } else {
        return response()->json(['There are no experts of this type of consulting']);
      }
    } elseif ($consult_type == 'Business & Management Consulting') {
      $ex = expert::where('consulting_type', 'Business & Management Consulting')->select('id', 'name', 'picture', 'rate')->get();
      if (count($ex)) {
        return $this->get_response($ex, 200, 'that all the experts of this type');
      } else {
        return response()->json(['There are no experts of this type of consulting']);
      }
    } elseif ($consult_type == 'Psychological Consulting') {
      $ex = expert::where('consulting_type', 'Psychological Consulting')->select('id', 'name', 'picture', 'rate')->get();
      if (count($ex)) {
        return $this->get_response($ex, 200, 'that all the experts of this type');
      } else {
        return response()->json(['There are no experts of this type of consulting']);
      }
    } else {
      return $this->get_response(null, 401, 'You have entered the wrong consultation name');
    }
  }



  public function searchByExpertName($name)
  {
    $ex = expert::where('name', 'like', '%' . $name . '%')->select('id', 'name', 'picture', 'rate', 'consulting_type')->get();
    if (count($ex)) {
      return $this->get_response($ex, 200, 'done');
    } else {
      return $this->get_response(null, 401, 'not found result');
    }
  }


  public function get_all_info_expert($id)
  {
    $ex =  Expert::select('id', 'name', 'picture', 'bio', 'cost', 'address', 'phone_num', 'rate')->find($id);
    if ($ex) {
      return $this->get_response($ex, 200, 'this all information of expert');
    } else {
      return $this->get_response(null, 401, 'you entre id to unexist expert');
    }
  }
}
