<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Guests;
use App\Models\Members;
use App\Models\Payments;
use App\Models\Room_types;
use App\Models\Housekeeping;
use Illuminate\Http\Request;
use App\Models\Cancellations;
use App\Models\Room_reservations;
use App\Models\Additional_charges;
use App\Models\Guest_reservations;
use Illuminate\Support\Facades\Auth;
use App\Notifications\SendEmailNotification;
use Illuminate\Support\Facades\Notification;

class ReservationsController extends Controller
{
    public function view_reservations()
    {
        $reservations = Room_reservations::all();
        $housekeepings = Housekeeping::all()->where('reservation_status', 'vacant');
        $room_types = Room_types::all();
        
        foreach($reservations as $reservation){
            if($reservation->reservation_status == 'Checked-in' && $reservation->check_out == date('Y-m-d')){
                $reservation->reservation_status = 'Due Out';
                $reservation->save();
            }
            else if($reservation->reservation_status == 'Checked-in' && $reservation->check_out == date('Y-m-d')){
                $reservation->reservation_status = 'Due Out';
                $reservation->save();
            }
            else if($reservation->check_in == date('Y-m-d')){
                foreach(Housekeeping::all() as $housekeeping){
                    if($reservation->room_no == $housekeeping->rooms->room_no && $reservation->status == 'Confirmed'){
                        $housekeeping->reservation_status = 'pending';
                        $housekeeping->save();
                    }
                }
            }
            $paid = 0;
            foreach ($reservation->payments as $payment) {
                $paid += $payment->amount;
            }
            $reservation->paid = $paid;
        }
        
        return view('admin.reservations.view_reservations', compact(['reservations','housekeepings','room_types']));
    }
    public function filter_available(Request $request)
    {
        $room_types = Room_types::select('id','room_name','rent','default_occupancy','max_occupancy','extra_adult')->get();
        $reservations = Room_reservations::all()->filter(function ($item) use ($request) {
            if($item->status != 'Cancelled' && $item->status != 'No Show'){
                return 
                date('Y-m-d', strtotime($request->check_in)) >= date('Y-m-d', strtotime($item->check_in)) && date('Y-m-d', strtotime($request->check_out)) <= date('Y-m-d', strtotime($item->check_out)) ||
                date('Y-m-d', strtotime($item->check_in)) >= date('Y-m-d', strtotime($request->check_in)) && date('Y-m-d', strtotime($item->check_out)) <= date('Y-m-d', strtotime($request->check_out));
            }
        })->values();
        $room_types = $room_types->filter(function ($room_type) use($reservations) {
            $room_type->available_room_count = $room_type->rooms->where('status', 'Open')->count();
            $room_type->available_room_no = $room_type->rooms;
            foreach ($reservations as $reservation) {
                if($reservation->room_type == $room_type->room_name){
                    $room_type->available_room_count--;

                    $room_type->available_room_no = $room_type->available_room_no->filter(function ($room) use ($reservation) {
                        return $room->room_no != $reservation->room_no;
                    });
                }
            }
            foreach($room_type->rooms as $room){
                $room->housekeeping = $room->housekeeping;
            }
            return true; 
        })->values();
        
        $room_types->transform(function($room_type) {
            unset($room_type->rooms);
            return $room_type;
        });
        return array("check_in"=>$request->check_in, "check_out"=>$request->check_out, "room_types"=>$room_types);
    }

    public function get_members()
    {
        $guests = Guests::select('id','first_name','last_name','email','contact','birthday')->get();

        $members = $guests->filter(function ($guest) {
            if($guest->member)
                return true;
        });
        return $members;
    }

    public function get_reservations($id)
    {
        $reservations = Room_reservations::all();
        foreach($reservations as $reservation){
            $reservation->reserved_by = $reservation->reserved_by;
        }
        $reservation = $reservations->find($id);
        $reservation->guests = $reservation->guests;
        $reservation->payer = $reservation->payer;
        $reservation->additional_charges = $reservation->additional_charges;
        $paid = 0;
        foreach ($reservation->payments as $payment) {
            $paid += $payment->amount;
        }
        $reservation->paid = $paid;
        $reservation->payments = $reservation->payments;
        if(isset($reservation->payer->member->user))
            $reservation->payer->member->user = $reservation->payer->member->user;
        foreach ($reservation->guests as $guest) {
            if(isset($guest->member->user))
             $guest->member->user = $guest->member->user;
        }

        return $reservations;
    }

