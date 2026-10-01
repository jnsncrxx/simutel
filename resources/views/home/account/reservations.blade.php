<style>
    .reservation-tabs a{
        text-decoration: none;
        color: black;
        cursor: pointer;
        background: lightgray;
        border: 1px solid darkgray;
        border-bottom: 1px solid gray;
    } 
    .reservation-tabs .active{
        border: 1px solid gray;
        border-bottom: 0; 
        cursor: default;
        background: white;
    } 
    h1,h2,h3,h4,h5,h6, p {
        font-family: initial;
        margin: 0;
    }
</style>
<meta name="csrf-token" content="{{ csrf_token() }}" />
<div class="container mt-3 p-0">
    <div class="reservation-tabs d-flex text-center">
        <a @if($_GET['reservations'] != 'upcoming') href="/account?tab=reservations&reservations=upcoming" @endif class="col-4 py-1 @if($_GET['reservations'] == 'upcoming') active @endif">Upcoming Reservations</a>
        <a @if($_GET['reservations'] != 'past') href="/account?tab=reservations&reservations=past" @endif class="col-4 py-1 @if($_GET['reservations'] == 'past') active @endif">Past Reservations</a>
        <a @if($_GET['reservations'] != 'cancelled') href="/account?tab=reservations&reservations=cancelled" @endif class="col-4 py-1 @if($_GET['reservations'] == 'cancelled') active @endif">Cancelled Reservations</a>
    </div>
    <div class="px-3 py-1" style="border: 1px solid gray; border-top:0">
        @php
            if($_GET['reservations'] == 'upcoming'){
                $reservations = $reservations->filter(function ($reservation) {
                        return $reservation->status == 'Confirmed' || $reservation->status == 'Due In';
                })->values()->sortBy('check_in');  
            } 
            else if($_GET['reservations'] == 'past'){
                $reservations = $reservations->filter(function ($reservation) {
                        return $reservation->status == 'Checked-out' || $reservation->status == 'No Show';
                })->values();  
            } 
            else if($_GET['reservations'] == 'cancelled'){
                $reservations = $reservations->filter(function ($reservation) {
                        return $reservation->status == 'Cancelled';
                })->values()->sortByDesc('updated_at');  
            } 
        @endphp
        @if($reservations->count() == 0)
        <div class="text-center my-5 py-5">
            <h4>Your account doesn't have any {{$_GET['reservations']}} bookings.</h4>
            <h4 class="my-4">To make reservations, click <span><a href="/choose?check_in={{date('Y-m-d')}}&check_out={{date('Y-m-d', strtotime('+1 day'))}}&rooms=1&adults=1&children=0&currency=PHP" style="color: var(--gold)">Book Now</a></span>.</h4>
        </div>
        @endif
        @foreach ($reservations as $reservation)
            @php 
                $room_type = $room_types->where('room_name', $reservation->room_type)->first();
                $rent = $room_type->rent;
                $adults = $reservation->adults;
                $extra_adults = $adults-$room_type->default_occupancy;
                if($adults>$room_type->default_occupancy){
                    $rent = $rent + ($room_type->extra_adult*$extra_adults);
                }
                $diff = date_diff(date_create($reservation->check_in), date_create($reservation->check_out))->format("%a");
                if($reservation->rate == 'Standard') 
                    $rentPerRoom = $diff*($rent+$rent*.2);
                else
                    $rentPerRoom = $diff*$rent;
                $vat = number_format((float)$rentPerRoom*.12, 2, '.');
                $cht = number_format((float)$rentPerRoom*.02, 2, '.');
                $serviceCharge = number_format((float)$rentPerRoom*.1, 2, '.');
                $totalTax = $rentPerRoom*.12 + $rentPerRoom*.02 + $rentPerRoom*.1;
            @endphp
            <div class="container border p-0 my-3">
                <div class="preview d-flex p-3">
                    <div class="col-6">
                        <h6>Confirmation #: {{'PUPSJ-'.str_pad($reservation->id,6,"0")}}</h6>
                        <h6>{{$reservation->room_type}}</h6>
                        <h6>{{$reservation->adults + $reservation->children}} {{($reservation->adults + $reservation->children < 2)? ' Guest' : ' Guests'}}, {{$diff}} {{($diff < 2)? ' Night' : ' Nights'}}</h6>
                        <h6>{{date('D, M d, Y', strtotime($reservation->check_in)).' - '.date('D, M d, Y', strtotime($reservation->check_out))}}</h6>
                    </div>
                    <div class="col-6 d-flex">
                        <button type="button" class="btn view btn-warning rounded-0 ms-auto my-auto" style="height: fit-content">View Details</button>
                    </div>
                </div>
                <div class="details d-none p-2">
                    <div class="col-12">
                        <form action="/download_pdf" method="post" target="_blank" class="d-flex justify-content-between">
                            <span class="clickable close"><p>← Return</p></span>
                            @if($_GET['reservations'] == 'upcoming') 
                                    @csrf
                                    <input type="hidden" name="id" value="{{$reservation->id}}">
                                    <input type="hidden" name="rent_per_room" value="{{$rentPerRoom/$diff}}">
                                    <input type="hidden" name="currency" value="PHP">
                                    <span class="clickable"><a onclick="this.closest('form').submit()"><u>Print Confirmation</u></a></span> 
                            @else 
                                <span><b>Reservation Status: {{$reservation->status}}</b></span> 
                            @endif
                        </form>
                        <h5 class="border border-secondary text-center my-2 py-1" style="font-family: initial">Confirmation Number: #{{'PUPSJ-'.str_pad($reservation->id,6,"0")}}</h5>
                    </div>
                    <div class="d-flex my-2">
                        <div class="col-6 pe-4">
                            <div>
                                <p class="ps-3 py-1" style="background: lightgray"><b>Reservation Details</b></p>
                                <div class="my-2 px-3">
                                    <p class="d-flex justify-content-between py-1">
                                        <span>Check-in:</span>
                                        <span class="text-end">{{date('D, M d, Y', strtotime($reservation->check_in))}}<br><span>2:00 PM</span></span>
                                    </p>
                                    <p class="d-flex justify-content-between py-1">
                                        <span>Check-out:</span>
                                        <span class="text-end">{{date('D, M d, Y', strtotime($reservation->check_out))}}<br><span>12:00 PM</span></span>
                                    </p>
                                    <p class="d-flex justify-content-between py-1">
                                        <span>No. of Guests:</span>
                                        <span>{{$reservation->adults}} {{($reservation->adults < 2)? ' Adult' : ' Adults'}}@if($reservation->children > 0), {{$reservation->children}} {{($reservation->children < 2)? ' Child' : ' Children'}}@endif</span>
                                    </p>
                                    <p class="d-flex justify-content-between py-1">
                                        <span>Room Type:</span>
                                        <span>{{ucwords($reservation->room_type)}}</span>
                                    </p>
                                    <p class="d-flex justify-content-between py-1">
                                        <span>Rate:</span>
                                        <span>{{ucwords($reservation->rate.' Rate')}}</span>
                                    </p>
                                    @if($_GET['reservations'] == 'upcoming' && $reservation->status != 'Due In')
                                        <div class="mt-2 text-end">
                                            <button type="button" onclick="modifyModal({{$reservation->id}},'{{$reservation->check_in}}','{{$reservation->check_out}}', '{{ucwords($reservation->room_type)}}', {{$reservation->adults}}, {{$reservation->children}}, {{$rent}}, '{{ucwords($reservation->rate.' Rate')}}', '{{$reservation->reserved_by->email}}')" class="btn btn-secondary rounded-0 py-0" style="height: fit-content" data-bs-toggle="modal" data-bs-target="#modifyModal">Modify Dates</button>
                                        </div>
                                    @endif
                                </div> 
                            </div>
                            <div class="pt-2">
                                <p class="ps-3 py-1" style="background: lightgray"><b>Amount Breakdown</b></p>
                                <div class="my-2 px-3">
                                    <p class="py-1 d-flex justify-content-between border-bottom border-secondary">
                                        <span>Subtotal:</span>
                                        <span>PHP {{number_format((float)$rentPerRoom, 2, '.')}}</span>
                                    </p>
                                    <p class="py-1 d-flex justify-content-between border-bottom border-secondary">
                                        <span>Total Taxes/Fees:</span>
                                        <span>PHP {{number_format((float)$totalTax, 2, '.')}}</span>
                                    </p>
                                    <p class="py-1 d-flex justify-content-between border-bottom border-secondary" style="font-weight: bold">
                                            <span>Total Amount:</span>
                                            <span>PHP {{number_format((float)$reservation->amount, 2, '.')}}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 ps-4 d-grid">
                            <div>
                                <p class="ps-3 py-1" style="background: lightgray"><b>Guest Details</b></p>
                                <div class="my-2 px-3">
                                    <p class="d-flex justify-content-between py-1">
                                        <span>Name:</span>
                                        <span>{{ucwords($reservation->reserved_by->first_name).' '.ucwords($reservation->reserved_by->last_name)}}</span>
                                    </p>
                                    <p class="d-flex justify-content-between py-1">
                                        <span>Email:</span>
                                        <span>{{$reservation->reserved_by->email}}</span>
                                    </p>
                                    <p class="d-flex justify-content-between py-1">
                                        <span>Contact Number:</span>
                                        <span>{{$reservation->reserved_by->contact}}</span>
                                    </p>
                                </div>
                                <div class="pt-2">
                                    <p class="ps-3 py-1" style="background: lightgray"><b>Special Requests</b></p>
                                    <div class="my-2 px-3">
                                       <p>{{$reservation->requests}}</p>
                                    </div>
                                </div>
                            </div>
                            @if($_GET['reservations'] == 'upcoming') 
                            <div class="pt-2 mt-auto">
                                <p class="ps-2 py-1 border-bottom">Terms and Conditions</p>
                                <div class="my-2 px-3">
                                   <small>Cancellation Policy: The guest may cancel the reservation 48 hours before arrival to avoid 50% charge of the rate availed. No-show guests will be charged the total amount of the reservation.</small>
                                </div>
                            </div>
                            @endif
                            @if($_GET['reservations'] == 'cancelled') 
                            <div class="pt-2 mt-auto">
                                <p class="ps-3 py-1" style="background: lightgray"><b>Cancellation Details</b></p>
                                <div class="my-2 px-3">
                                    <p class="d-flex justify-content-between py-1">
                                        <span>Cancellation Date:</span>
                                        <span>{{date('D, M d, Y h:i A', strtotime($reservation->cancellations->created_at))}}</span>
                                    </p>
                                    <p class="d-flex justify-content-between py-1">
                                        <span>Reason:</span>
                                        <span>{{$reservation->cancellations->reason}}</span>
                                    </p>
                                    <p class="d-flex justify-content-between py-1">
                                        <span>Amount Charged:</span>
                                        <span>{{($reservation->cancellations->amount_charge > 0)?'PHP '.number_format((float)$reservation->cancellations->amount_charge, 2, '.'): 'Free of charge'}}</span>
                                    </p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @if($_GET['reservations'] == 'upcoming')
                        <div class="col-12 text-end">
                            <button type="button" onclick="cancelModal({{$reservation->id}},'{{'PUPSJ-'.str_pad($reservation->id,6,'0')}}')" class="btn btn-secondary rounded-0 py-0" style="height: fit-content" data-bs-toggle="modal" data-bs-target="#cancelModal">Cancel Reservation</button>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
