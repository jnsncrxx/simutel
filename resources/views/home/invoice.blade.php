<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Confirmation</title>
    <link rel="stylesheet" href="home/dist/css/bootstrap.min.css">
</head>
<body>
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
        $diff = date_diff(date_create($reservation->check_in), date_create($reservation->check_out))->format("%a");
        $room_rent *= $diff;
        $vat = number_format(((float)$room_rent*.12) * $currency_api[$currency], 2, '.');
        $cht = number_format(((float)$room_rent*.02) * $currency_api[$currency], 2, '.');
        $serviceCharge = number_format(((float)$room_rent*.1) * $currency_api[$currency], 2, '.');

        $totalTax = $room_rent*.12 + $room_rent*.02 + $room_rent*.1;
    @endphp
    <div class="container mx-auto" style="max-width: 650px; color:black; font-family: Arial, Helvetica, sans-serif">
        <p style="text-align:left;">
            Reservation Confirmation
            <span style="float:right;">{{date('m/d/Y', strtotime($reservation->created_at))}}</span>
        </p>
        <h1 class="mt-4" style="color: #D4AF37; font-size: 40px;">PUPSJ Hotel</h1>
        <p class="mb-0">223 Ortega St., cor. A. Mabini St. Brgy. Addition Hills, San Juan City, <br>Metro Manila, Philippines 1500</p>
        <p class="mb-0">Contact details: (+63 2) 7738-5071</p>
        <p class="my-4"><b>Thank you for choosing to stay with us!</b></p>
        <h2 class="mb-3">Confirmation number: #{{'PUPSJ-'.str_pad($reservation->id,6,"0")}}</h2>
        <h3>Reservation Summary</h3>
        <hr class="mt-0">
            <div style="float: left; width:53%">
                <div class="pe-3">
                    <p style="text-align:right;">
                        {{date('D, M d, Y', strtotime($reservation->check_in))}}<span style="float:left;"><b>Check-in</b></span>
                        <br>2:00 PM
                    </p>
                    <p style="text-align:right;">
                        {{date('D, M d, Y', strtotime($reservation->check_out))}}<span style="float:left;"><b>Check-out</b></span>
                        <br>12:00 PM
                    </p>
                    <hr>
                    <p style="text-align:right;">
                        {{ucwords($reservation->room_type)}}<span style="float:left;"><b>Room type</b></span>
                    </p>
                    <p style="text-align:right;">
                        {{$reservation->adults}} {{($reservation->adults < 2)? ' Adult' : ' Adults'}} 
                        @if($reservation->children > 0)
                            {{', '.$reservation->children}} {{($reservation->children < 2)? 'Child' : ' Children'}}
                        @endif
                        <span style="float:left;"><b>{{($reservation->adults+$reservation->children < 2)? ' Guest' : ' Guests'}}</b></span>
                    </p>
                    <hr>
                    <p style="text-align:right;">
                        {{ucwords($reservation->reserved_by->first_name).' '.ucwords($reservation->reserved_by->last_name)}}<span style="float:left;"><b>Guest details</b></span>
                        @if($reservation->reserved_by->email)<br>{{ucwords($reservation->reserved_by->email)}}@endif
                        @if($reservation->reserved_by->contact)<br>{{ucwords($reservation->reserved_by->contact)}}@endif
                    </p>
                </div>
            </div>
            <div style="float:right; width:47%">
                <div class="ps-2">
                    <div class="pt-1">
                        <p style="text-align:right;">
                            {{ucwords($reservation->rate)}}<span style="float:left;"><b>Rate</b></span>
                        </p>
                    </div>
                    <div class="p-2 pe-0" style="background: lightgray; border-bottom: 1px solid gray">
                        <p style="text-align:right; margin: 0">
                            <small>{{($currency == 'Points')? $room_rent .' '. $currency:$currency.' '.number_format(((float)$room_rent) * $currency_api[$currency], 2, '.')}}
                                <span style="float:left;">Subtotal</span>
                            </small>
                        </p>
                    </div>
                    <div class="p-2 pe-0">
                        @php
                             $period = new DatePeriod(
                                new DateTime($reservation->check_in),
                                new DateInterval('P1D'),
                                new DateTime($reservation->check_out)
                            );   
                        @endphp
                        @foreach ($period as $key => $value)
                            <p style="text-align:right; margin: 0">
                                <small>{{$currency}} {{number_format((float)$room_rent/$diff * $currency_api[$currency], 2, '.')}}
                                    <span style="float:left;">{{$value->format('D, M d, Y')}}</span>
                                </small>
                            </p>
                        @endforeach
                    </div>
                    @if($currency != 'Points')
                        <div class="p-2 pe-0" style="background: lightgray; border-bottom: 1px solid gray">
                            <p style="text-align:right; margin: 0">
                                <small>{{$currency.' '.number_format((float)$totalTax * $currency_api[$currency], 2, '.')}}
                                    <span style="float:left;">Taxes & Fees</span>
                                </small>
                            </p>
                        </div>
                        <div class="px-2 pt-2">
                            <p style="text-align:right; margin: 0">
                                <small>{{$currency.' '.$vat}}<span style="float:left;">VALUE ADDED TAX</span></small>
                            </p>
                        </div>
                        <div class="px-2 py-1">
                            <p style="text-align:right; margin: 0">
                                <small>{{$currency.' '.$cht}}<span style="float:left;">CITY HOTEL TAX</span></small>
                            </p>
                        </div>
                        <div class="px-2 pb-2">
                            <p style="text-align:right; margin: 0">
                                <small>{{$currency.' '.$serviceCharge}}<span style="float:left;">SERVICE CHARGE</span></small>
                            </p>
                        </div>
                    @endif
                    <div class="p-2" style="background: lightgray; border-bottom: 2.5px solid dimgray">
                        <p style="text-align:right; margin: 0">
                            <small><b>{{$currency}} {{number_format(((float)$totalTax+$room_rent) * $currency_api[$currency], 2, '.')}}<span style="float:left;">Total Amount</span></b></small>
                        </p>
                    </div>
                </div>
            </div>
        <div style="clear: right">
            <hr class="mt-2">
            <p style="text-align:left;">
                <b>Special Requests</b>
                <span style="float:right;">{{$reservation->requests}}</span>
            </p>
        </div>
        <p class="position-absolute bottom-0"><b>Need help with your reservation?</b> Please don't hesitate to contact us (+63 2) 7738-5071</p>
    </div>
</body>
</html>