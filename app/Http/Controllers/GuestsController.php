<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Guests;
use App\Models\Members;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use function PHPUnit\Framework\isEmpty;

class GuestsController extends Controller
{
    public function view_guests(){
        $guests = Guests::all()->filter(function ($guest) {
           if($guest->reservations->isNotEmpty()){
                foreach($guest->reservations as $reservation){
                    if(date('Y-m-d') >= date('Y-m-d', strtotime($reservation->check_in)) && date('Y-m-d') <= date('Y-m-d', strtotime($reservation->check_out)))
                        return true;
                }
           }
        });
        
        return view('admin.guests.guests',compact('guests'));
    }

    public function get_guest($id){
        $guest = Guests::find($id);
            $guest->member->user = $guest->member->user;
        return $guest;
    }

    public function add_member(Request $request)
    {   
        $user = new User;
        
        $request->validate([
            'email' => 'unique:users',
            'username' => 'unique:users',
        ]);
        
        $user->email = $request->email;
        $user->username = $request->username;
        $user->password = Hash::make(Str::random(10));
        $user->save();

        $guest = new Guests;
        $guest->first_name = $request->first_name;
        $guest->last_name = $request->last_name;
        $guest->contact = $request->contact;
        $guest->birthday = $request->birthday;
        $guest->email = $request->email;
        $guest->save();
        
        $member = new Members;
        $member->user_id = $user->id;
        $member->guest_id = $guest->id;
        $member->save();

        return redirect()->back()->with('message', 'Member Added Successfully');
    }

    public function update_member(Request $request, $id)
    {
        $member = Members::find($id);
        
        $request->validate([
            'edit_email' => 'unique:users,email,'.$member->user->id,
            'edit_username' => 'unique:users,username,'.$member->user->id,
        ],
        [
            'edit_email.unique' => $member->id,
            'edit_username.unique' => $member->id
        ]);

        $member->user->email = $request->edit_email;
        $member->user->username = $request->edit_username;
        $member->user->save();

        $member->guests->email = $request->edit_email;
        $member->guests->contact = $request->edit_contact;
        $member->guests->save();

        return redirect()->back()->with('message', 'Member Updated Successfully');
    }
}