<div class="modal fade" id="modifyModal">
    <div class="modal-dialog modal-dialog-centered" style="max-width: min-content;">
      <div class="modal-content px-2" style="width: fit-content;">
        <div class="modal-header px-1 py-2">
          <small>Check availability to modify your dates.</small>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body px-0" style="width: fit-content;">
            <div class="d-flex px-3 pb-3 mx-auto" style="width: max-content;">
                <div class="text-center">
                    <label class="d-block" for="check_in">Check-in:</label>
                    <input type="date" id="check-in" class="text-center py-1" onkeydown="return false" onclick="this.showPicker()"/>
                </div>
                <div class="text-center mx-2">
                    <label class="d-block" for="check_out">Check-out:</label>
                    <input type="date" id="check-out" class="text-center py-1" onkeydown="return false" onclick="this.showPicker()"/>
                </div>
                <button type="button" onclick="checkAvailable()" class="btn btn-warning py-1 rounded-0 align-self-end">Check Availability</button>
            </div>
            <p class="text-danger d-none text-center">Room is not available on this dates.</p>
            <div id="review-changes" class="border p-2 d-none">
                <h5 class="text-center pb-2">Review and confirm your changes</h5>
                <div class="d-flex">
                    <div class="col-6 pe-2" style="width: max-content;">
                        <p class="ps-2 py-1" style="background: lightgray"><b>Reservation Details</b></p>
                        <div class="my-2 px-2">
                            <p class="d-flex justify-content-between py-1">
                                <span class="pe-2">Check-in:</span>
                                <span class="text-end"><span class="check-in"></span><br><span>2:00 PM</span></span>
                            </p>
                            <p class="d-flex justify-content-between py-1">
                                <span class="pe-2">Check-out:</span>
                                <span class="text-end"><span class="check-out"></span><br><span>12:00 PM</span></span>
                            </p>
                            <p class="d-flex justify-content-between py-1">
                                <span class="pe-2">No. of Guests:</span>
                                <span class="guests"></span>
                            </p>
                            <p class="d-flex justify-content-between py-1">
                                <span class="pe-2">Room Type:</span>
                                <span class="room-type"></span>
                            </p>
                            <p class="d-flex justify-content-between py-1">
                                <span class="pe-2">Rate:</span>
                                <span class="rate"></span>
                            </p>
                        </div> 
                    </div>
                    <div class="col-6 ps-2" style="width: max-content;">
                        <p class="ps-2 py-1" style="background: lightgray"><b>Amount Breakdown</b></p>
                        <div class="my-2 px-2">
                            <p class="py-1 d-flex justify-content-between border-bottom border-secondary">
                                <span class="pe-2">Subtotal:</span>
                                <span class="subtotal"></span>
                                <div class="p-2 pe-0 night-price"></div>
                            </p>
                            <p class="py-1 d-flex justify-content-between border-bottom border-secondary">
                                <span class="pe-2">Total Taxes/Fees:</span>
                                <span class="total-tax">PHP 144,000.00</span>
                                <div class="px-2 pt-2">
                                    <p style="text-align:right; margin: 0">
                                        <small><span class="vat"></span><span style="float:left;">Value Added Tax</span></small>
                                    </p>
                                </div>
                                <div class="px-2 py-0">
                                    <p style="text-align:right; margin: 0">
                                        <small><span class="cht"></span><span style="float:left;">City Hotel Tax</span></small>
                                    </p>
                                </div>
                                <div class="px-2 pb-1">
                                    <p style="text-align:right; margin: 0">
                                        <small><span class="service-charge"></span><span style="float:left;">Service Charge</span></small>
                                    </p>
                                </div>
                            </p>
                            <p class="py-1 d-flex justify-content-between border-bottom border-secondary" style="font-weight: bold">
                                <span class="pe-2">Total Amount:</span>
                                <span class="total-amount"></span>
                            </p>
                        </div>
                    </div>
                </div>
                <button type="button" onclick="modify()" class="btn btn-secondary rounded-0 py-0" style="height: fit-content">Confirm Changes</button>
            </div>
        </div>
        <div class="modal-footer justify-content-center text-secondary">
          <h6 style="font-family: initial;"><i class="fa-solid fa-bed"></i> <span class="room_type"></span> ┃ <i class="fa-regular fa-user"></i> <span class="guests_count"></span></h6>
        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="modifiedModal">
    <div class="modal-dialog modal-dialog-centered" style="max-width: fit-content;">
      <div class="modal-content px-2">
            <div class="modal-header px-1 py-2">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-1 text-center">
                <i class="fa-regular fs-1 my-2 fa-circle-check" style="color: var(--gold)"></i>
                <h4 class="text-center">Your reservation has been updated successfully!</h4>
                <p class="text-center pt-2">A confirmation email has been sent to <span class="email"></span></p>
            </div>
      </div>
    </div>
