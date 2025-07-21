<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booked extends Model
{
    use HasFactory;
    protected $table='booked';
    protected $fillable = ['schedule_id','cruise_id','room_id','user_id','payment_id','user_id','code','date','rooms_booked'];

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }


}
