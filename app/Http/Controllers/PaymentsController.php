<?php

namespace App\Http\Controllers;

use App\Models\Payments;
use App\Models\Room_reservations;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    public function view_payments()
    {   
        $payments = Payments::all();
        return view('admin.payments.view_payments', compact('payments'));
    }

    public function add_booking_payment(Request $request, $id)
    { 
        $payment = new Payments;
        $payment->room_reservation_id = $id;
        $payment->payer_guest_id = Room_reservations::find($id)->payer_guest_id;
        $payment->payment_method = $request->method;
        $payment->amount = $request->amount;
        $payment->save();
        return redirect()->back()->with('message', 'Payment of ₱'.number_format((float)$request->amount, 2, '.').' added to booking #'.$id.' successfully');
    }

    public function get_payment($id)
    {   
        $payment = Payments::find($id);
        $payment->payer = $payment->guests;
        unset($payment->room_reservations);
        return $payment;
    }
}
