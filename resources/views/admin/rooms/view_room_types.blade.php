@include('admin.css')
<style>
    @media only screen and (min-width: 768px) {
        .modal-dialog {
            max-width: unset; 
        }
    }
    .switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
    }

    .switch input { 
    opacity: 0;
    width: 0;
    height: 0;
    }

    .slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    -webkit-transition: .4s;
    transition: .4s;
    }

    .slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
    }

    input:checked + .slider {
    background-color: #2196F3;
    }

    input:focus + .slider {
    box-shadow: 0 0 1px #2196F3;
    }

    input:checked + .slider:before {
    -webkit-transform: translateX(26px);
    -ms-transform: translateX(26px);
    transform: translateX(26px);
    }

    .slider.round {
    border-radius: 34px;
    }

    .slider.round:before {
    border-radius: 50%;
    }
</style>
<body>
	<div class="main-wrapper">
	@include('admin.header')
	@include('admin.sidebar')
    @include('admin.delete')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="mt-5">
                            @if(session()->has('message'))
                                @include('admin.alert-success')
                            @endif
                            <h4 class="card-title float-left">Room Type</h4> 
                            <a class="btn btn-primary float-right view button" data-toggle="modal" data-target="#add_room_type">Add Room Type</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <form id="search-form" action="room_types" method="GET">
                        <div class="row formtype">
                            <div class="col-md-2 px-md-0">
                                <div class="form-group">
                                    <select class="form-control" name="column">
                                        @if(isset($_GET["column"]))
                                            @php 
                                                $options = array('All', 'Room Name', 'Status');
                                                $output = '';
                                                for( $i=0; $i<count($options); $i++ ) {
                                                    $output .= '<option ' . ( $_GET["column"] == $options[$i] ? 'selected' : '' ) . '>' . $options[$i] . '</option>';
                                                }
                                                echo $output;
                                            @endphp
                                        @else
                                            <option>All</option>
                                            <option>Room Name</option>
                                            <option>Status</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="search" value="@php if(isset($_GET["search"])) echo $_GET["search"]; @endphp"/>
                                </div>
                            </div>
                            <div class="col-md-3 pl-md-0">
                                <div class="form-group d-flex">
                                    <input type="button" onclick="validateForm()" class="btn btn-secondary mt-0 col-md-6 search_button" value="Search"/>
                                    <a href="/room_types" class="btn btn-secondary mt-0 ml-2 col-md-6">Clear</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body booking_card">
                            <div class="table-responsive">
                                <table class="datatable table table-stripped table table-hover table-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>Room Name</th>
                                            <th>Rent</th>
                                            <th>Room Count</th>
                                            <th>Status</th>
                                            <th class="actions text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            function strContains($str1, $str2) {
                                                return str_contains(strtolower($str1), strtolower($str2)) || str_contains(strtolower($str2), strtolower($str1));
                                            }
                                            if(isset($_GET["search"]) && $_GET["search"] != null){
                                                $room_types = $room_types->filter(function ($item) {
                                                    if($_GET["column"] == 'All'){
                                                        return 
                                                        strContains($item->room_name, $_GET["search"]) || 
                                                        str_starts_with(strtolower($item->status), strtolower($_GET["search"]));
                                                    }
                                                    else if($_GET["column"] == 'Room Name'){
                                                        return 
                                                        strContains($item->room_name, $_GET["search"]);
                                                    }
                                                    else if($_GET["column"] == 'Status'){
                                                        return 
                                                        str_starts_with(strtolower($item->status), strtolower($_GET["search"]));
                                                    }
                                                })->values();
                                            }
                                        @endphp
                                        @foreach ($room_types as $room_type)
                                        <tr>
                                            <td>{{ucwords($room_type->room_name)}}</td>
                                            <td>₱{{number_format((float)$room_type->rent, 2, '.')}}</td>
                                            <td>{{$room_type->rooms->count()}}</td>
                                            <td>
                                                <p class="d-none">{{$room_type->status}}</p>
                                                <label class="switch">
                                                    <form action="{{url('/update_room_type_status',$room_type->id)}}" method="POST">
                                                        @csrf
                                                        <input type="checkbox" name="switch" onChange="this.form.submit()" {{($room_type->status == 'Active')? 'checked' : '' }}>
                                                        <span class="slider round"></span>
                                                    </form>
                                                </label>
                                            </td>
                                            <td class="text-right">
                                                <div class="dropdown dropdown-action"> <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-v ellipse_color"></i></a>
                                                    <div class="dropdown-menu dropdown-menu-right"> 
                                                        <a class="dropdown-item" data-toggle="modal" data-target="#edit_room_type" onclick="edit({{$room_type->id}})"><i class="fas fa-pencil-alt"></i> Edit</a>
                                                        <a class="dropdown-item" data-toggle="modal" data-target="#delete" onclick="deleteRoomType({{$room_type->id}})"><i class="fas fa-trash-alt"></i> Delete</a> 
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
    function getRoomType(id) {
        let room_type;
        $.ajax({
            async: false,
            type: "GET",
            url: "get_room_type/"+id,
            success: function (response) {
                room_type = response;
            }
        });
        return room_type;
    }
    function validateForm(){
        var isValid = false;

        var elements = document.getElementById('search-form').querySelectorAll('input[type=text]');
        for(var i=0; i < elements.length; i++){
        if(elements[i].value && !(!elements[i].value.trim().length)){
            isValid= true;
        }
        }

        if(isValid){
            document.getElementById('search-form').submit();
        }
    }

document.getElementById('side-rooms').classList.add('active');
document.getElementById('side-rooms-link').classList.add('active');
document.getElementById('side-rooms-link').classList.add('subdrop');
document.getElementById('submenu-rooms').style.display = "block";
document.getElementById('side-all-rooms').classList.add('active');

    window.onload = function() {
        if (sessionStorage.getItem("addRoomType")) {
            $('#add_room_type').modal('show');
            sessionStorage.removeItem("addRoomType");
        }
        if (sessionStorage.getItem("idRoomName") || sessionStorage.getItem("idBeds") || sessionStorage.getItem("idAmenities")) {
            edit(sessionStorage.getItem("idRoomName"));
            edit(sessionStorage.getItem("idBeds"));
            edit(sessionStorage.getItem("idAmenities"));
            $('#edit_room_type').modal('show');
            sessionStorage.removeItem("idRoomName");
            sessionStorage.removeItem("idBeds");
            sessionStorage.removeItem("idAmenities");
        }
    }

    function deleteRoomType(id){
        let room_type = getRoomType(id);
        deletePicture.src = room_type.picture;
        deleteName.innerHTML = ucwords(room_type.room_name);
        deleteBtn.href = "/delete_room_type/"+id;
    }
</script>
@include('admin.rooms.add_room_type')
@include('admin.rooms.edit_room_type')
</body>
</html>