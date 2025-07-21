<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController 
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $admin = DB::table('users')
                    ->where('email', $request->email)
                    ->first();

        if ($admin && Hash::check($request->password, $admin->password)) {
            Session::put('admin', $admin->id);
            Session::put('category', 'super');

            return redirect('admin/dashboard')->with('success', 'Access Granted!');
        }

        return back()->with('error', 'Access Denied.');
    }
}
