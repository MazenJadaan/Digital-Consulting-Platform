<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Wallet extends Model
{
    protected $fillable = ['id', 'balance'];

    public function user()
    {
        return $this->hasOne(User::class);
    }
    public function expert()
    {
        return $this->hasOne(Expert::class);
    }
}
