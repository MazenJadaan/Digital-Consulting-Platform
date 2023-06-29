<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'id', 'user_id', 'expert_id', 'date_appointment', 'time', 'start_hour', 'end_hour', 'booked_appointments',
        /*'consultation_done'*/ 'fav_expert',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function expert()
    {
        return $this->belongsTo(Expert::class);
    }
}
