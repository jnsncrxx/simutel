<?php

namespace App\Http\Controllers;

use App\Models\Housekeeping;
use App\Models\Room_reservations;
use App\Models\Rooms;
use App\Models\Room_types;
use Illuminate\Http\Request;

class HousekeepingController extends Controller
{
    public function view_housekeeping(){
        $housekeepings = Housekeeping::all();
        $room_types = Room_types::all();
    
        return view('admin.housekeeping.view_housekeeping', compact(['housekeepings','room_types']));
    }

    public function update_housekeeping_status(Request $request, $id)
    {
        $housekeeping = Housekeeping::find($id);
        $housekeeping->status = strtolower($request->status);
        $housekeeping->save();

        return redirect()->back()->with('message', 'Room no. '.$housekeeping->rooms->room_no.' housekeeping status updated to '.strtolower($request->status).' successfully.');
    }

    public function update_housekeeping_priority(Request $request, $id)
    {
        $housekeeping = Housekeeping::find($id);
        $housekeeping->priority = strtolower($request->priority);
        $housekeeping->save();

        return redirect()->back()->with('message', 'Room no. '.$housekeeping->rooms->room_no.' priority updated to '.strtolower($request->priority).' successfully.');
    }
}
