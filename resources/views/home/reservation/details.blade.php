<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reservation</title>
    @include('home.css')
    <style>
        .details {
            background: ghostwhite;
        }
        .header a {
            font-family: initial;
            width: auto !important;
            text-decoration: none;
            margin: 0 5vw;
            position: relative;
            color: var(--gray);
        }
        .header a:nth-child(1):hover{
            color: darkslategray;
        }
        .header a:nth-child(2)::after{
            position: absolute;
            content: '';
            background: var(--gray);
            width: 100%;
            height: 2px;
            right: 0;
            bottom: 0;
        }
        .header a:nth-child(3){
            color: lightgray;
        }

        .details h4 {
            color: var(--gold);
            font-weight: bold;
        }
        img {
            object-fit: cover;
            height: 240px;
        }
        .box-shadow {
            box-shadow: rgba(0, 0, 0, 0.1) 0px 5px 15px;
        }
        p {
            font-family: initial;
            margin: 0;
            color: dimgray;
        }
        .sub {
            font-size: .9em;
            color: gray;
        }
        .sticky-top {
            top: 80px;
        }
        .icon {
            right: 10px;
        }
        input {
            padding: 2px 5px;
        }
        body {
            line-height: unset;
        }
    </style>
</head>
<body>
@include('home.navbar')
<section style="margin-top: 60px">
    <div class="container header pt-4 d-flex justify-content-center border-bottom">
        <a href="/choose?check_in={{date('Y-m-d')}}&check_out={{date('Y-m-d', strtotime('+1 day'))}}&rooms=1&adults=1&children=0&currency={{(isset($_GET['currency']))?$_GET['currency']:'PHP'}}" class="col-4">Choose Room</a> →
        <a class="col-4 pb-1">Enter Details and Payment</a> →
        <a class="col-4">View Confirmation</a>
    </div>