    public function add_reservation(Request $request)
    { 
        if($request->done == false){
            $primaryId = $request->primary['primaryId'];
            if(empty($request->primary['primaryId'])){
                $primary_guest = new Guests;
                $primary_guest->first_name = $request->primary['primaryFN'];
                $primary_guest->last_name = $request->primary['primaryLN'];
                $primary_guest->email = $request->primary['primaryEmail'];
                $primary_guest->contact = $request->primary['primaryContact'];
                $primary_guest->birthday = $request->primary['primaryBirthday'];
                $primary_guest->save();
                $primaryId = $primary_guest->id;
            }

            foreach ($request->bookings as $booking) {
                $reservation = new Room_reservations();
                $reservation->reserved_by_guest_id = $primaryId;
                $reservation->payer_guest_id = $primaryId;
                $reservation->room_no = $booking['roomNo'];
                $reservation->room_type = $booking['roomType'];
                $reservation->adults = $booking['adults'];
                $reservation->children = $booking['children'];
                $reservation->check_in = $booking['checkIn'];
                $reservation->check_out = $booking['checkOut'];
                $reservation->rate = $booking['rate'];
                $reservation->amount = $booking['price'];
                $reservation->source = $request->primary['source'];
                if($booking['checkIn'] == date('Y-m-d'))
                    $reservation->status = 'Due In';
                $reservation->save();
            }
        }
        return redirect()->back()->with('message', 'Reservations Added Successfully');
    }
    
    public function update_reservation_room_no(Request $request, $id)
    {
        $reservation = Room_reservations::find($id);

        foreach(Housekeeping::all() as $housekeeping){
            if(!$reservation->room_no && $request->room_no && $housekeeping->rooms->room_no == $request->room_no){
                $housekeeping->reservation_status = 'occupied';
                if(date('Y-m-d h:i:s') >= $reservation->check_in && date('Y-m-d h:i:s') <= $reservation->check_out)
                    $housekeeping->reservation_status = 'pending';

                $housekeeping->save();
            }
            else if($reservation->room_no && $request->room_no && $housekeeping->rooms->room_no == $reservation->room_no){
                $housekeeping->reservation_status = 'vacant';
                $housekeeping->save();

                foreach(Housekeeping::all() as $housekeepin){
                    if($housekeepin->rooms->room_no == $request->room_no){
                        $housekeepin->reservation_status = 'occupied';
                        if(date('Y-m-d h:i:s') >= $reservation->check_in && date('Y-m-d h:i:s') <= $reservation->check_out)
                            $housekeeping->reservation_status = 'pending';
                            
                        $housekeepin->save();
                    }
                }
            }
            else if($reservation->room_no && !$request->room_no && $housekeeping->rooms->room_no == $reservation->room_no){
                $housekeeping->reservation_status = 'vacant';

                $housekeeping->save();
            }
        }

        $reservation->room_no = $request->room_no;
        $reservation->save();

        return redirect()->back()->with('message', 'Reservation Room Number Updated Successfully');
    }

