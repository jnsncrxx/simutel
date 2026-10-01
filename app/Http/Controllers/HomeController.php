<?php

namespace App\Http\Controllers;

use App\Models\Guests;
use App\Models\Payments;
use App\Models\Room_types;
use Illuminate\Http\Request;

use App\Models\Cancellations;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Room_reservations;
use Illuminate\Support\Facades\Auth;
use App\Notifications\SendEmailNotification;
use Illuminate\Support\Facades\Notification;

class HomeController extends Controller
{
    public function index(){
        if(Auth::check()){
            return redirect('/redirect');
        }
        else{
            return view('home.userpage');
        }
    }
    
    public function redirect(){
        
        $usertype=Auth::user()->usertype;
        if($usertype == 'employee') {
            return view('admin.dashboard');
        }
        else{
            return view('home.userpage');
        }
    }

    public function rooms(){
        if(optional(Auth::user())->usertype == 'employee'){
            return redirect('/redirect');
        }
        else{
            $room_types = Room_types::orderBy('rent')->get();
    
            return view('home.rooms.rooms', compact('room_types'));
        }
    }

    public function choose(){
        $room_types = Room_types::orderBy('rent')->get();
        $reservations = Room_reservations::all();
        if(optional(Auth::user())->usertype == 'employee'){
            return redirect('/redirect');
        }
        else{
            return view('home.reservation.choose_room', compact(['room_types','reservations']));
        }
    }

    public function details(Request $request){
        $room_type = Room_types::find($request->room_type_id);
        return view('home.reservation.details', compact(['room_type', 'request']));
    }

    public function add_reservation(Request $request)
    { 
        $guest = new Guests;
        if(Auth::check()){
            $guest_id = Auth::user()->member->guest->id;
            $notifyGuest = Auth::user()->member->guest;
        }
        else{
            $guest->first_name = $request->first_name;
            $guest->last_name = $request->last_name;
            $guest->email = $request->email;
            $guest->contact = $request->contact;
            $guest->birthday = $request->birthday;
            $guest->save();
            $guest_id = $guest->id;
            $notifyGuest = $guest;
        }

        $confirmations = [];
        for($i=1; $i<=$request->room_count; $i++){
            $reservation = new Room_reservations();
            $reservation->reserved_by_guest_id = $guest_id;
            $reservation->payer_guest_id = $guest_id;
            $reservation->room_type = Room_types::find($request->room_type_id)->room_name;
            $reservation->adults = $request->adults;
            $reservation->children = $request->children;
            $reservation->check_in = $request->check_in;
            $reservation->check_out = $request->check_out;
            $reservation->rate = $request->rate;
            $reservation->amount = $request->amount/$request->room_count;
            $reservation->requests = $request->requests;
            $reservation->source = "Website";
            if($request->check_in == date('Y-m-d'))
                $reservation->status = 'Due In';
            $reservation->save();

            if($request->currency == 'Points'){
                Auth::user()->member->points -= $request->amount/$request->room_count;
                Auth::user()->member->save();
                
                $payment = new Payments;
                $payment->room_reservation_id = $reservation->id;
                $payment->payer_guest_id = $guest_id;
                $payment->payment_method = $request->currency;
                $payment->amount = $request->amount/$request->room_count;
                $payment->save();
            }

            $data["reservation"] = $reservation;
            $data["room_rent"] = $request->rent_per_room;
            $data["currency"] = $request->currency;
            $pdf = Pdf::loadView('home.invoice', $data);
            $confirmations['PUPSJ-'.str_pad($reservation->id,6,"0")] = $pdf->output();
        }

        $details = [
            'greeting' => 'Hello '.ucwords($notifyGuest->first_name).',',
            'firstline' => 'Good Day!',
            'body' => 'Your reservation has been confirmed! Please view attached file.',
            'lastline' => 'Thank You!',
            'confirmation' => $confirmations
        ];
        Notification::send($notifyGuest, new SendEmailNotification($details));

        session()->put('id', $reservation->id);
        session()->put('room_count', $request->room_count);
        session()->put('currency', $request->currency);
        
        return redirect('confirmation');
    }

    public function view_confirmation(){
        $reservation = Room_reservations::find(session()->get('id'));
        $room_count = session()->get('room_count');
        $currency = session()->get('currency');

        return view('home.reservation.confirmation', compact('reservation', 'room_count', 'currency'));
    }

    public function account(){
        if(Auth::user()->usertype == 'employee'){
            return redirect('/redirect');
        }
        else{
            $reservations = Guests::find(Auth::user()->member->guest_id)->reserved_by;
            $room_types = Room_types::all();
            return view('home.account.account', compact(['room_types','reservations']));
        }
    }

    public function download_pdf(Request $request){
        $data["reservation"] = Room_reservations::find($request->id);
        $data["room_rent"] = $request->rent_per_room;
        $data["currency"] = $request->currency;
        $pdf = Pdf::loadView('home.invoice', $data);
        return $pdf->stream();
    }

    public function check_available(Request $request)
    {   
        $room_type = Room_types::where('room_name',$request->room_type)->get()->first();
        $reservations = Room_reservations::all()->filter(function ($reservation) use ($request) {
            if($reservation->status != 'Cancelled'){
                return 
                $request->check_in >= date('Y-m-d', strtotime($reservation->check_in)) && $request->check_out <= date('Y-m-d', strtotime($reservation->check_out)) ||
                date('Y-m-d', strtotime($reservation->check_in)) >= $request->check_in && date('Y-m-d', strtotime($reservation->check_out)) <= $request->check_out;
            }
        })->values();
        $booked = 0;
        foreach ($reservations as $reservation) {
            if($reservation->room_type == $room_type->room_name){
                $booked += 1;
            }
        }
        if($room_type->rooms->where('status', 'Open')->count()-$booked > 0){
            return true;
        }
        else
            return false;

    }

    public function modify_dates(Request $request){
        $reservation = Room_reservations::find($request->id);

        $reservation->check_in = $request->check_in;
        if($request->check_in == date('Y-m-d'))
            $reservation->status = 'Due In';
        else
            $reservation->status = 'Confirmed';

        $reservation->check_out = $request->check_out;
        $reservation->amount = $request->amount;
        $reservation->save();

        $data["reservation"] = $reservation;
        $data["room_rent"] = $request->room_rent;
        $data["currency"] = 'PHP';
        $pdf = Pdf::loadView('home.invoice', $data);
        $confirmations['PUPSJ-'.str_pad($reservation->id,6,"0")] = $pdf->output();
        
        $notifyGuest = $reservation->reserved_by;
        $details = [
            'greeting' => 'Hello '.ucwords($notifyGuest->first_name).',',
            'firstline' => 'Good Day!',
            'body' => 'We would like to notify you that your reservation with a confirmation number #PUPSJ-'.str_pad($reservation->id,6,"0").' had its dates modified on '.date('D, M d, Y h:i A', strtotime($reservation->updated_at)).'. Please view attached file.',
            'lastline' => 'Thank You!',
            'confirmation' => $confirmations
        ];
        Notification::send($notifyGuest, new SendEmailNotification($details));
            
        return true;
    }

    public function cancel_reservation(Request $request){
        $reservation = Room_reservations::find($request->id);
        $reservation->status = 'Cancelled';
        $reservation->save();

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
            
        return true;
    }
    
}
