<?php

namespace App\Http\Controllers\User;

use App\Models\Appointment;
use App\Models\Wallet;
use App\Models\Expert;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Trait\Response_Trait;

class User__appiontments extends Controller
{
    use Response_Trait;
    public function ShowAvaliableResearvations($expert_id)
    {
        $availableReserv = Appointment::where([['expert_id', $expert_id], ['booked_appointments', false]])->select('id', 'date_appointment', 'start_hour', 'end_hour')->get();
        return $this->get_response($availableReserv, 200, 'That All Avaliable Researvations Of This Expert ');
    }


    public function Booked_Researvation($Researvation_id)
    {
        $expert_id = Appointment::where('id', $Researvation_id)->select('expert_id')->get();
        $cost = Expert::find($expert_id)->value('cost');

        $user_id = auth('user-api')->user()->id;
        $wallet_number_user = User::find($user_id)->value('wallet_id');
        $user_balance = Wallet::where('id', $wallet_number_user)->value('balance');
        if ($user_balance < $cost) {
            return response()->json(['You do not have enough balance please recharge your wallet']);
        }
        $user_balance = $user_balance - $cost;
        Wallet::where('id', $wallet_number_user)->update(['balance' => $user_balance]);


        $wallet_number_expert = Expert::find($expert_id)->value('wallet_id');

        $expert_balance = Wallet::where('id', $wallet_number_expert)->value('balance');
        $expert_balance = $expert_balance + $cost;

        Wallet::where('id', $wallet_number_expert)->update(['balance' => $expert_balance]);



        Appointment::where('id', $Researvation_id)->update(['user_id' => $user_id, 'booked_appointments' => true]);

        return response()->json([' Researvation booked done and The cost of the consultation will be deducted from your wallet  ']);
    }
}
