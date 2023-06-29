<?php

namespace App\Http\Controllers\Expert;

use App\Models\Appointment;
use App\Trait\Response_Trait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Illuminate\Support\Facades\Validator;

class Expert_appiontments extends Controller
{
    use Response_Trait;
    public function getAllReservations()
    {
        $expert_id = auth('expert-api')->user()->id;
        $availableReserv = Appointment::where('expert_id', $expert_id)->select('user_id', 'date_appointment', 'start_hour', 'end_hour', 'booked_appointments')->with(['user' => function ($u) {
            $u->select('id', 'name');
        }])->get();
        return    $this->get_response($availableReserv, 200, 'that all the Reservations booked and unbooked ');
    }

    public function Add_Appointments(Request $request)
    {
        $expert_id = auth('expert-api')->user()->id;

        $valid = Validator::make($request->all(), [
            'date_appointment' => 'required|date',
            'start_hour' => 'required|date_format:H:i',
            'end_hour' => 'required|date_format:H:i|after:start_hour',
            //'time'=>'required',
        ]);
        if ($valid->fails()) {
            return $this->get_response(null, 400, $valid->errors());
        }
        $appoint = Carbon::parse($request->input('date_appointment'));
        $start = Carbon::parse($request->input('start_hour'));
        $end = Carbon::parse($request->input('end_hour'));
        //  $time=$request->input('time');

        $start_exist = Appointment::where([['expert_id', $expert_id], ['date_appointment', $appoint],/*['time',$time]*/])->whereBetween('start_hour', [$start, $end])->get();
        $end_exist = Appointment::where([['expert_id', $expert_id], ['date_appointment', $appoint],/*['time',$time]*/])->whereBetween('end_hour', [$start, $end])->get();
        if (count($start_exist) || count($end_exist)) {
            return response()->json(['The appointment you entered is already added or there is a conflict with the hours of a pre-added appointment']);
        } else {
            $newappointments = Appointment::create([
                'expert_id' => $expert_id,
                'date_appointment' => Carbon::parse($request->input('date_appointment')),
                'start_hour' => Carbon::parse($request->input('start_hour')),
                'end_hour' => Carbon::parse($request->input('end_hour')),
                //   'time'=>$request->input('time'),
            ]);

            return response([
                'add Appointment' => 'The appointment has been added to your available appointments',
                'Appointment' => $newappointments
            ]);
        }
    }
}