</div>
<div class="modal fade" id="cancelModal">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content px-2">
            <div class="modal-header px-1 py-2">
            <small style="font-family: initial">Confirmation Number: #<span class="conf-no"></span></small>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-1">
                <h3 class="text-center" style="color: var(--gold)">Cancel Reservation</h3>
                <h5 class="text-center py-2">Are you sure you want to cancel this reservation?</h5>
                <p>Cancel Reason:</p>
                <textarea class="form-control h-auto fs-6 reason" rows="3" style="font-family: initial"></textarea>
                <div class="mt-3" style="line-height: 1em;"><small><b>Cancellation Policy:</b> Cancel 48 hours before arrival or you will be charged 50% of the rate availed.</small></div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" onclick="cancel()" class="btn btn-warning py-1 rounded-0 align-self-end">Cancel my reservation</button>
                <button type="button" class="btn btn-outline-secondary py-1 rounded-0 align-self-end" data-bs-dismiss="modal">Do not cancel</button>
            </div>
      </div>
    </div>
</div>
<div class="modal fade" id="cancelledModal">
    <div class="modal-dialog modal-dialog-centered" style="max-width: fit-content;">
      <div class="modal-content px-2">
            <div class="modal-header px-1 py-2">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-1">
                <h4 class="text-center" style="color: var(--gold)">Your reservation has been cancelled successfully.</h4>
                <h5 class="text-center py-2" style="font-family: initial">Confirmation Number: #<span class="conf-no"></span></h5>
                <p class="text-center pt-2">Would you like to book again?</p>
            </div>
            <div class="modal-footer justify-content-center">
                <a href="/choose?check_in={{date('Y-m-d')}}&check_out={{date('Y-m-d', strtotime('+1 day'))}}&rooms=1&adults=1&children=0&currency=PHP" class="btn btn-warning py-1 rounded-0 align-self-end">Book again</a>
            </div>
      </div>
    </div>
