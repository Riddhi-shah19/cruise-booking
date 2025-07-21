<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Passenger;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $passengers = Passenger::orderBy('id', 'desc')->get();
        return view('admin.pages.users', compact('passengers'));
    }
    
    public function toggleStatus($id, $status)
    {
        $passenger = Passenger::findOrFail($id);
        $passenger->status = $status;
        $passenger->save();

        return redirect()->route('admin.users')
                         ->with('success', 'Action completed!');
    }
}
