<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $table='payment';
    protected $fillable = ['passenger_id','cruise_id','room_id','schedule_id','rooms_booked','amount','ref','date','status'];
}
