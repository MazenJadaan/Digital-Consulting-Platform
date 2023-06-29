<?php

namespace App\Http\Controllers\User;

use App\Models\Appointment;
use App\Models\Expert;
use App\Models\Fav_list;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Illuminate\Support\Facades\Auth;

class Addfavoraite_and_rate extends Controller
{
    public function Add_to_favorite($id_expert)
    {
        $id_user = auth('user-api')->user()->id;
        $fav = Fav_list::where('user_id', $id_user)->where('expert_id', $id_expert)->get();
        if (count($fav)) {
            return response()->json(['that expert exist in your favorite list already']);
        } else {
            Fav_list::Create(['user_id' => $id_user, 'expert_id' => $id_expert]);
            return response()->json(['Expert Added to Favorites ']);
        }
    }

    public function rate_expert_by_user(Request $request, $id_expert)
    {
        $value = $request->input('value');
        $id_user = auth('user-api')->user()->id;

        $Appoint = Appointment::where('user_id', $id_user)->where('expert_id', $id_expert)->get();
        if (count($Appoint)) {
            $rate = Rating::where('user_id', $id_user)->where('expert_id', $id_expert)->get();
            if (count($rate)) {
                return response()->json(['You cant rate an expert again you rate him previous ']);
            } else {
                Rating::Create(['user_id' => $id_user, 'expert_id' => $id_expert, 'rate_value' => $value]);
                $this->Calculate_Expert_Rate($id_expert);

                return response()->json(['Expert Rate Done']);
            }
        } else {
            return response()->json(['You cant rate an expert because you didnt have a previous-booking with him']);
        }
    }

    public function Calculate_Expert_Rate($id_expert)
    {
        $expert = Expert::find($id_expert);

        $arr = Rating::where('expert_id', $id_expert)->pluck('rate_value');
        $x = 0;
        foreach ($arr as $total) {
            $x = $x + $total;
        }
        $expert_rate = round($x / count($arr));
        $expert->update(['rate' => $expert_rate]);
    }
}
