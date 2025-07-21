<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    protected $table='schedule';
    protected $fillable = ['cruise_id','route_id','date','time','luxury_fee', 'general_fee'];

    public function cruise()
    {
        return $this->belongsTo(Cruise::class);
    }

    public function route()
    {
        return $this->belongsTo(Route::class);
    }
}
