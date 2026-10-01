<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reservation</title>
    @include('home.css')
    <style>
        .header a {
            font-family: initial;
            width: auto !important;
            text-decoration: none;
            margin: 0 5vw;
            position: relative;
            color: var(--gray);
        }
        .header a:nth-child(3)::after{
            position: absolute;
            content: '';
            background: var(--gray);
            width: 100%;
            height: 2px;
            right: 0;
            bottom: 0;
        }
        .confirmation .container,.details .container {
            background: floralwhite;
        }
        .confirmation .container h1,.details .container h2 {
            color: var(--gold);
        }
        .confirmation .container h6 {
            font-family: initial;
        }
        .confirmation .container h6:nth-child(1){
            margin: 0;
        }
    </style>
</head>
<body>
@include('home.navbar')
<section style="margin-top: 60px">
    <div class="container header pt-4 d-flex justify-content-center border-bottom d-none d-sm-flex">
        @if(!isset($_GET['no']))
            <a class="col-4">Choose Room</a> →
            <a class="col-4 pb-1">Enter Details and Payment</a> →
            <a class="col-4">View Confirmation</a>
        @endif
    </div>
</section>
<section class="confirmation text-center mt-3">
    <div class="container py-3 shadow-sm">
        <h1>BOOKING CONFIRMED</h1>
        <h6>We are pleased to inform you that your reservation request has been received and confirmed.</h6>
        <h6>Thank you for choosing to stay at PUPSJ Hotel.</h6>
        <h6>Please check your email to verify the confirmation of your reservation. Thank you.</h6>
    </div>
</section>
<section class="details text-center my-4">
    <div class="container py-3 px-5 shadow-sm">
        <h2 class="m-0">PUPSJ HOTEL</h2>
        <h3 class="border-bottom pb-2">Reservation Details</h3>
        <div class="mt-1 d-grid justify-content-center">
            <div class="text-start" style="display: grid; grid-template-columns: repeat(2, auto); grid-column-gap: 50px;">
                <h5>Guest Name:</h5>
                <h5>{{ucwords($reservation->reserved_by->first_name).' '.ucwords($reservation->reserved_by->last_name)}}</h5>
                <h5>Check-in Date:</h5>
                <h5>{{date('D, M d, Y', strtotime($reservation->check_in))}}</h5>
                <h5>Check-out Date:</h5>
                <h5>{{date('D, M d, Y', strtotime($reservation->check_out))}}</h5>
                <h5>No. of Nights:</h5>
                <h5>
                    @php $diff = date_diff(date_create($reservation->check_in), date_create($reservation->check_out))->format("%a");
                        echo $diff;
                        echo ($diff < 2)? ' Night' : ' Nights';
                    @endphp
                </h5>
                <h5>Room Type:</h5>
                <h5>{{ucwords($reservation->room_type)}}</h5>
                <h5>No. of Rooms:</h5>
                <h5>
                    @php 
                        echo $room_count;
                        echo ($room_count < 2)? ' Room' : ' Rooms';
                    @endphp
                </h5>
                <h5>No. of Guests:</h5>
                <h5>
                    @php 
                        echo ($reservation->adults+$reservation->children)*$room_count;
                        echo (($reservation->adults+$reservation->children)*$room_count < 2)? ' Guest' : ' Guests';
                    @endphp
                </h5>
                <h5>Total Costs:</h5>
                @php 
                    $response_json = file_get_contents('https://api.exchangerate-api.com/v4/latest/PHP');
                    if(false !== $response_json) {
                        try {
                            $currency_api = (array) json_decode($response_json)->rates;
                            if($currency == 'Points')
                                $currency_api['Points'] = 1;
                        }
                        catch(Exception $e) {
                        }
                    }  
                @endphp
                @if($currency == 'Points')
                    <h5>{{$reservation->amount*$room_count}} {{$currency}}</h5>
                @else
                    <h5>{{$currency}} {{number_format(((float)$reservation->amount*$room_count) * $currency_api[$currency], 2, '.')}}</h5>
                @endif
                <h5>Rate:</h5>
                <h5>{{ucwords($reservation->rate.' Rate')}}</h5>
                <h5>Special Requests:</h5>
                <h5>{{($reservation->requests != null)? $reservation->requests : 'None'}}</h5>
            </div>
        </div>
    </div>
    <a href="/choose?check_in={{date('Y-m-d')}}&check_out={{date('Y-m-d', strtotime('+1 day'))}}&rooms=1&adults=1&children=0&currency={{session()->get('currency')}}" class="book-btn btn btn-warning rounded-0 w-50 mt-3">BOOK AGAIN</a>
</section>
@include('home.navbar-script')
</body>
</html>