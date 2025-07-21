<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cruise extends Model
{
    use HasFactory;
    protected $table='cruise';
    protected $fillable = ['name','general_rooms','luxury_rooms'];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
