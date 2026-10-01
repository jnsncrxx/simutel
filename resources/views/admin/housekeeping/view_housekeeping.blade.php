@include('admin.css')
<style>
    .circle span {
        color: black;
    }
    .circle::before{
        content: '◉';
        font-size: 1.5em;
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
                            <h4 class="card-title float-left">Housekeeping</h4> 
                        </div>
                    </div>
                </div>
            </div>
            @if(session()->has('message'))
                @include('admin.alert-success')
            @endif
            <div class="row mx-0 mb-2 search">
                <div class="col-md-4 pl-0 pr-2">
                    <div class="form-group d-flex">
                        <input type="text" class="form-control" name="search" placeholder="&#xf002 Search" style=" font-family: 'Helvetica', FontAwesome, sans-serif;" value="@php if(isset($_GET["search"])) echo $_GET["search"]; @endphp"/>
                        <input type="button" onclick="validateForm(this.parentElement.querySelectorAll('input')[0])" class="btn btn-secondary ml-1" style="width:fit-content" value="Search"/>
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
                    <div class="dropdown dropdown-action housekeeping"> <a class="action-icon dropdown-toggle btn btn-outline-secondary" data-toggle="dropdown">Housekeeping Status <i class="fa-solid fa-angle-down"></i></a>
                        <div class="dropdown-menu"> 
                            @php
                                $housekeeping_status = array("Clean", "Cleaning", "Dirty", "Out of service");
                            @endphp
                            @forEach($housekeeping_status as $status)
                                <div class="dropdown-item">
                                    <label class="mb-0 d-flex">
                                        <input type="checkbox" onclick="getFilters(event.target.tagName,'housekeeping',this.closest('.dropdown-menu'))" value="{{$status}}">&nbsp;{{$status}}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="pr-2">
                    <div class="dropdown dropdown-action priority"> <a class="action-icon dropdown-toggle btn btn-outline-secondary" data-toggle="dropdown">Priority <i class="fa-solid fa-angle-down"></i></a>
                        <div class="dropdown-menu"> 
                            @php
                                $priorities = array("High", "Normal", "Low");
                            @endphp
                            @forEach($priorities as $priority)
                                <div class="dropdown-item">
                                    <label class="mb-0 d-flex">
                                        <input type="checkbox" onclick="getFilters(event.target.tagName,'priority',this.closest('.dropdown-menu'))" value="{{$priority}}">&nbsp;{{$priority}}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="pr-2">
                    <div class="dropdown dropdown-action reservation"> <a class="action-icon dropdown-toggle btn btn-outline-secondary" data-toggle="dropdown">Reservation Status <i class="fa-solid fa-angle-down"></i></a>
                        <div class="dropdown-menu"> 
                            @php
                                $reservation_status = array("Occupied", "Vacant", "Pending");
                            @endphp
                            @forEach($reservation_status as $status)
                                <div class="dropdown-item">
                                    <label class="mb-0 d-flex">
                                        <input type="checkbox" onclick="getFilters(event.target.tagName,'reservation',this.closest('.dropdown-menu'))" value="{{$status}}">&nbsp;{{$status}}
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
                    $housekeepings = $housekeepings->filter(function ($item) {
                        return $item->rooms->room_no == $_GET["search"];
                    })->values();
                }
                if(isset($_GET["room_types"]) && $_GET["room_types"] != null){
                    $housekeepings = $housekeepings->filter(function ($item) {
                        return strContains($item->rooms->room_types->room_name, $_GET["room_types"]);
                    })->values();
                }
                if(isset($_GET["housekeeping"]) && $_GET["housekeeping"] != null){
                    $housekeepings = $housekeepings->filter(function ($item) {
                        if(in_array($item->status, explode(", ", $_GET["housekeeping"])))
                            return true;
                    })->values();
                }
                if(isset($_GET["priority"]) && $_GET["priority"] != null){
                    $housekeepings = $housekeepings->filter(function ($item) {
                        if(in_array($item->priority, explode(", ", $_GET["priority"])))
                            return true;
                    })->values();
                }
                if(isset($_GET["reservation"]) && $_GET["reservation"] != null){
                    $housekeepings = $housekeepings->filter(function ($item) {
                        if(in_array($item->reservation_status, explode(", ", $_GET["reservation"])))
                            return true;
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
                                            <th>Room No.</th>
                                            <th>Room Type</th>
                                            <th>Housekeeping Status</th>
                                            <th>Priority</th>
                                            <th>Floor</th>
                                            <th>Reservation Status</th>
                                            <th class="actions text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($housekeepings as $housekeeping)
                                        <tr>
                                            <td>{{$housekeeping->rooms->room_no}}</td>
                                            <td>{{ucwords(strtolower($housekeeping->rooms->room_types->room_name))}}</td>
                                            <td>
                                                <div class="dropdown dropdown-action"> 
                                                    @php
                                                        echo '<a data-toggle="dropdown" href="" aria-expanded="false" class="w-100 action-icon dropdown-toggle ';
                                                        if(strtolower($housekeeping->status) == 'clean') 
                                                            echo 'btn btn-sm bg-success-light">';
                                                        elseif(strtolower($housekeeping->status) == 'cleaning') 
                                                            echo 'btn btn-sm bg-primary-light">';
                                                        elseif(strtolower($housekeeping->status) == 'dirty') 
                                                            echo 'btn btn-sm bg-danger-light">';
                                                        elseif(strtolower($housekeeping->status) == 'out of service') 
                                                            echo 'btn btn-sm bg-warning-light">';
                                                        echo ucwords($housekeeping->status).'</a>';
                                                    @endphp
                                                    <div class="dropdown-menu dropdown-menu-right"> 
                                                        <form action="{{url('/update_housekeeping_status',$housekeeping->id)}}" method="POST">
                                                            @csrf
                                                            <a class="dropdown-item {{(strtolower($housekeeping->status) == 'clean')? 'd-none' : ''}}">
                                                                <input type="submit" class="btn btn-sm bg-success-light w-100" value="Clean" name="status">
                                                            </a>
                                                        </form>
                                                        <form action="{{url('/update_housekeeping_status',$housekeeping->id)}}" method="POST">
                                                            @csrf
                                                            <a class="dropdown-item {{(strtolower($housekeeping->status) == 'cleaning')? 'd-none' : ''}}">
                                                                <input type="submit" class="btn btn-sm bg-primary-light w-100" value="Cleaning" name="status">
                                                            </a>
                                                        </form>
                                                        <form action="{{url('/update_housekeeping_status',$housekeeping->id)}}" method="POST">
                                                            @csrf
                                                            <a class="dropdown-item {{(strtolower($housekeeping->status) == 'dirty')? 'd-none' : ''}}">
                                                                <input type="submit" class="btn btn-sm bg-danger-light w-100" value="Dirty" name="status">
                                                            </a>
                                                        </form>
                                                        <form action="{{url('/update_housekeeping_status',$housekeeping->id)}}" method="POST">
                                                            @csrf
                                                            <a class="dropdown-item {{(strtolower($housekeeping->status) == 'out of service')? 'd-none' : ''}}">
                                                                <input type="submit" class="btn btn-sm bg-warning-light w-100" value="Out of service" name="status">
                                                            </a>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="dropdown dropdown-action"> 
                                                    <a class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                    @php
                                                        if(strtolower($housekeeping->priority) == 'high') 
                                                            echo '<p class="d-none">0</p><p class="circle text-danger btn btn-sm border bg-transparent d-flex"><span class="my-auto mx-1">'.ucwords($housekeeping->priority).'</span></p>';
                                                        else if(strtolower($housekeeping->priority) == 'normal') 
                                                            echo '<p class="d-none">1</p><p class="circle text-success btn btn-sm border bg-transparent d-flex"><span class="my-auto mx-1">'.ucwords($housekeeping->priority).'</span></p>';
                                                        else if(strtolower($housekeeping->priority) == 'low') 
                                                            echo '<p class="d-none">2</p><p class="circle text-secondary btn btn-sm border bg-transparent d-flex"><span class="my-auto mx-1">'.ucwords($housekeeping->priority).'</span></p>';
                                                    @endphp
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right"> 
                                                        <form action="{{url('/update_housekeeping_priority',$housekeeping->id)}}" method="POST">
                                                            @csrf
                                                            <a class="dropdown-item py-0 {{(strtolower($housekeeping->priority) == 'high')? 'd-none' : ''}}">
                                                                <p class="priority text-danger btn btn-sm bg-transparent d-flex m-0 p-0">
                                                                    <span class="my-auto mx-0">
                                                                        <input type="submit" class="priority bg-transparent border-0" value="High" name="priority">
                                                                    </span>
                                                                </p>
                                                            </a>
                                                        </form>  
                                                        <form action="{{url('/update_housekeeping_priority',$housekeeping->id)}}" method="POST">
                                                            @csrf
                                                            <a class="dropdown-item py-0 {{(strtolower($housekeeping->priority) == 'normal')? 'd-none' : ''}}">
                                                                <p class="priority text-success btn btn-sm bg-transparent d-flex m-0 p-0">
                                                                    <span class="my-auto mx-0">
                                                                        <input type="submit" class="priority bg-transparent border-0" value="Normal" name="priority">
                                                                    </span>
                                                                </p>
                                                            </a>
                                                        </form>
                                                        <form action="{{url('/update_housekeeping_priority',$housekeeping->id)}}" method="POST">
                                                            @csrf
                                                            <a class="dropdown-item py-0 {{(strtolower($housekeeping->priority) == 'low')? 'd-none' : ''}}">
                                                                <p class="priority text-secondary btn btn-sm bg-transparent d-flex m-0 p-0">
                                                                    <span class="my-auto mx-0">
                                                                        <input type="submit" class="priority bg-transparent border-0" value="Low" name="priority">
                                                                    </span>
                                                                </p>
                                                            </a>
                                                        </form> 
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{$housekeeping->rooms->floor}}</td>
                                            <td>{{ucwords($housekeeping->reservation_status)}}</td>
                                            <td class="text-right">
                                                <div class="dropdown dropdown-action"> <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-v ellipse_color"></i></a>
                                                    <div class="dropdown-menu dropdown-menu-right"> 
                                                        {{-- <a class="dropdown-item" data-toggle="modal" data-target="#edit_room" onclick="edit({{$room->id}})"><i class="fas fa-pencil-alt"></i>Edit</a>
                                                        <a class="dropdown-item" data-toggle="modal" data-target="#delete" onclick="deleteRoom({{$room->id}})"><i class="fas fa-trash-alt"></i> Delete</a>  --}}
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
document.getElementById('side-housekeeping').classList.add('active');
@if(isset($_GET["room_types"]))
    fillFilterButton(document.querySelector('.search').querySelector('.room-types').querySelectorAll('[type="checkbox"]'), "{{$_GET['room_types']}}", document.querySelector('.search').querySelector('.room-types').querySelector('a'), 'room_types');
@endif

@if(isset($_GET["housekeeping"]))
    fillFilterButton(document.querySelector('.search').querySelector('.housekeeping').querySelectorAll('[type="checkbox"]'), "{{$_GET['housekeeping']}}", document.querySelector('.search').querySelector('.housekeeping').querySelector('a'), 'housekeeping');
@endif

@if(isset($_GET["priority"]))
    fillFilterButton(document.querySelector('.search').querySelector('.priority').querySelectorAll('[type="checkbox"]'), "{{$_GET['priority']}}", document.querySelector('.search').querySelector('.priority').querySelector('a'), 'priority');
@endif

@if(isset($_GET["reservation"]))
    fillFilterButton(document.querySelector('.search').querySelector('.reservation').querySelectorAll('[type="checkbox"]'), "{{$_GET['reservation']}}", document.querySelector('.search').querySelector('.reservation').querySelector('a'), 'reservation');
@endif

</script>
</body>
</html>