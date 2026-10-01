<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rooms</title>
    @include('home.css')
    @include('home.rooms.css')
    <style>
        .state-header a {
            font-family: initial;
            width: auto !important;
            text-decoration: none;
            margin: 0 5vw;
            position: relative;
            color: var(--gray);
        }
        .state-header a:nth-child(1)::after{
            position: absolute;
            content: '';
            background: var(--gray);
            width: 100%;
            height: 2px;
            right: 0;
            bottom: 0;
        }
        .state-header a:not(:nth-child(1)),.state-header span:nth-child(4){
            color: lightgray;
        }
        p {
            margin: 0;
        }
        div.text-center {
            margin: 0 5px;
        }
        .bg {
            background: rgba(0,0,0, 55%);
            display: none;
        }
        .box {
            animation: slideInDown;
            animation-duration: 0.7s;
        }
        .showMember {
            display: flex;
        }
        .quantity .input-number {
            width: 2.6em;
        }
        .quantity .input-number-decrement, .quantity .input-number-increment {
            padding: 0 18px;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
</head>
<body>
@include('home.navbar')
<section style="margin-top: 60px">
    <div class="container state-header pt-4 d-flex justify-content-center border-bottom">
        <a class="col-4">Choose Room</a><span>→</span>
        <a class="col-4 pb-1">Enter Details and Payment</a><span>→</span>
        <a class="col-4">View Confirmation</a>
    </div>
</section>
<form id="details-form" method="POST" action="/details?currency={{$_GET['currency']}}">
    @csrf
    <input type="hidden" class="room-type-id" name="room_type_id">
    <input type="hidden" class="check-in" name="check_in">
    <input type="hidden" class="check-out" name="check_out">
    <input type="hidden" class="rooms" name="rooms">
    <input type="hidden" class="adults" name="adults">
    <input type="hidden" class="children" name="children">  
    <input type="hidden" class="rent" name="rent">  
</form>
<section class="mt-1">
    <div class="container bg-secondary text-light py-2">
        <div id="details" class="d-flex align-items-center">
            <p class="d-flex align-items-center"><i class="ri-calendar-line"></i>&nbsp;<span id="date-details"></span></p><span class="mx-2">┃</span>
            <p class="d-flex align-items-center"><i class="ri-door-closed-line" style="font-size: 1.2em;"></i>&nbsp;@php echo $_GET["rooms"];echo ($_GET["rooms"] < 2)? ' Room' : ' Rooms';@endphp</p><span class="mx-2">┃</span>
            <p  class="d-flex align-items-center"><i class="fa-regular fa-user" style="font-size: .85em;margin-right: 7px;"></i>@php echo ($_GET["adults"] + $_GET["children"])*$_GET["rooms"];echo (($_GET["adults"] + $_GET["children"])*$_GET["rooms"] < 2)? ' Guest' : ' Guests';@endphp</p><span class="mx-2">┃</span>
            <p><i class="fa-solid fa-coins"></i>&nbsp; <input type="checkbox" class="clickable" onclick="usePoints(this.checked)" name="points" style="accent-color: lightgray; transform: scale(1.2);" {{($_GET['currency'] == 'Points')?'checked':''}}> Use Points</p>
                <script>
                    function usePoints(checked){
                        let params = new URLSearchParams(new URL(window.location.href).search);
                        if(checked)
                            params.set('currency', 'Points'); 
                        else
                            params.set('currency', 'PHP'); 
                        window.location.href = window.location.origin + window.location.pathname+'?'+ params.toString();
                    }
                </script>
            <button id="edit-details" type="button" class="btn btn-sm btn-outline-light rounded-0 ms-auto px-3">EDIT DETAILS</button>
        </div>
        <div id="input-details" class="d-none" style="width: fit-content">
            <div class="text-end pe-1">
                <span id="input-details-exit" style="cursor: pointer;">⨉</span>
            </div>
            <p id="date-warn" class="d-none">● Can't book dates in the past.</p>
            <form action="" method="get" class="d-flex px-3 pb-3">
                    <div class="text-center">
                        <label class="d-block" for="check_in">Check-in:</label>
                        <input type="date" id="check-in" class="text-center py-1" name="check_in" value="@php if(isset($_GET["check_in"]))echo $_GET["check_in"]; else date('Y-m-d'); @endphp" onkeydown="return false" onclick="this.showPicker()"/>
                    </div>
                    <div class="text-center">
                        <label class="d-block" for="check_out">Check-out:</label>
                        <input type="date" id="check-out" class="text-center py-1" name="check_out" value="@php if(isset($_GET["check_out"]))echo $_GET["check_out"]; else date('Y-m-d', strtotime('+1 day')); @endphp" onkeydown="return false" onclick="this.showPicker()"/>
                    </div>
                    <div class="rooms text-center">
                        <label for="rooms">Rooms:</label>
                        <span class="quantity d-flex">
                            <span class="input-number-decrement" id="input-number-decrement">–</span>
                            <input class="input-number" id="input-rooms" name="rooms" type="number" value="@php if(isset($_GET["rooms"]) && is_numeric($_GET["rooms"]))echo $_GET["rooms"]; else echo '1'; @endphp" min="1" required>
                            <span class="input-number-increment" id="input-number-increment">+</span>
                        </span>
                    </div>
                    <div class="adults text-center">
                        <label for="adults">Adults per room:</label>
                        <span class="quantity d-flex">
                            <span class="input-number-decrement" id="input-number-decrement">–</span>
                            <input class="input-number" id="input-adults" name="adults" type="number" value="@php if(isset($_GET["adults"]) && is_numeric($_GET["adults"]))echo $_GET["adults"]; else echo '1'; @endphp" min="1" required>
                            <span class="input-number-increment" id="input-number-increment">+</span>
                        </span>
                    </div>
                    <div class="children text-center">
                        <label for="children">Children:</label>
                        <span class="quantity d-flex">
                            <span class="input-number-decrement" id="input-number-decrement">–</span>
                            <input class="input-number" id="input-children" name="children" type="number" value="@php if(isset($_GET["children"]) && is_numeric($_GET["children"]))echo $_GET["children"]; else echo '0'; @endphp" min="0" required>
                            <span class="input-number-increment" id="input-number-increment">+</span>
                        </span>
                    </div>
                    <input type="hidden" name="currency" value="{{$_GET['currency']}}">
                    <button type="submit" class="btn btn-warning py-1 rounded-0 align-self-end ms-1">UPDATE</button>
                </div>
            </form>
        </div>
    </div>
</section>
<section class="rooms-section">
    <div class="header row container mx-auto px-0">
        <div class="col-6 d-flex justify-content-end">
            <a id="rooms-cta">Rooms</a>
        </div>
        <div class="col-6 d-flex justify-content-between pe-0">
            <a id="suites-cta">Suites</a>
            <a class="d-flex align-items-center">
                <i class="fa-solid fa-coins fs-5 text ms-2 me-1"></i>
                <h5 class="m-0">Currency:&nbsp;</h5>
                <select class="form-select clickable" style="width: auto" onchange="changeCurrency(this.value)">
                    @php 
                        $response_json = file_get_contents('https://api.exchangerate-api.com/v4/latest/PHP');
                        if(false !== $response_json) {
                            try {
                                $currency_api = (array) json_decode($response_json)->rates;
                                ksort($currency_api);
                            }
                            catch(Exception $e) {
                            }
                        }  
                        if($_GET['currency'] == 'Points')
                            $currency_api = array("Points" => 1) + $currency_api; 
                        foreach ($currency_api as $key => $value){
                            echo ($key == $_GET['currency'])?'<option selected>':'<option>';
                            echo $key.'</option>';
                        }
                    @endphp
                </select>
                <script>
                    function changeCurrency(selectedCurrency) {
                        let params = new URLSearchParams(new URL(window.location.href).search);
                        params.delete('currency');
                        window.location.href = window.location.origin + window.location.pathname+'?'+params.toString()+'&currency='+selectedCurrency;
                    }
                </script>
            </a>
        </div>
    </div>
    <div class="container-fluid" style="background: ghostwhite;">
        @php
            if(isset($_GET["check_in"],$_GET["check_out"])){
                $room_types = $room_types->filter(function ($room_type) use ($reservations) {
                    $booked = 0;
                    $reservations = $reservations->filter(function ($item) {
                        if($item->status != 'Cancelled'){
                            return 
                            $_GET["check_in"] >= date('Y-m-d', strtotime($item->check_in)) && $_GET["check_out"] <= date('Y-m-d', strtotime($item->check_out)) ||
                            date('Y-m-d', strtotime($item->check_in)) >= $_GET["check_in"] && date('Y-m-d', strtotime($item->check_out)) <= $_GET["check_out"];
                        }
                    })->values();
                    foreach ($reservations as $reservation) {
                        if($reservation->room_type == $room_type->room_name){
                            $booked += 1;
                        }
                    }
                    return $room_type->rooms->where('status', 'Open')->count()-$booked >= $_GET["rooms"]; 
                })->values();
            }
            if(isset($_GET["adults"],$_GET["children"])){
                $room_types = $room_types->filter(function ($item) {
                        return $item->max_occupancy >= ($_GET["adults"] + $_GET["children"]); 
                })->values();
            }
        @endphp
        <div id="rooms" class="container border-top py-3 mb-5">
            @php $empty = true; @endphp
            @foreach ($room_types as $room_type)
                @if(!str_contains(strtolower($room_type->room_name), 'suite') && $room_type->status == 'Active')
                    @php $empty = false; @endphp
                    @include('home.reservation.room')
                @endif
            @endforeach
            @if($empty == true)
                <h4 class="text-center mt-5 text-secondary">No rooms available for your selected details.</h4>
            @endif
        </div>
        <div id="suites" class="container border-top py-3 mb-5">
            @php $empty = true; @endphp
            @foreach ($room_types as $room_type)
                @if(str_contains(strtolower($room_type->room_name), 'suite') && $room_type->status == 'Active')
                    @php $empty = false; @endphp
                    @include('home.reservation.room')
                @endif
            @endforeach
            @if($empty == true)
                <h4 class="text-center mt-5 text-secondary">No suites available for your selected details.</h4>
            @endif
        </div>
    </div>
    </div>
</section>
<div id="member-bg" class="bg container-fluid vh-100 fixed-top align-items-center">
    <div id="member-box" class="box w-auto bg-light p-0 mx-auto">
        <h4 class="text-end pe-1 m-0">
            <span id="member-exit" style="cursor: pointer;">⨉</span>
        </h4>
        <div class="text-center px-3 pb-2">
            <h1 class="m-0 text-danger">Membership Required</h1>
            <h6 class="text-secondary">Join <span class="text-warning">PUPSJ Hotel</span> or <span class="text-warning">Sign In</span> on your account to avail using points.</h6>
            <div class="d-flex mt-3 mb-2">
                <div class="w-50 p-2 d-grid">
                    <h5>Want to become a member?</h5>
                    <a href="/register" class="btn btn-outline-primary rounded-0 w-100 mt-auto bg-light text-primary">JOIN TO BOOK</a>
                </div>
                <div class="w-50 p-2 d-grid border-start border-secondary">
                    <h5>Already a member?</h5>
                    <a href="/login" class="btn btn-warning rounded-0 w-100 mt-auto">SIGN IN TO BOOK</a>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-center">
                <i class="bi bi-chevron-left text-secondary"></i><a id="member-back" href="#" style="font-size:.9em;font-family: initial;text-decoration:none;padding-bottom:1px;">Maybe later. I'll choose another rate.</a>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="warningModal" aria-hidden="true">
  <div class="modal-dialog my-0 h-100 d-flex">
    <div class="modal-content rounded-0 my-auto">
      <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
      <div class="modal-body p-2 text-center">
        <h5><span class="text-warning">Unfortunately,</span> you do <span class="text-danger">not</span> have enough points to redeem this award.</h5>
        <button class="btn btn-primary w-100 mt-2 rounded-0" data-bs-dismiss="modal" aria-label="Close">VIEW OTHER RATES</button>
      </div>
    </div>
  </div>
</div> 
@include('home.navbar-script')
@include('home.calendar-script')
@include('home.quantity-script')
@include('home.rooms.script')
<script src="/home/moment.js"></script>
<script>
const editDetails = document.getElementById('edit-details')
    inputDetails = document.getElementById('input-details')
    inputDetailsExit = document.getElementById('input-details-exit')
    details = document.getElementById('details');

    editDetails.addEventListener('click', function(){
        details.className = "d-none";
        inputDetails.className = "d-grid bg-light text-dark mx-auto my-2";
    });
    inputDetailsExit.addEventListener('click', function(){
        details.className = "d-flex align-items-center";
        inputDetails.className = "d-none";
    });

const inputRooms = document.getElementById('input-rooms')
    inputAdults = document.getElementById('input-adults')
    inputChildren = document.getElementById('input-children');   

    checkInValue = checkIn.value;
    checkOutValue = checkOut.value;
    RoomsValue = inputRooms.value;
    AdultsValue = inputAdults.value;
    ChildrenValue = inputChildren.value;  

    document.getElementById('date-details').innerHTML = moment(checkInValue).format('ddd'+ ', ' +'ll') + " - " + moment(checkOutValue).format('ddd'+ ', ' +'ll');
    function showDateWarn(){
        document.getElementById('date-warn').className = "d-flex text-danger ps-3";
        details.className = "d-none";
        inputDetails.className = "d-grid bg-light text-dark mx-auto my-2";
    }
    if(checkInValue < "{{date('Y-m-d')}}" || checkOutValue < "{{date('Y-m-d', strtotime('+1 day'))}}"){
        showDateWarn();
    }

    const memberBg = document.getElementById('member-bg')
      memberBox = document.getElementById('member-box')
      memberBack = document.getElementById('member-back')
      memberExit = document.getElementById('member-exit');
      
      function removeShow(){
        memberBg.classList.remove('showMember'); 
      }

      memberBack.addEventListener('click', removeShow);
      memberExit.addEventListener('click', removeShow);
      document.addEventListener('mouseup', function(e) {
        if (!memberBox.contains(e.target)) {
            removeShow(); 
        }
      });
    function toDetails(id,rent) {
        let nights = Math.abs(moment(checkInValue, "YYYY-MM-DD").diff(moment(checkOutValue, "YYYY-MM-DD"), 'days'));
        if(checkInValue < "{{date('Y-m-d')}}" || checkOutValue < "{{date('Y-m-d', strtotime('+1 day'))}}"){
            location.href = "#";
            showDateWarn();
        }
        else {
            if({{($_GET['currency'] == 'Points')? 'true' : 'false'}} && {{!Auth::check() ? 'true' : 'false'}})
                memberBg.classList.add('showMember'); 
            @if(Auth::check())
            else if({{($_GET['currency'] == 'Points')? 'true' : 'false'}} && rent*{{$_GET['rooms']}}*nights > {{Auth::user()->member->points}}){
                var warningModal = new bootstrap.Modal(document.getElementById('warningModal'))
                warningModal.show()
            }
            @endif
            else
                detailsForm();
                 
        }
        
        function detailsForm(){
            const detailsForm = document.getElementById('details-form');

            detailsForm.querySelector('.room-type-id').value = id;
            detailsForm.querySelector('.check-in').value = checkInValue;
            detailsForm.querySelector('.check-out').value = checkOutValue;
            detailsForm.querySelector('.rooms').value = RoomsValue;
            detailsForm.querySelector('.adults').value = AdultsValue;
            detailsForm.querySelector('.children').value = ChildrenValue;            
            detailsForm.querySelector('.rent').value = rent;      

            detailsForm.submit();
        }
    }
</script>
</body>
</html>
