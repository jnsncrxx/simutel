@include('home.rooms.css')
@include('admin.css')
<style>
    @media only screen and (min-width: 992px) {
        .modal-dialog {
            max-width: unset; 
        }
    }
    p{
        margin: 0;
    }
    form {
        margin: 0;
    }
</style>
<meta name="csrf-token" content="{{ csrf_token() }}" />
<body>
	<div class="main-wrapper">
	@include('admin.header')
	@include('admin.sidebar')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="mt-5">
                            <h4 class="card-title float-left">Room Reservations</h4> 
                            <a class="btn btn-primary float-right view button" data-toggle="modal" data-target="#add_reservation">Add Reservation</a>
                        </div>
                    </div>
                </div>
            </div>
            @if(session()->has('message'))
                @include('admin.alert-success')
            @endif
            <div class="row mx-0 search">
                <div class="col-md-4 pl-0 pr-2">
                    <div class="form-group d-flex">
                        <input type="text" class="form-control" name="search" placeholder="&#xf002 Search" style=" font-family: 'Helvetica', FontAwesome, sans-serif;" value="@php if(isset($_GET["search"])) echo $_GET["search"]; @endphp"/>
                        <input type="button" onclick="validateForm(this.parentElement.querySelectorAll('input')[0])" class="btn btn-secondary ml-1" style="width:fit-content" value="Search"/>
                    </div>
                </div>
                <div class="pr-2">
                    <div class="dropdown dropdown-action date"> <a class="action-icon dropdown-toggle btn btn-outline-secondary" data-toggle="dropdown">Select Date <i class="fa-solid fa-angle-down"></i></a>
                        <div class="dropdown-menu"> 
                            <a class="btn btn-secondary dropdown-item today" onclick="getFilters(event.target.tagName,'date',this.innerHTML.toLowerCase())">Today</a>
                            <a class="btn btn-secondary dropdown-item 7" onclick="getFilters(event.target.tagName,'date',this.innerHTML.toLowerCase())">Last 7 Days</a>
                            <a class="btn btn-secondary dropdown-item 15" onclick="getFilters(event.target.tagName,'date',this.innerHTML.toLowerCase())">Last 15 Days</a>
                            <a class="btn btn-secondary dropdown-item 30" onclick="getFilters(event.target.tagName,'date',this.innerHTML.toLowerCase())">Last 30 Days</a>
                            <a class="dropdown-item custom">
                                <div class="btn p-0">Custom Date</div>
                                <input class="form-control text-center p-0 pr-2" type="date" onkeydown="return false" value="{{(isset($_GET['date']) && DateTime::createFromFormat('d-m-Y', $_GET['date']) !== false)?date('Y-m-d',strtotime($_GET['date'])):''}}"  onclick="this.showPicker()" onchange="getFilters(event.target.tagName,'date',this.value)">
                            </a> 
                        </div>
                    </div>
                </div>
                <div class="pr-2">
                    <div class="dropdown dropdown-action room-types"> <a class="action-icon dropdown-toggle btn btn-outline-secondary" data-toggle="dropdown">Room Types <i class="fa-solid fa-angle-down"></i></a>
                        <div class="dropdown-menu"> 
                            @forEach($room_types as $room_type)
                                <div class="dropdown-item">
                                    <label class="mb-0 d-flex">
                                        <input type="checkbox" onclick="getFilters(event.target.tagName,'room_types',this.closest('.dropdown-menu'))" value="{{$room_type->room_name}}">&nbsp;{{$room_type->room_name}}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="pr-2">
                    <div class="dropdown dropdown-action statuses"> <a class="action-icon dropdown-toggle btn btn-outline-secondary" data-toggle="dropdown">Status <i class="fa-solid fa-angle-down"></i></a>
                        <div class="dropdown-menu"> 
                            @php
                                $statuses = array("Confirmed", "Due In", "Due Out", "Checked-in", "Checked-out", "No Show");
                            @endphp
                            @forEach($statuses as $status)
                                <div class="dropdown-item">
                                    <label class="mb-0 d-flex">
                                        <input type="checkbox" onclick="getFilters(event.target.tagName,'status',this.closest('.dropdown-menu'))" value="{{$status}}">&nbsp;{{$status}}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @php
                function strContains($str1, $str2) {
                    return str_contains(strtolower($str1), strtolower($str2)) || str_contains(strtolower($str2), strtolower($str1));
                }
                if(isset($_GET["search"]) && $_GET["search"] != null){
                    $reservations = $reservations->filter(function ($item) {
                        if($item->id == $_GET["search"] || $item->room_no == $_GET["search"] || strContains($item->reserved_by->first_name, $_GET["search"]) || strContains($item->reserved_by->last_name, $_GET["search"]))
                            return true;
                        else {
                            foreach ($item->guests as $guest) {
                                return strContains($guest->first_name, $_GET["search"]) || strContains($guest->last_name, $_GET["search"]);
                            }
                        }
                    })->values();
                }
                if(isset($_GET["date"]) && $_GET["date"] != null){
                    $range = null;
                    if($_GET["date"] == 'today')
                        $range = 0;
                    else if($_GET["date"] == 'last 7 days')
                        $range = 7;
                    else if($_GET["date"] == 'last 15 days')
                        $range = 15;
                    else if($_GET["date"] == 'last 30 days')
                        $range = 30;
                    $reservations = $reservations->filter(function ($item) use($range){
                        if(isset($range)){
                            for($i=0; $i<=$range; $i++){
                                $days = ($i<2)?' day ':' days ';
                                if(date('Y-m-d', strtotime('-'.$i.$days)) >= date('Y-m-d', strtotime($item->check_in)) && date('Y-m-d', strtotime('-'.$i.$days)) <= date('Y-m-d', strtotime($item->check_out)))
                                    return true;
                            }
                        }
                        else {
                            if(date('Y-m-d', strtotime($_GET["date"])) >= date('Y-m-d', strtotime($item->check_in)) && date('Y-m-d', strtotime($_GET["date"])) <= date('Y-m-d', strtotime($item->check_out)))
                                return true;
                        }
                    })->values();
                }
                if(isset($_GET["room_types"]) && $_GET["room_types"] != null){
                    $reservations = $reservations->filter(function ($item) {
                            return strContains($item->room_type, $_GET["room_types"]);
                    })->values();
                }
                if(isset($_GET["status"]) && $_GET["status"] != null){
                    $reservations = $reservations->filter(function ($item) {
                            return strContains($item->status, $_GET["status"]);
                    })->values();
                }
            @endphp
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body booking_card">
                            <div class="table-responsive">
                                <table class="datatable table table-stripped table-hover table-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Room No.</th>
                                            <th>Room Type</th>
                                            <th>Reserved By</th>
                                            <th>Check-in</th>
                                            <th>Check-out</th>
                                            <th>Amount</th>
                                            <th>Paid</th>
                                            <th>Status</th>
                                            <th class="actions text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($reservations as $reservation)
                                        <tr onclick="reservationDetails({{$reservation->id}},event)" class="clickable">
                                            <td>{{$reservation->id}}</td>
                                            <td><div class="border rounded p-2 text-center">{{(is_null($reservation->room_no))?'Unassigned':$reservation->room_no}}</div></td>
                                            <td>{{ucwords($reservation->room_type)}}</td>
                                            <td>{{ucwords($reservation->reserved_by->first_name).' '.ucwords($reservation->reserved_by->last_name)}}</td>
                                            <td>{{date('d-m-Y', strtotime(explode(' ', $reservation->check_in)[0]))}}</td>
                                            <td>{{date('d-m-Y', strtotime(explode(' ', $reservation->check_out)[0]))}}</td>
                                            @php
                                                $totalCharges = 0;
                                                foreach ($reservation->additional_charges as $charge) {
                                                    $totalCharges += $charge->amount; 
                                                }
                                            @endphp
                                            <td>₱{{number_format((float)$reservation->amount + (float)$totalCharges, 2, '.')}}</td>
                                            <td>₱{{number_format((float)$reservation->paid, 2, '.')}}</td>
                                            <td>
                                                <div class="dropdown dropdown-action"> 
                                                    @php
                                                        echo "<a data-toggle='dropdown' aria-expanded='false' class='action-icon dropdown-toggle w-100 rounded-0 btn btn-sm ";    
                                                        if($reservation->status == 'Confirmed') 
                                                            echo "bg-success-light d-flex'><span class='mr-1'>".$reservation->status."</span><i class='fa-solid pt-1 ml-auto my-auto fa-angle-down'></i>";
                                                        elseif($reservation->status == 'Checked-in') 
                                                            echo "bg-warning-light d-flex'><span class='mr-1'>".$reservation->status."</span><i class='fa-solid pt-1 ml-auto my-auto fa-angle-down'></i>";
                                                        elseif($reservation->status == 'Due In') 
                                                            echo "d-flex' style='background: #f9d3ff;color: violet;'><span class='mr-1'>".$reservation->status."</span><i class='fa-solid pt-1 ml-auto my-auto fa-angle-down'></i>";
                                                        elseif($reservation->status == 'Due Out') 
                                                            echo "bg-info-light d-flex'><span class='mr-1'>".$reservation->status."</span><i class='fa-solid pt-1 ml-auto my-auto fa-angle-down'></i>";
                                                        elseif($reservation->status == 'Checked-out')  
                                                            echo "bg-primary-light'>".$reservation->status;
                                                        elseif($reservation->status == 'Cancelled') 
                                                            echo "bg-secondary text-light'>".$reservation->status;
                                                        elseif($reservation->status == 'No Show') 
                                                            echo "bg-danger-light'>".$reservation->status;
                                                    @endphp
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right shadow rounded-0 {{($reservation->status == 'No Show' || $reservation->status == 'Cancelled' || $reservation->status == 'Checked-out')? 'd-none' : ''}}"> 
                                                        <div class="dropdown-item px-3 {{($reservation->status == 'Checked-in' || $reservation->status == 'Due Out')? 'd-none' : 'd-flex'}}" onclick="confirmCheckIn({{$reservation->id}},'{{$reservation->room_no}}','{{$reservation->check_in}}','{{$reservation->check_out}}','{{ucwords($reservation->room_type)}}',{{$reservation->adults}},{{$reservation->children}})">
                                                            <p class="my-auto">Change to <i class="fa-solid mx-2 fa-angle-right"></i></p>
                                                            <button class="btn btn-sm bg-warning-light w-100 rounded-0">Checked-in</button>
                                                        </div>
                                                        <div class="dropdown-item px-3 {{($reservation->status == 'Checked-out' || $reservation->status == 'Confirmed' || $reservation->status == 'Due In')? 'd-none' : 'd-flex'}}" onclick="confirmCheckOut({{$reservation->id}},'{{$reservation->check_in}}','{{$reservation->check_out}}',{{$reservation->adults}},{{$reservation->children}})">
                                                            <p class="my-auto">Change to <i class="fa-solid mx-2 fa-angle-right"></i></p>
                                                            <button class="btn btn-sm bg-primary-light w-100 rounded-0">Checked-out</button>
                                                        </div>
                                                        <div class="dropdown-item px-3 {{($reservation->status == 'Cancelled' || $reservation->status == 'Due Out' || $reservation->status == 'Checked-in')? 'd-none' : 'd-flex'}}" onclick="confirmCancel({{$reservation->id}},'{{ucwords($reservation->reserved_by->first_name).' '.ucwords($reservation->reserved_by->last_name)}}')">
                                                            <p class="my-auto">Change to <i class="fa-solid mx-2 fa-angle-right"></i></p>
                                                            <button class="btn btn-sm bg-secondary text-light w-100 rounded-0">Cancelled</button>
                                                        </div> 
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-right">
                                                <div class="dropdown dropdown-action"> <a class="action-icon dropdown-toggle border px-2" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-h ellipse_color"></i></a>
                                                    <div class="dropdown-menu dropdown-menu-right"> 
                                                        <div class="dropdown-item view d-flex px-0"><i class="fa fa-eye mr-1 my-auto col-3"></i><span>View</span></div>
                                                        @if($reservation->status == 'Confirmed' || $reservation->status == 'Due In')
                                                            <div class="dropdown-item d-flex px-0" onclick="reschedule({{$reservation->id}},'{{ucwords($reservation->reserved_by->first_name).' '.ucwords($reservation->reserved_by->last_name)}}','{{explode(' ', $reservation->check_in)[0]}}','{{explode(' ', $reservation->check_out)[0]}}',{{$reservation->adults}},{{$reservation->children}},'{{ucwords($reservation->room_type)}}','{{ucwords($reservation->rate)}}','{{$reservation->amount}}')"><i class="fa fa-calendar-o mr-1 my-auto col-3"></i><span>Reschedule</span></div>
                                                        @endif
                                                        @if($reservation->status != 'Checked-out' && $reservation->status != 'Cancelled')
                                                            <div class="dropdown-item d-flex px-0" onclick="addExtraGuest({{$reservation->id}})"><i class="fa fa-user mr-1 my-auto col-3"></i><span>Add extra guest</span></div>
                                                        @endif
                                                        @if($reservation->status != 'Checked-out' && $reservation->status != 'Cancelled')
                                                            <div class="dropdown-item d-flex px-0" onclick="addPayment({{$reservation->id}})"><i class="fa fa-credit-card mr-1 my-auto col-3"></i><span>Add payment</span></div>
                                                        @endif
                                                        @if($reservation->status != 'Checked-out' && $reservation->status != 'Cancelled')
                                                            <div class="dropdown-item d-flex px-0" onclick="changeRoom({{$reservation->id}},'{{ucwords($reservation->reserved_by->first_name).' '.ucwords($reservation->reserved_by->last_name)}}','{{explode(' ', $reservation->check_in)[0]}}','{{explode(' ', $reservation->check_out)[0]}}',{{$reservation->adults}},{{$reservation->children}},'{{ucwords($reservation->room_type)}}','{{(is_null($reservation->room_no))?'Unassigned':$reservation->room_no}}','{{ucwords($reservation->rate)}}','₱{{number_format((float)$reservation->amount, 2, '.')}}')"><i class="fa fa-bed mr-1 my-auto col-3"></i><span>Change Room</span></div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@include('admin.script')