</div>
<script src="/home/moment.js"></script>
@include('home.calendar-script')
<script src="admin/assets/js/jquery-3.5.1.min.js"></script>
<script>
    let id, room_type, adults, children, room_rent, rate, totalAmount, email;

    function modifyModal(reservation_id, checkInDate, checkOutDate, roomType, adultsCount, childrenCount, rent, room_rate, guestEmail) {
        document.getElementById('check-in').value = moment(checkInDate).format('y-MM-DD');
        document.getElementById('check-out').value = moment(checkOutDate).format('y-MM-DD');
        document.getElementById('modifyModal').querySelector('.room_type').innerHTML = roomType;
        let guests = adultsCount+childrenCount;
        document.getElementById('modifyModal').querySelector('.guests_count').innerHTML = (guests>1)?guests+' Guests':guests+' Guest';
        id = reservation_id;
        room_type = roomType;
        adults = adultsCount;
        children = childrenCount;
        room_rent = rent;
        rate = room_rate;
        email = guestEmail;
        document.getElementById('modifyModal').querySelector('.text-danger').className = "text-danger d-none text-center";
        document.getElementById('modifyModal').querySelector('#review-changes').className = "border p-2 d-none";
    }
    
    function checkAvailable() {
        const checkIn = document.getElementById('check-in')
              checkOut = document.getElementById('check-out');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            async: false,
            type: "POST",
            url: "check_available",
            data: {room_type: room_type, check_in:checkIn.value, check_out:checkOut.value, adults: adults, children: children},
            success: function (available) {
                const modifyModal = document.getElementById('modifyModal');
                if(available){
                    document.getElementById('modifyModal').querySelector('#review-changes').className = "border p-2";
                    modifyModal.querySelector('.check-in').innerHTML = "";
                    modifyModal.querySelector('.check-out').innerHTML = "";
                    modifyModal.querySelector('.guests').innerHTML = "";
                    modifyModal.querySelector('.room-type').innerHTML = "";
                    modifyModal.querySelector('.rate').innerHTML = "";
                    modifyModal.querySelector('.night-price').innerHTML = "";
                    modifyModal.querySelector('.subtotal').innerHTML = "";
                    
                    modifyModal.querySelector('.text-danger').className = "text-danger d-none text-center";
                    modifyModal.querySelector('.check-in').innerHTML = moment(checkIn.value).format('ddd'+ ', ' +'ll');
                    modifyModal.querySelector('.check-out').innerHTML = moment(checkOut.value).format('ddd'+ ', ' +'ll');
                    let adultsOutput = (adults > 1)? adults+' Adults':  adults+' Adult'
                        childrenOutput = (children > 1)? children+' Children':  children+' Child';
                    modifyModal.querySelector('.guests').innerHTML = (children > 0)?adultsOutput+', '+childrenOutput : adultsOutput;
                    modifyModal.querySelector('.room-type').innerHTML = room_type;
                    modifyModal.querySelector('.rate').innerHTML = rate;

                    var dates = [moment(checkIn.value).startOf('day').toDate()];
                    var currDate = moment(checkIn.value).startOf('day');
                    var lastDate = moment(checkOut.value).startOf('day');
                    while(currDate.add(1, 'days').diff(lastDate) < 0) {
                        dates.push(currDate.clone().toDate());
                    }
                    dates.forEach(date => {
                        modifyModal.querySelector('.night-price').innerHTML += "<p style='text-align:right; margin: 0'><small>PHP "+(Math.round(room_rent * 100) / 100).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")+"<span style='float:left;'>"+moment(date).format('ddd'+ ', ' +'ll')+"</span></small></p>";
                    });

                    modifyModal.querySelector('.subtotal').innerHTML = "PHP "+ Math.round(((room_rent*dates.length) * 100) / 100).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

                    let vat = (room_rent*dates.length)*.12
                        cht = (room_rent*dates.length)*.02
                        serviceCharge = (room_rent*dates.length)*.1;

                    modifyModal.querySelector('.total-tax').innerHTML = "PHP "+ Math.round(((vat+cht+serviceCharge) * 100) / 100).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    modifyModal.querySelector('.vat').innerHTML = "PHP "+ Math.round((vat * 100) / 100).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    modifyModal.querySelector('.cht').innerHTML = "PHP "+ Math.round((cht * 100) / 100).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    modifyModal.querySelector('.service-charge').innerHTML = "PHP "+ Math.round((serviceCharge * 100) / 100).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    totalAmount = (room_rent*dates.length)+vat+cht+serviceCharge;
                    modifyModal.querySelector('.total-amount').innerHTML = "PHP "+ Math.round((totalAmount * 100) / 100).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                }
                else{
                    document.getElementById('modifyModal').querySelector('#review-changes').className = "border p-2 d-none";
                    modifyModal.querySelector('.text-danger').className = "text-danger text-center";
                }
            }
        });
    }
    function modify() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            async: false,
            type: "POST",
            url: "modify_dates",
            data: {id: id, check_in:checkIn.value, check_out:checkOut.value, amount: totalAmount, room_rent: room_rent},
            success: function (modified) {
                var modifyModal = bootstrap.Modal.getInstance(document.getElementById('modifyModal'));
                if(modified){
                    modifyModal.hide();
                    sessionStorage.setItem("email", email);
                    location.reload();
                }
            }
        });
    }

    if(sessionStorage.getItem("email")){
        var modifiedModal = new bootstrap.Modal(document.getElementById('modifiedModal'));
        document.getElementById('modifiedModal').querySelector('.email').innerHTML = sessionStorage.getItem("email");
        modifiedModal.show();
        sessionStorage.removeItem("email");
    }

    function cancelModal(reservation_id,conf_no) {
        document.getElementById('cancelModal').querySelector('.conf-no').innerHTML = conf_no;
        id = reservation_id;
    }
    function cancel() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            async: false,
            type: "POST",
            url: "cancel_reservation",
            data: {id: id, reason: document.getElementById('cancelModal').querySelector('.reason').value},
            success: function (cancelled) {
                var cancelModal = bootstrap.Modal.getInstance(document.getElementById('cancelModal'));
                if(cancelled){
                    cancelModal.hide();
                    sessionStorage.setItem("conf_no", document.getElementById('cancelModal').querySelector('.conf-no').innerHTML);
                    location.reload();
                }
            }
        });
    }

    if(sessionStorage.getItem("conf_no")){
        var cancelledModal = new bootstrap.Modal(document.getElementById('cancelledModal'));
        document.getElementById('cancelledModal').querySelector('.conf-no').innerHTML = sessionStorage.getItem("conf_no");
        cancelledModal.show();
        sessionStorage.removeItem("conf_no");
    }

    const btnView = document.querySelector('.reservations').querySelectorAll('.view')
          previews = document.querySelector('.reservations').querySelectorAll('.preview')
          details = document.querySelector('.reservations').querySelectorAll('.details')
          btnClose = document.querySelector('.reservations').querySelectorAll('.close');

    btnView.forEach((view, index) => {
        view.addEventListener('click', ()=>{
            previews[index].classList.replace('d-flex', 'd-none');
            details[index].classList.replace('d-none', 'd-grid');
            previews.forEach((preview, j) => {
                if(j != index)
                preview.parentElement.classList.add('d-none');
            });
        });
    });
    btnClose.forEach((close, index) => {
        close.addEventListener('click', ()=>{
            previews[index].classList.replace('d-none', 'd-flex');
            details[index].classList.replace('d-grid', 'd-none');
            previews.forEach((preview) => {
                preview.parentElement.classList.remove('d-none');
            });
        });
    });
</script>