    public function update_reservation_status(Request $request, $id)
    {
        $reservation = Room_reservations::find($id);
        $reservation->status = $request->status;
        if($request->status == 'Checked-in'){
            $reservation->check_in = explode(' ',$reservation->check_in)[0].' '.$request->time_in.':00';
            $reservation->check_out = explode(' ',$reservation->check_out)[0].' '.$request->time_out.':00';
            $reservation->room_no = $request->room_no;
            foreach(Housekeeping::all() as $housekeeping){
                if($housekeeping->rooms->room_no == $reservation->room_no){
                    $housekeeping->reservation_status = 'occupied';
                    $housekeeping->save();
                }
            }
            for ($i=0; $i < count($request->member_id); $i++) { 
                $guest_id = $request->member_id[$i];
                if(empty($guest_id)){
                    $new_guest = new Guests;
                    $new_guest->first_name = $request->first_name[$i];
                    $new_guest->last_name = $request->last_name[$i];
                    $new_guest->email = $request->email[$i];
                    $new_guest->contact = $request->contact[$i];
                    $new_guest->birthday = $request->birthday[$i];
                    $new_guest->save();
                    $guest_id = $new_guest->id;
                }
    
                $guestreservation = new Guest_reservations();
                $guestreservation->guest_id = $guest_id;
                $guestreservation->room_reservation_id = $reservation->id;
                $guestreservation->save();
            }
        }
        else if($request->status == 'Checked-out'){
            $reservation->check_in = explode(' ',$reservation->check_in)[0].' '.$request->time_in.':00';
            $reservation->check_out = explode(' ',$reservation->check_out)[0].' '.$request->time_out.':00';

            if(isset($reservation->reserved_by->member) && $reservation->rate != 'Points'){
                $diff = date_diff(date_create($reservation->check_in), date_create($reservation->check_out))->format("%a");
                $reservation->reserved_by->member->points += Room_types::where('room_name', $reservation->room_name)->get()->value('points') * $diff;
                $reservation->reserved_by->member->total_points += Room_types::where('room_name', $reservation->room_name)->get()->value('points') * $diff;
                $reservation->reserved_by->member->save();
            }

            foreach(Housekeeping::all() as $housekeeping){
                if($housekeeping->rooms->room_no == $reservation->room_no){
                    $housekeeping->status = 'dirty';
                    $housekeeping->reservation_status = 'vacant';
                    $housekeeping->save();
                }
            }
        }
        else if($request->status == 'Cancelled' || $request->status == 'No Show'){
            foreach(Housekeeping::all() as $housekeeping){
                if($housekeeping->rooms->room_no == $reservation->room_no){
                    $housekeeping->reservation_status = 'vacant';
                    $housekeeping->save();
                }
            }
        }
        $reservation->save();
        if($request->status == 'Cancelled'){
            $body = '';
            if($reservation->updated_at >= date('Y-m-d', strtotime($reservation->check_in.'-2 day')) && $reservation->updated_at <= $reservation->check_in){
                $cancellation = new Cancellations;
                $cancellation->room_reservation_id = $reservation->id;
                $cancellation->reason = $request->reason;
                $cancellation->amount_charge = $reservation->amount*.5;
                $cancellation->save();

                $payment = new Payments;
                $payment->room_reservation_id = $reservation->id;
                $payment->payer_guest_id = $reservation->reserved_by->id;
                $payment->payment_method = 'Visa';
                $payment->amount = $reservation->amount*.5;
                $payment->save();

                $body = 'We would like to notify you that your reservation with a confirmation number #PUPSJ-'.str_pad($reservation->id,6,"0").' had been cancelled on '.date('D, M d, Y h:i A', strtotime($reservation->updated_at)).'. Your cancellation is within 48 hours before arrival with that you have been charged PHP '.number_format($cancellation->amount_charge, 2, '.').' on your credit card which is 50% of the rate.';

            }
            else {
                $cancellation = new Cancellations();
                $cancellation->room_reservation_id = $reservation->id;
                $cancellation->reason = $request->reason;
                $cancellation->amount_charge = 0;
                $cancellation->save();

                $body = 'We would like to notify you that your reservation with a confirmation number #PUPSJ-'.str_pad($reservation->id,6,"0").' had been cancelled on '.date('D, M d, Y h:i A', strtotime($reservation->updated_at)).'.';
            }

            $notifyGuest = $reservation->reserved_by;
            $details = [
                'greeting' => 'Hello '.ucwords($notifyGuest->first_name).',',
                'firstline' => 'Good Day!',
                'body' => $body,
                'button' => 'Book Again',
                'url' =>  $_SERVER['HTTP_HOST'].'/choose?check_in='.date('Y-m-d').'&check_out='.date('Y-m-d', strtotime('+1 day')).'&rooms=1&adults=1&children=0&currency=PHP',
                'lastline' => 'Thank You!',
            ];

            Notification::send($notifyGuest, new SendEmailNotification($details));
        }
        return redirect()->back()->with('message', 'Reservation Status of Booking #'.$id.' Changed to ' .$request->status. ' Successfully');
    }

    public function additional_charges(Request $request, $id)
    { 
        $additional_charge = new Additional_charges;
        $additional_charge->room_reservation_id = $id;
        $additional_charge->description = $request->description;
        $additional_charge->amount = $request->amount;
        $additional_charge->save();
        return Room_reservations::find($id)->additional_charges;
    }

    public function remove_charges($id)
    { 
        $additional_charge = Additional_charges::find($id);
        $id = $additional_charge->room_reservation_id;
        $additional_charge->delete();
        return Room_reservations::find($id)->additional_charges;
    }