<script>
    @if(isset($_GET["date"]))
        fillFilterButton(document.querySelector('.search').querySelector('.date').querySelector('.dropdown-menu').querySelectorAll('a'), "{{$_GET['date']}}", document.querySelector('.search').querySelector('.date').querySelector('a'), 'date');
    @endif
    @if(isset($_GET["room_types"]))
        fillFilterButton(document.querySelector('.search').querySelector('.room-types').querySelectorAll('[type="checkbox"]'), "{{$_GET['room_types']}}", document.querySelector('.search').querySelector('.room-types').querySelector('a'), 'room_types');
    @endif
    @if(isset($_GET["status"]))
        fillFilterButton(document.querySelector('.search').querySelector('.statuses').querySelectorAll('[type="checkbox"]'), "{{$_GET['status']}}", document.querySelector('.search').querySelector('.statuses').querySelector('a'), 'status');
    @endif
</script>
@include('admin.reservations.reservation_details')
@include('admin.reservations.add_reservation')
@include('admin.reservations.edit-time')
@include('admin.reservations.change_payer')
@include('admin.reservations.add_extra_guest')
@include('admin.reservations.add_payment')
@include('admin.reservations.reschedule')
@include('admin.reservations.change_room')
@include('admin.reservations.check-in')
@include('admin.reservations.check-out')
@include('admin.reservations.cancel')
@include('admin.reservations.no-show')
<script>
document.getElementById('side-reservations').classList.add('active');

if(sessionStorage.getItem("add_reservation")){
    sessionStorage.removeItem("add_reservation");
    $('#add_reservation').modal('show');
}
if(sessionStorage.getItem("booking_id")){
    console.log(sessionStorage.getItem("booking_id"));
    reservationDetails(sessionStorage.getItem("booking_id"),"session");
    sessionStorage.removeItem("booking_id");
}
$('.room-types').on('hidden.bs.dropdown', () => {
    $('.room-types')[0].querySelector('i').className = "fa-solid fa-angle-down";
})
$('.room-types').on('shown.bs.dropdown', () => {
    $('.room-types')[0].querySelector('i').className = "fa-solid fa-angle-up";
})
$('#check_in').on('hidden.bs.modal', function (e) {
    document.body.style.overflow = "visible";
});
$('#check_in').on('show.bs.modal', function (e) {
    document.body.style.overflow = "hidden";
});
</script>
</body>
</html>