<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Models\Appointment;


class Expert extends Authenticatable
{

    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'address',
        'bio',
        'cost',
        'picture',
        'phone_num',
        'rate',
        'wallet_id',
        'consulting_type'
    ];

    protected $hidden = ['password', 'remember_token'];
    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
    public function rating()
    {
        return $this->hasMany(Rating::class);
    }
    public function appointment()
    {
        return $this->hasMany(Appointments::class);
    }
    public function fav_list()
    {
        return $this->hasMany(Fav_list::class);
    }
}