    public function add_extra_guest(Request $request, $id)
    { 
        $reservation = Room_reservations::find($id);
        $age = date_diff(date_create($request->birthday), date_create('now'))->y;
        if($age < 18)
            $reservation->children = $reservation->children+1;
        else
            $reservation->adults =  $reservation->adults+1;
        $reservation->save();

        $guest_id = $request->member_id;
        if(empty($request->member_id)){
            $new_guest = new Guests;
            $new_guest->first_name = $request->first_name;
            $new_guest->last_name = $request->last_name;
            $new_guest->email = $request->email;
            $new_guest->contact = $request->contact;
            $new_guest->birthday = $request->birthday;
            $new_guest->save();
            $guest_id = $new_guest->id;
        }

        $guestreservation = new Guest_reservations();
        $guestreservation->guest_id = $guest_id;
        $guestreservation->room_reservation_id = $reservation->id;
        $guestreservation->save();

        return redirect()->back()->with('message', 'Extra guest added to booking #'.$id.' successfully');
    }

    public function remove_booking_guest(Request $request, $id)
    { 
        Guest_reservations::where('guest_id',$request->guestID)->where('room_reservation_id',$id)->get()->first()->delete();
        $reservation = Room_reservations::find($id);
        $age = date_diff(date_create(Guests::find($request->guestID)->birthday), date_create('now'))->y;
        if($age < 18)
            $reservation->children = $reservation->children-1;
        else
            $reservation->adults = $reservation->adults-1;
        $reservation->save();
        return redirect()->back()->with('message', 'Guest removed from booking #'.$id.' successfully');
    }

    public function move_booking_guest(Request $request, $id)
    { 
        $guest_reservation = Guest_reservations::where('guest_id',$request->guestID)->where('room_reservation_id',$id)->get()->first();
        $guest_reservation->room_reservation_id = $request->toBooking;
        $guest_reservation->save();

        $reservation = Room_reservations::find($request->toBooking);
        $age = date_diff(date_create(Guests::find($request->guestID)->birthday), date_create('now'))->y;
        if($age < 18)
            $reservation->children = $reservation->children+1;
        else
            $reservation->adults = $reservation->adults+1;
        $reservation->save();

        $reservation = Room_reservations::find($id);
        if($age < 18)
            $reservation->children = $reservation->children-1;
        else
            $reservation->adults = $reservation->adults-1;
        $reservation->save();

        return redirect()->back()->with('message', 'Guest moved to booking #'.$request->toBooking.' successfully');
    }

    public function reschedule(Request $request, $id)
    { 
        $reservation = Room_reservations::find($id);
        $reservation->check_in = $request->check_in;
        if($request->check_in == date('Y-m-d'))
            $reservation->status = 'Due In';
        else
            $reservation->status = 'Confirmed';
        $reservation->check_out = $request->check_out;
        $reservation->amount = $request->amount;
        $reservation->save();

        return redirect()->back()->with('message', 'Booking #'.$id.' rescheduled successfully');
    }

    public function change_room(Request $request, $id)
    { 
        $reservation = Room_reservations::find($id);
        $reservation->room_type = $request->room_type;
        $reservation->room_no = $request->room_no;
        $reservation->amount = $request->amount;
        $reservation->save();

        return redirect()->back()->with('message', 'Booking #'.$id.' changed to room no.'.$request->room_no.' successfully');
    }

    public function edit_booking_time(Request $request, $id)
    { 
        $reservation = Room_reservations::find($id);
        $reservation->check_in = explode(' ',$reservation->check_in)[0].' '.$request->time_in.':00';
        $reservation->check_out = explode(' ',$reservation->check_out)[0].' '.$request->time_out.':00';
        $reservation->save();

        return redirect()->back()->with('message', 'Check-in/check-out time of booking #'.$id.' updated successfully');
    }

    public function change_booking_payer(Request $request, $id)
    { 
        $reservation = Room_reservations::find($id);

        $guest_id = $request->member_id;
        if(empty($request->member_id)){
            $new_guest = new Guests;
            $new_guest->first_name = $request->first_name;
            $new_guest->last_name = $request->last_name;
            $new_guest->email = $request->email;
            $new_guest->contact = $request->contact;
            $new_guest->birthday = $request->birthday;
            $new_guest->save();
            $guest_id = $new_guest->id;
        }
        $reservation->payer_guest_id = $guest_id;
        $reservation->save();

        return redirect()->back()->with('message', 'Payer of booking #'.$id.' changed successfully');
    }
    
}
