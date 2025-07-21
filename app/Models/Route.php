<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;
    protected $table='route';
    protected $fillable = ['start','stop'];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
