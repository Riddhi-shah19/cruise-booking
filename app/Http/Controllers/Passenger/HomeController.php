<?php

namespace App\Http\Controllers\Passenger;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $title = 'E-Ticketing';
        $developer_name = 'Riddhi Shah'; 
        
        return view('passenger.home', compact('title', 'developer_name'));
    }
}
