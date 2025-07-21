<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    protected $table='rooms';
    protected $fillable = ['cruise_id','type','room_number'];

    public function cruise()
    {
        return $this->belongsTo(Cruise::class);
    }

}