</section>
<section class="details">
    <div class="container py-4 px-0 mx-auto row flex-row-reverse">
        <div class="col-4 sticky-top bg-light pe-0">
            <div class="box-shadow p-2">
                <h4>Price Summary</h4>
                <div class="d-flex justify-content-between fw-bold mt-3">
                    <p>
                        @php $diff = date_diff(date_create($request->check_in), date_create($request->check_out))->format("%a");
                            echo $diff;
                            echo ($diff < 2)? ' Night' : ' Nights';
                        @endphp
                        Stay
                    </p>
                    <p>
                        @php 
                            $response_json = file_get_contents('https://api.exchangerate-api.com/v4/latest/PHP');
                            if(false !== $response_json) {
                                try {
                                    $currency_api = (array) json_decode($response_json)->rates;
                                    if($_GET['currency'] == 'Points')
                                        $currency_api['Points'] = 1; 
                                }
                                catch(Exception $e) {
                                }
                            }  
                            if($_GET['currency'] != 'Points' && Auth::check() && Auth::user()->member->points >= 500) 
                                $rentPerRoom = $diff*($request->rent-$request->rent*.2);
                            else
                                $rentPerRoom = $diff*$request->rent;
                                
                            if($_GET['currency'] == 'Points')
                                echo $rentPerRoom.' '.$_GET['currency'];
                            else
                                echo $_GET['currency'].' '.number_format((float)$rentPerRoom * $currency_api[$_GET['currency']], 2, '.');
                        @endphp
                    </p>
                </div>
                <style>
                    details>summary {
                        list-style: none;
                    }
                    summary::-webkit-details-marker {
                        display: none
                    }
                    .close > summary > div::after {
                        content: "\1401";
                        position: absolute;
                        right: 0;
                    }
                    .open > summary > div::after {
                        content: "\1403";
                        position: absolute;
                        right: 0;
                    }
                    summary {
                        bottom: -1.2em;
                    }
                </style>
                @if($diff > 10)
                <details class="close position-relative mb-4" ontoggle="
                        if(this.open){
                            this.classList.add('open');
                            this.classList.remove('close');
                            this.querySelector('span').innerHTML = 'Hide';
                        }
                        else{
                            this.classList.add('close');
                            this.classList.remove('open');
                            this.querySelector('span').innerHTML = 'Show';
                        }
                    ">
                    <summary class="text-primary position-absolute w-100"><div><span>Show</span> Price Details</div></summary>
                @endif
                    <div class="d-flex justify-content-between">
                        @php
                            $period = new DatePeriod(
                                new DateTime($request->check_in),
                                new DateInterval('P1D'),
                                new DateTime($request->check_out)
                            );
                            echo '<div>';
                            foreach ($period as $key => $value) {
                                echo '<p class="sub">'.$value->format('D, M d, Y').'</p>';   
                            }
                            echo '</div>';
                            echo '<div>';
                            for($i=1; $i<=$diff; $i++){
                                if($_GET['currency'] == 'Points')
                                    echo '<p class="sub">'.(float)$rentPerRoom/(float)$diff.' '.$_GET['currency'].'</p>';
                                else
                                    echo '<p class="sub">'.$_GET['currency'].' '.number_format(((float)$rentPerRoom/(float)$diff) * $currency_api[$_GET['currency']], 2, '.').'</p>';
                            }
                            echo '</div>';
                        @endphp
                    </div>
                @if($diff > 10)
                </details>
                @endif
                @php
                    $vat = number_format(((float)$rentPerRoom*.12) * $currency_api[$_GET['currency']], 2, '.');
                    $cht = number_format(((float)$rentPerRoom*.02) * $currency_api[$_GET['currency']], 2, '.');
                    $serviceCharge = number_format(((float)$rentPerRoom*.1) * $currency_api[$_GET['currency']], 2, '.');

                    $totalTax = $rentPerRoom*.12 + $rentPerRoom*.02 + $rentPerRoom*.1;
                @endphp
                @if($_GET['currency'] != 'Points')
                    <div class="d-flex justify-content-between fw-bold mt-3">
                        <p>Taxes</p>
                        <p>{{$_GET['currency'].' '.number_format((float)$totalTax * $currency_api[$_GET['currency']], 2, '.')}}</p>
                    </div>
                    <div class="d-flex justify-content-between">
                        <p class="sub">Value Added Tax</p>
                        <p class="sub">{{$_GET['currency'].' '.$vat}}</p>
                    </div>
                    <div class="d-flex justify-content-between">
                        <p class="sub">City Hotel Tax</p>
                        <p class="sub">{{$_GET['currency'].' '.$cht}}</p>
                    </div>
                    <div class="d-flex justify-content-between">
                        <p class="sub">Service Charge</p>
                        <p class="sub">{{$_GET['currency'].' '.$serviceCharge}}</p>
                    </div>
                @endif
                <div class="d-flex justify-content-between fw-bold mt-3">
                    <p>Total Cost for {{($request->rooms < 2)? $request->rooms.' Room' : $request->rooms.' Rooms'}}</p>
                    @if($_GET['currency'] == 'Points')
                        <p>{{$rentPerRoom*$request->rooms}} {{$_GET['currency']}}</p>
                    @else
                        <p>{{$_GET['currency']}} {{($request->rooms < 2)? number_format(((float)$totalTax+$rentPerRoom) * $currency_api[$_GET['currency']], 2, '.') : number_format(((float)($totalTax+$rentPerRoom)*(float)$request->rooms) * $currency_api[$_GET['currency']], 2, '.')}}</p>
                    @endif
                </div>
                @if(Auth::check() && $_GET['currency'] != 'Points')
                    <small class="text-danger">* Earn {{$room_type->points * $diff}} reward points for this booking!</small>
                @endif
            </div>
        </div>
        <div class="col-8 pe-1">
            <div class="shadow-sm bg-light p-2">
                <h4>Reservation Details</h4>
                <div class="d-flex">
                    <div class="col-6">
                        <div id="carousel{{$room_type->id}}" class="carousel slide" data-bs-ride="true">
                            <div class="carousel-indicators">
                            @foreach($room_type->pictures as $index => $picture)
                                <button type="button" data-bs-target="#carousel{{$room_type->id}}" data-bs-slide-to="{{$index}}" {{($index == 0)? "class=active" : ''}} aria-current="true" aria-label="Slide {{$index+1}}"></button>
                            @endforeach
                            </div>
                            <div class="carousel-inner">
                            @foreach($room_type->pictures as $index => $picture)
                                <div class="carousel-item {{($index == 0)? 'active' : ''}}">
                                <img src="{{$picture->picture}}" class="d-block w-100">
                                </div>
                            @endforeach
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carousel{{$room_type->id}}" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carousel{{$room_type->id}}" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                    <div class="col-6 ps-2 pt-1 d-flex flex-column">
                        <h4 class="text-secondary m-0">{{ucwords($room_type->room_name)}}</h4>
                        <div class="my-auto pt-2" style="font-size: 1.1em;">
                            <p>{{date('D, M d, Y', strtotime($request->check_in)).' - '.date('D, M d, Y', strtotime($request->check_out))}}</p>
                            <p>
                            @php 
                                echo $request->rooms;
                                echo ($request->rooms < 2)? ' Room, ' : ' Rooms, ';
                                echo ($request->adults + $request->children)*$request->rooms;
                                echo (($request->adults + $request->children)*$request->rooms < 2)? ' Guest' : ' Guests';
                            @endphp
                            </p>
                            <p>{{($_GET['currency'] != 'Points' && Auth::check() && Auth::user()->member->points >= 500)? 'Member' : (($_GET['currency'] == 'Points')? 'Points' : 'Standard')}} Rate</p>
                        </div>
                        <p class="text-danger border-top pt-1 text-center" style="font-size: 1em"><i class="bi bi-clock"></i>&nbsp; Check-in 14:00&nbsp; | &nbsp;Check-out 12:00</p>
                    </div>
                </div>
            </div>
            <form id="paid" method="POST" action="/add_reservation">
                @csrf
                <input type="hidden" name="room_type_id" value="{{$room_type->id}}">
                <input type="hidden" name="room_count" value="{{$request->rooms}}">
                <input type="hidden" name="adults" value="{{$request->adults}}">
                <input type="hidden" name="children" value="{{$request->children}}">
                <input type="hidden" name="check_in" value="{{$request->check_in}}">
                <input type="hidden" name="check_out" value="{{$request->check_out}}">
                <input type="hidden" name="amount" value="{{($_GET['currency'] == 'Points')? $rentPerRoom : (float)($totalTax+$rentPerRoom)*(float)$request->rooms}}">
                <input type="hidden" name="rate" value="{{($_GET['currency'] != 'Points' && Auth::check() && Auth::user()->member->points >= 500)? 'Member' :(($_GET['currency'] == 'Points')? 'Points' : 'Standard')}}">
                <input type="hidden" name="currency" value="{{$_GET['currency']}}">
                <input type="hidden" name="rent_per_room" value="{{(float)$rentPerRoom/(float)$diff}}">
                @if(!Auth::check()) 
                <div class="mt-4 bg-light mb-2">
                    <div class="shadow-sm p-2 pb-3">
                        <h4>Contact Information</h4>
                        <div class="row mb-0">
                            <div class="col-sm-6 mt-2">
                                <label for="first_name">First Name</label>
                                <input class="form-control" type="text" name="first_name" value="{{old('first_name')}}" required> 
                
                                @error('first_name')
                                    <p>{{$message}}</p>
                                @enderror
                            </div>
                            <div class="col-sm-6 mt-2">
                                <label for="last_name">Last Name</label>
                                <input class="form-control" type="text" name="last_name" value="{{old('last_name')}}" required> 
                
                                @error('last_name')
                                    <p>{{$message}}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-0">
                            <div class="col-sm-6 mt-2">
                                <label for="email">Email</label>
                                <input class="form-control" type="email" name="email" value="{{old('email')}}" required> 
                
                                @error('email')
                                    <p>{{$message}}</p>
                                @enderror
                            </div>
                            <div class="col-sm-3 mt-2">
                                <label for="contact">Contact Number</label>
                                <input class="form-control" type="tel" name="contact" pattern="^(09)\d{9}$" value="{{old('contact')}}" required> 
            
                                @error('contact')
                                    <p>{{$message}}</p>
                                @enderror
                            </div>
                            <div class="col-sm-3 mt-2">
                                <label for="birthday">Birthday</label>
                                <input class="form-control" type="date" name="birthday" value="{{old('birthday')}}" onkeydown="return false" onclick="this.showPicker()" 
                                max="{{date('Y-m-d', strtotime('-18 year'))}}" required> 
            
                                @error('birthday')
                                    <p>{{$message}}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <div class="mt-4 bg-light">
                    <div class="shadow-sm p-2 pb-3">
                        <h4>Payment Information</h4>
                        <div class="row mb-0">
                            <div class="col-sm-5 mt-2">
                                <label for="card-num">Card Number</label>
                                <div class="position-relative d-flex">
                                <input class="form-control card-num" type="text" name="card-num" placeholder="0000 0000 0000 0000" maxlength="19" value="{{old('card-num')}}" required> 
                                <input type="hidden" class="payment_method" name="payment_method">
                                <i class="icon card-icon fa fa-credit-card position-absolute align-self-center"></i>
                                <script>
                                    const inputCard = document.querySelector('.card-num');
                                    inputCard.addEventListener('keyup',()=>{
                                        if(inputCard.value.charAt(0) == 4){
                                            document.querySelector('.card-icon').className = "icon card-icon fa-brands fa-cc-visa position-absolute align-self-center";
                                            document.querySelector('.payment_method').value = "Visa";
                                        }
                                        else if(inputCard.value.charAt(0) == 5){
                                            document.querySelector('.card-icon').className = "icon card-icon fa-brands fa-cc-mastercard position-absolute align-self-center";
                                            document.querySelector('.payment_method').value = "Mastercard";
                                        }
                                        else{
                                            document.querySelector('.card-icon').className = "icon card-icon fa fa-credit-card position-absolute align-self-center";
                                            document.querySelector('.payment_method').value = "Credit Card";
                                        }
                                        let txt = inputCard.value.replace(/\D/g, '');
                                        let newtxt = '';
                                        for (let i = 0; i < Math.min(txt.length, 16); i++) {
                                            newtxt += txt[i];
                                            if (i == 3 || i == 7 || i == 11) {
                                            newtxt += ' ';
                                            }
                                        }
                                        if (newtxt[newtxt.length - 1] == ' ') newtxt = newtxt.substring(0, newtxt.length - 1);
                                        inputCard.value = newtxt;
                                        
                                        if(inputCard.value.length < 19)
                                            inputCard.setCustomValidity('Card number should have 16 digits.')
                                        else 
                                            inputCard.setCustomValidity('');
                                    });
                                </script>
                                </div>
                            </div>
                            <div class="col-sm-4 mt-2">
                                <label for="expiry">Expiry Date</label>
                                <input class="form-control" type="month" name="expiry" value="{{(old('expiry') != null)? old('expiry') : date("Y").'-'.date('m')}}" onkeydown="return false" onclick="this.showPicker()" required> 
                            </div>
                            <div class="col-sm-3 mt-2">
                                <label for="cvv">CVC/CVV</label>
                                <div class="position-relative d-flex">
                                    <input class="form-control" type="password" pattern="[0-9]*" inputmode="numeric" name="cvv" placeholder="000" minlength="3" maxlength="4" value="{{old('cvv')}}" required> 
                                    <i class="icon bi bi-shield-shaded position-absolute align-self-center"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4 bg-light">
                    <div class="shadow-sm p-2 pb-3">
                        <h4>Special Requests</h4>
                        <textarea class="form-control" rows="3" name="requests">{{old('requests')}}</textarea>
                        <p class="sub">Your message will be sent to the hotel when your booking is complete. We cannot guarantee that your requests will be met.</p>
                    </div>
                </div>
                <div class="mt-4 bg-light">
                    <div class="shadow-sm p-2 pb-3">
                        <h4>Terms & Conditions</h4>
                        <p>Cancellation Policy</p>
                        <p class="sub">The guest may cancel the reservation 48 hours before arrival, and it will be free of charge. In the event of a cancellation within 48 hours before arrival, the hotel will charge 50% of the rate availed.</p>
                        <p class="sub mt-3"><input type="checkbox" required> I accept the cancellation policy. I have read and acknowledge the use of my personal information in accordance with the PUPSJ Hotel Privacy Policy.</p>
                    </div>
                </div>
                <div class="mt-4 bg-light">
                    <button type="submit" class="book-btn btn btn-warning rounded-0 w-50">BOOK MY STAY</button>
                </div>
            </form>
        </div>
    </div>
</section>
@include('home.navbar-script')
<script src="admin/assets/js/jquery-3.5.1.min.js"></script>
<script src="admin/assets/js/bootstrap.min.js"></script>
</body>
</html>