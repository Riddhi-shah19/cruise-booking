<?php

namespace App\Http\Controllers\Admin;
use Exception;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cruise;
use App\Models\Room;


class CruiseController extends Controller
{
    public function index()
    {
        $cruise = Cruise::all();
        return view('admin.pages.cruise', compact('cruise'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|unique:cruise,name',
            'general_rooms' => 'required|integer|min:0',
            'luxury_rooms' => 'required|integer|min:0',
        ]);

        try{
            $cruise=Cruise::create($request->all());

            for ($i = 1; $i <= $cruise->general_rooms; $i++) {
                Room::create([
                    'cruise_id' => $cruise->id,
                    'room_number'   => 'G' . str_pad($i, 3, '0', STR_PAD_LEFT), // G001, G002, G003
                    'type'      => 'general',
                ]);
            }

            for ($i = 1; $i <= $cruise->luxury_rooms; $i++) {
                Room::create([
                    'cruise_id' => $cruise->id,
                    'room_number'   => 'L' . str_pad($i, 3, '0', STR_PAD_LEFT), // L001, L002, L003
                    'type'      => 'luxury',
                ]);
            }
        }catch(Exception $e){
            Log::error($e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return redirect()->back()->with('error', 'Something went wrong while adding the cruise.');
        }

        return redirect()->back()->with('success', 'Cruise And Rooms Added Successfully!');
    }

    public function update(Request $request, $id)
    {
        $cruise = Cruise::findOrFail($id);

        $request->validate([
            'name' => 'required|min:3|unique:cruise,name,' . $cruise->id,
            'general_rooms' => 'required|integer|min:0',
            'luxury_rooms' => 'required|integer|min:0',
        ]);

        try{
                $cruise->update($request->all());
                Room::where('cruise_id', $cruise->id)->delete();

                // Recreate updated General Rooms
                for ($i = 1; $i <= $cruise->general_rooms; $i++) {
                    Room::create([
                        'cruise_id' => $cruise->id,
                        'room_number' => 'G' . str_pad($i, 3, '0', STR_PAD_LEFT),
                        'type' => 'General',
                    ]);
                }
            
                // Recreate updated Luxury Rooms
                for ($i = 1; $i <= $cruise->luxury_rooms; $i++) {
                    Room::create([
                        'cruise_id' => $cruise->id,
                        'room_number' => 'L' . str_pad($i, 3, '0', STR_PAD_LEFT),
                        'type' => 'Luxury',
                    ]);
                }
            }catch(Exception $e){
                Log::error($e->getMessage(), [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]);
                return redirect()->back()->with('error', 'Something went wrong while updating the cruise.');

            }    

        return redirect()->back()->with('success', 'Cruise And Rooms Updated!');
    }

    public function destroy($id)
    {
        $cruise = Cruise::findOrFail($id);
        try {
            Room::where('cruise_id', $cruise->id)->delete();
            $cruise->delete();
            return redirect()->back()->with('success', 'Cruise And Rooms Deleted!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Cruise could not be deleted. It is tied to other data!');
        }
    }

    public function bulkCreate($cruiseId)
    {
        $cruise = Cruise::findOrFail($cruiseId);

        // Optional: check if rooms already exist for this cruise
        if (Room::where('cruise_id', $cruiseId)->exists()) {
            return redirect()->back()->with('error', 'Rooms already exist for this cruise!');
        }

        // Create General Rooms
        for ($i = 1; $i <= $cruise->general_seats; $i++) {
            Room::create([
                'cruise_id' => $cruiseId,
                'room_no'   => 'G' . str_pad($i, 3, '0', STR_PAD_LEFT), 
                'type'      => 'General',
            ]);
        }

        for ($i = 1; $i <= $cruise->luxury_seats; $i++) {
            Room::create([
                'cruise_id' => $cruiseId,
                'room_no'   => 'L' . str_pad($i, 3, '0', STR_PAD_LEFT), // Like L001, L002, L003
                'type'      => 'Luxury',
            ]);
        }

        return redirect()->back()->with('success', 'Rooms created successfully for ' . $cruise->name);
    }
}
