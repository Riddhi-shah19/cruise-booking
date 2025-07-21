<?php

namespace App\Http\Controllers\Passenger;
use Exception;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;
use App\Models\Passenger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PassengerAuthController extends Controller
{
    public function showSignupForm()
    {
        return view('passenger.signup');
    }

    public function register(Request $request)
    {

        $messages = [
            'password.regex' => 'Password must be at least 8 characters long and contain at least one number and one special character.',
        ];

        $request->validate([
            'name' => 'required|string|min:3',
            'phone' => 'required|unique:passenger,phone',
            'email' => 'required|email|unique:passenger,email',
            'file' => 'image|mimes:jpeg,png,jpg,gif',
            'address' => 'required|string',
            'password' => [
                        'required',
                        'string',
                        'min:8',
                        'confirmed',
                        'regex:/^(?=.*[0-9])(?=.*[\W_]).+$/'
                    ],
        ],$messages);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time().'_'.$file->getClientOriginalName();
            $filePath = $file->storeAs('passenger_profiles', $filename, 'public');
        } else {
            return back()->with('error', 'File upload failed!');
        }

        Passenger::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'address' => $request->address,
            'loc' => $filePath,
        ]);

        return redirect()->route('login')->with('success', 'Congratulations! You are now registered.');
    }

    public function showSigninForm()
{
    return view('passenger.signin');
}

public function signin(Request $request)
{
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        try{
                $passenger = Passenger::where('email', $request->email)->first();

                if (!$passenger || !Hash::check($request->password, $passenger->password)) {
                    return back()->with('error', 'Access Denied.')->withInput();
                }

                if ($passenger->status != 1) {
                    return back()->with('error', 'Account Deactivated! Contact the System Administrator!')->withInput();
                }

                Auth::guard('passenger')->login($passenger);

                // Login successful
                session()->regenerate();
                session([
                    'user_id' => $passenger->id,
                    'email' => $passenger->email,
                ]);

                return redirect()->route('passenger.dashboard')->with('success', 'Access Granted!');

            }catch(Exception $e){
                Log::error($e->getMessage(), [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]);
    
                return redirect()->back()->with('error', 'Something went wrong while Logging in.');
            }
    }
}
