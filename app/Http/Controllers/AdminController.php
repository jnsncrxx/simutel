<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Rooms;
use App\Models\Room_types;
use App\Models\Housekeeping;
use Illuminate\Http\Request;
use App\Models\Room_pictures;
use App\Models\Room_reservations;


class AdminController extends Controller
{
    public function get_reservations()
    {
         $reservations = Room_reservations::all();
         foreach($reservations as $reservation){
            $reservation->reserved_by = $reservation->reserved_by;
            $reservation->guests = $reservation->guests;
         }
         return $reservations;
    }
    public function get_all_rooms()
    {
        $room_types = Room_types::all();
        foreach($room_types as $room_type){
           foreach($room_type->rooms as $room){
               $room->housekeeping = $room->housekeeping;
           }
        }
        return $room_types;
    }
    public function view_rooms(){
        $rooms = Rooms::all();
        $room_types = Room_types::all();
        
        return view('admin.rooms.view_rooms', compact(['rooms', 'room_types']));
    }

    public function add_room(Request $request)
    {
        $room = new Rooms;

        $request->validate([
            'room_no' => 'unique:rooms'
        ],
        [
            'room_no.unique' => 'Room number already exists.'
        ]);

        $room->room_no = $request->room_no;
        $room->room_type_id = Room_types::where('room_name', $request->room_type)->get()->value('id');
        $room->floor = $request->floor;
        $room->status = $request->status;

        $room->save();

        $housekeeping = new Housekeeping;
        $housekeeping->room_id = $room->id;
        $housekeeping->save();

        return redirect()->back()->with('message', 'Room Added Successfully');
    }

    public function add_multiple_rooms(Request $request)
    {
        for($i=0; $i < count($request->room_no); $i++){
            $room = new Rooms;
            $room->room_no = $request->room_no[$i];
            $room->room_type_id = Room_types::where('room_name', $request->room_type[$i])->get()->value('id');
            $room->floor = $request->floor[$i];
            $room->status = $request->status[$i];
            $room->save();

            $housekeeping = new Housekeeping;
            $housekeeping->room_id = $room->id;
            $housekeeping->save();
        }
        return redirect()->back()->with('message', 'Multiple Rooms Added Successfully');
    }

    public function update_room(Request $request, $id)
    {
        $room = Rooms::find($id);

        $request->validate([
            'edit_room_no' => 'unique:rooms,room_no,'.$room->id
        ],
        [
            'edit_room_no.unique' => $room->id
        ]);
        
        $room->room_no = $request->edit_room_no;
        $room->floor = $request->floor;
        $room->status = $request->status;

        $room->save();

        return redirect()->back()->with('message', 'Room Updated Successfully');
    }

    public function get_room($id)
    {
        $room = Rooms::find($id);
        return $room;
    }
    public function get_rooms()
    {
        return Rooms::all();
    }
    public function delete_room($id)
    {
        $room = Rooms::find($id);
        $room->delete();

        return redirect('all_rooms')->with('message', 'Room Deleted Successfully');
    }

    public function view_room_types(){
        $room_types = Room_types::all();

        return view('admin.rooms.view_room_types', compact('room_types'));
    }

    public function update_room_type_status(Request $request, $id)
    {
        $room_type = Room_types::find($id);

        if($request->switch){
            $request->switch = 'Active';
            foreach($room_type->rooms as $room){
                $room->status = "Open";
                $room->save();
            }
        }
        else {
            $request->switch = 'Inactive';
            foreach($room_type->rooms as $room){
                $room->status = "Out Of Order";
                $room->save();
            }
        }   

        $room_type->status = $request->switch;
        $room_type->save();

        return redirect()->back()->with('message', 'Room Type Status Updated Successfully');
    }

    public function get_room_type($id)
    {
        $room_type = Room_types::find($id);
        $room_type->pictures = $room_type->pictures;
        return $room_type;
    }

    public function add_room_type(Request $request)
    {
        $room_type = new Room_types;

        $request->validate([
            'room_name' => 'unique:room_types',
            'amenities' => 'required'
        ]);

        $room_type->room_name = $request->room_name;
        $room_type->default_occupancy = $request->default_occupancy;
        $room_type->max_occupancy = $request->max_occupancy;
        $room_type->rent = $request->rent;
        $room_type->extra_adult = $request->extra_adult;
        $room_type->points = $request->points;
        $room_type->points_required = $request->points_required;
        $room_type->beds = $request->beds;
        $room_type->views = $request->views;
        $room_type->status = $request->status;
        $room_type->description = $request->description;
        $room_type->amenities = $request->amenities;
        $room_type->save();

        if($request->has('pictures')){
            foreach ($request->file('pictures') as $image) {
                $room_pictures = new Room_pictures;
                $imageName = strtolower($room_type->room_name).'-'.uniqid().'.'.$image->getClientOriginalExtension();
                $room_pictures->room_type_id = $room_type->id;
                $room_pictures->picture = $image->move('rooms-picture', $imageName);
                $room_pictures->save();
            }
        }

        return redirect()->back()->with('message', 'Room Type Added Successfully');
    }

    public function update_room_type(Request $request, $id) 
    {
        $room_type = Room_types::find($id);

        $request->validate([
            'edit_room_name' => 'unique:room_types,room_name,'.$room_type->id,
            'edit_amenities' => 'required',
        ],
        [
            'edit_room_name.unique' => $room_type->id,
            'edit_amenities.required' => $room_type->id
        ]);
        
        $room_type->room_name = $request->edit_room_name;
        $room_type->rent = $request->edit_rent;
        $room_type->points = $request->edit_points;
        $room_type->points_required = $request->edit_points_required;
        $room_type->beds = $request->edit_beds;
        $room_type->status = $request->edit_status;
        $room_type->default_occupancy = $request->edit_default_occupancy;
        $room_type->max_occupancy = $request->edit_max_occupancy;
        $room_type->extra_adult = $request->edit_extra_adult;
        $room_type->description = $request->edit_description;
        $room_type->views = $request->edit_views;
        $room_type->amenities = $request->edit_amenities;
        $room_type->save();

        if($request->has('pictures')){
            foreach ($room_type->pictures as $picture) {
                unlink(public_path().'/'.$picture->picture);
                $picture->delete();
            }
            foreach ($request->file('pictures') as $image) {
                $room_pictures = new Room_pictures;
                $imageName = strtolower($room_type->room_name).'-'.uniqid().'.'.$image->getClientOriginalExtension();
                $room_pictures->room_type_id = $room_type->id;
                $room_pictures->picture = $image->move('rooms-picture', $imageName);
                $room_pictures->save();
            }
        }

        return redirect()->back()->with('message', 'Room Type Updated Successfully');
    }

    public function delete_room_type($id)
    {
        $room_type = Room_types::find($id);

        $room_type->delete();

        return redirect('room_types')->with('message', 'Room Type Deleted Successfully');
    }

    
}
