<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Route;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function index()
    {
        $routes = Route::all();
        return view('admin.pages.routes', compact('routes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'start' => 'required|string',
            'stop' => 'required|string',
        ]);

        Route::create($request->only('start', 'stop'));
        return redirect()->back()->with('success', 'Route Added!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'start' => 'required|string',
            'stop' => 'required|string',
        ]);

        $route = Route::findOrFail($id);
        $route->update($request->only('start', 'stop'));

        return redirect()->back()->with('success', 'Route Modified!');
    }

    public function destroy($id)
    {
        try {
            Route::destroy($id);
            return redirect()->back()->with('success', 'Route Deleted!');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->with('error', 'Route could not be deleted. It is tied to another data!');
        }
    }
}
