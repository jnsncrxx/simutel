@include('admin.css')
<body>
    @include('admin.rooms.add_rooms')
    @include('admin.rooms.add_multiple_rooms')
    @include('admin.rooms.edit_room')
    @include('admin.delete')
	<div class="main-wrapper">
	@include('admin.header')
	@include('admin.sidebar')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="mt-5">
                            @if(session()->has('message'))
                                @include('admin.alert-success')
                            @endif
                            <h4 class="card-title float-left">All Rooms</h4> 
                            <a class="btn btn-primary float-right ml-2" data-toggle="modal" data-target="#add_multiple_rooms" onclick="document.getElementById('table-rows').innerHTML = ''">Add Multiple Rooms</a>
                            <a class="btn btn-primary float-right" data-toggle="modal" data-target="#add_room">Add Room</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <form id="search-form" action="all_rooms" method="GET">
                        <div class="row formtype">
                            <div class="col-md-2 px-md-0">
                                <div class="form-group">
                                    <select class="form-control" name="column">
                                        @if(isset($_GET["column"]))
                                            @php 
                                                $options = array('All', 'Room No.', 'Room Type', 'Floor', 'Status');
                                                $output = '';
                                                for( $i=0; $i<count($options); $i++ ) {
                                                    $output .= '<option ' . ( $_GET["column"] == $options[$i] ? 'selected' : '' ) . '>' . $options[$i] . '</option>';
                                                }
                                                echo $output;
                                            @endphp
                                        @else
                                            <option>All</option>
                                            <option>Room No.</option>
                                            <option>Room Type</option>
                                            <option>Floor</option>
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
                                    <a href="/all_rooms" class="btn btn-secondary mt-0 ml-2 col-md-6">Clear</a>
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
                                <table id="mainTable" class="datatable table table-stripped table table-hover table-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="actions"><input type="checkbox"> Select all</th>
                                            <th>Room No.</th>
                                            <th>Room Type</th>
                                            <th>Floor</th>
                                            <th>Status</th>
                                            <th class="actions text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            if(isset($_GET["search"]) && $_GET["search"] != null){
                                                $rooms = $rooms->filter(function ($item) {
                                                    if($_GET["column"] == 'All'){
                                                        return 
                                                        $item->room_no == $_GET["search"] || 
                                                        str_contains(strtolower($item->room_types->room_name), strtolower($_GET["search"])) || 
                                                        str_contains(strtolower($_GET["search"]), strtolower($item->room_types->room_name)) || 
                                                        $item->floor == $_GET["search"] ||
                                                        str_contains(strtolower($item->status), strtolower($_GET["search"])) || 
                                                        str_contains(strtolower($_GET["search"]), strtolower($item->status));
                                                    }
                                                    else if($_GET["column"] == 'Room No.'){
                                                        return 
                                                        $item->room_no == $_GET["search"];
                                                    }
                                                    else if($_GET["column"] == 'Room Type'){
                                                        return 
                                                        str_contains(strtolower($item->room_types->room_name), strtolower($_GET["search"])) || 
                                                        str_contains(strtolower($_GET["search"]), strtolower($item->room_types->room_name));
                                                    }
                                                    else if($_GET["column"] == 'Floor'){
                                                        return 
                                                        $item->floor == $_GET["search"];
                                                    }
                                                    else if($_GET["column"] == 'Status'){
                                                        return 
                                                        str_contains(strtolower($item->status), strtolower($_GET["search"])) || 
                                                        str_contains(strtolower($_GET["search"]), strtolower($item->status));
                                                    }
                                                })->values();
                                            }
                                        @endphp
                                        @foreach ($rooms->sortBy('room_no') as $room)
                                        <tr>
                                            <td><input type="checkbox"></td>
                                            <td>{{$room->room_no}}</td>
                                            <td>{{ucwords(strtolower($room->room_types->room_name))}}</td>
                                            <td>{{$room->floor}}</td>
                                            <td>
                                                @php
                                                    echo '<p class="btn btn-sm m-0 bg-';
                                                    if($room->status == 'Open') 
                                                        echo 'success';
                                                    else if($room->status == 'Out Of Order')
                                                        echo 'danger';
                                                    echo '-light">'.$room->status.'</p> ';
                                                @endphp
                                            </td>
                                            <td class="text-right">
                                                <div class="dropdown dropdown-action"> <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-v ellipse_color"></i></a>
                                                    <div class="dropdown-menu dropdown-menu-right"> 
                                                        <a class="dropdown-item" data-toggle="modal" data-target="#edit_room" onclick="edit({{$room->id}})"><i class="fas fa-pencil-alt"></i>Edit</a>
                                                        <a class="dropdown-item" data-toggle="modal" data-target="#delete" onclick="deleteRoom({{$room->id}})"><i class="fas fa-trash-alt"></i> Delete</a> 
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
    function getRoom(id) {
        let room;
        $.ajax({
            async: false,
            type: "GET",
            url: "get_room/"+id,
            success: function (response) {
                room = response;
            }
        });
        return room;
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
document.getElementById('side-room-types').classList.add('active');

      window.onload = function() {
        if (sessionStorage.getItem("addRoom")) {
            $('#add_room').modal('show');
            sessionStorage.removeItem("addRoom");
        }
        if (sessionStorage.getItem("addMultipleRooms")) {
            $('#add_multiple_rooms').modal('show');
            sessionStorage.removeItem("addMultipleRooms");
        }
        if (sessionStorage.getItem("idRoomNo")) {
            edit(sessionStorage.getItem("idRoomNo"));
            $('#edit_room').modal('show');
            sessionStorage.removeItem("idRoomNo");
        }
    }

    function deleteRoom(id){
        let room = getRoom(id);
            if(room_type.id == room.room_type_id){
                deletePicture.src = room_type.picture;
                deleteName.innerHTML = 'Room No. '+room.room_no;
                deleteBtn.href = "/delete_room/"+ room.id;
            }
    }
</script>
</body>
</html>