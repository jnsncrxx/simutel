@include('admin.css')
<style>
    .bi{
        cursor: default !important;
    }
</style>
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
                            <h4 class="card-title float-left">Guests</h4> 
                            <a class="btn btn-primary float-right view button" data-toggle="modal" data-target="#add_member">Add Member</a>
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
                        <input type="search" class="form-control" onsearch="validateForm(this.parentElement.querySelectorAll('input')[0]);" name="search" placeholder="&#xf002 Search" style=" font-family: 'Helvetica', FontAwesome, sans-serif;" value="@php if(isset($_GET["search"])) echo $_GET["search"]; @endphp"/>
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
            </div>
            @php
                function strContains($str1, $str2) {
                    return str_contains(strtolower($str1), strtolower($str2)) || str_contains(strtolower($str2), strtolower($str1));
                }
                if(isset($_GET["search"]) && $_GET["search"] != null){
                    $guests = $guests->filter(function ($item) {
                            if(strContains($item->first_name, $_GET["search"]) || strContains($item->last_name, $_GET["search"]))
                                return true;
                            else {
                                foreach($item->reservations as $reservation){
                                    if($reservation->id == $_GET["search"])
                                    return true;
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
                    $guests = $guests->filter(function ($item) use($range){
                        if(isset($range)){
                            for($i=0; $i<=$range; $i++){
                                $days = ($i<2)?' day ':' days ';
                                if(date('Y-m-d', strtotime('-'.$i.$days)) == date('Y-m-d', strtotime($item->created_at)))
                                    return true;
                            }
                        }
                        else {
                            if(date('Y-m-d', strtotime($_GET["date"])) == date('Y-m-d', strtotime($item->created_at)))
                                return true;
                        }
                    })->values();
                }
            @endphp
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body booking_card">
                            <div class="table-responsive">
                                <table class="datatable table table-stripped table table-hover table-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Birthday</th>
                                            <th>Age</th>
                                            <th>Contact</th>
                                            <th>Guest Type</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($guests as $guest)
                                        <tr>
                                            <td>{{ucfirst($guest->first_name)}}</td>
                                            <td>{{ucfirst($guest->last_name)}}</td>
                                            <td>{{(is_null($guest->birthday))?'-':$guest->birthday}}</td>
                                            <td>
                                                @php
                                                    $birthDate   = new DateTime($guest->birthday);
                                                    $currentDate = new DateTime('today');
                                                    
                                                    echo (is_null($guest->birthday))?'-':$birthDate->diff($currentDate)->y;
                                                @endphp
                                            </td>
                                            <td>{{(is_null($guest->contact))?'-':$guest->contact}}</td>
                                            <td>{!!(isset($guest->member))?'<p class="bi bi-check-circle btn btn-sm bg-primary-light"> Member</p> ':'<p class="bi bi-x-circle btn btn-sm bg-danger-light"> Non-member</p>';!!}</td>
                                            <td class="text-right">
                                                <div class="dropdown dropdown-action"> <a class="action-icon dropdown-toggle border px-2 clickable" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-h ellipse_color"></i></a>
                                                    <div class="dropdown-menu dropdown-menu-right"> 
                                                        @php
                                                            $reservation = $guest->reservations->filter(function ($reservation) {
                                                                if(date('Y-m-d') >= date('Y-m-d', strtotime($reservation->check_in)) && date('Y-m-d') <= date('Y-m-d', strtotime($reservation->check_out)))
                                                                    return true;
                                                            })->first();
                                                        @endphp
                                                        <a class="dropdown-item px-3 text-center clickable" onclick="sessionStorage.setItem('booking_id', {{$reservation->id}});
                                                        window.location.href='all_reservations?date=today';"><i class="fa fa-eye"></i> View Reservation</a>
                                                        <a class="dropdown-item px-3 text-center clickable" data-toggle="modal" data-target="#edit_guest" onclick="editGuest({{$guest->id}})"><i class="fas fa-pencil-alt"></i> Edit Guest Details</a>
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
@include('admin.guests.add_member')
@include('admin.guests.edit_guest')
<script>
document.getElementById('side-guests').classList.add('active');

    @error('edit_email')
        edit({{$message}},'{{ucwords($members->find($message)->guests->first_name)}}','{{ucwords($members->find($message)->guests->last_name)}}','{{$members->find($message)->user->email}}','{{$members->find($message)->guests->contact}}','{{ucwords($members->find($message)->user->username)}}','{{$members->find($message)->guests->birthday}}');
        document.getElementById('edit-email-warn').classList.remove('d-none');
        $('#edit_member').modal('show');
    @enderror

    @error('edit_username')
        edit({{$message}},'{{ucwords($members->find($message)->guests->first_name)}}','{{ucwords($members->find($message)->guests->last_name)}}','{{$members->find($message)->user->email}}','{{$members->find($message)->guests->contact}}','{{ucwords($members->find($message)->user->username)}}','{{$members->find($message)->guests->birthday}}');
        document.getElementById('edit-username-warn').classList.remove('d-none');
        $('#edit_member').modal('show');
    @enderror


    function editGuest(id){
        let guest;
        $.ajax({
            async: false,
            type: "GET",
            url: "get_guest/"+id,
            success: function (response) {
                guest = response;
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
            }
        });
        console.log(guest);
        // document.getElementById('first-name').value = guest.first_name;
        // document.getElementById('last-name').value = guest.last_name;
        // document.getElementById('email').value = email;
        // document.getElementById('contact').value = guest.contact;
        // document.getElementById('username').value = username;
        // document.getElementById('birthday').value = guest.birthday;
        // document.getElementById('edit-form').action = "/update_member/"+id;
    };
</script>
</body>
</html>