<style>
    .table th, .table td {
        white-space: nowrap;
    }
    .table tbody tr {
        border-bottom: unset;
    }
    #table-rows td {
        vertical-align: bottom;
    }
    </style>
<div id="add_multiple_rooms" class="modal p-0" role="dialog">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 1050px;">
        <div class="modal-content">
            <div class="modal-body">
                <div class="page-header">
                    <h3 class="page-title position-relative">Add Multiple Rooms
                        <span class="position-absolute text-secondary" style="cursor:pointer;right:-5px;top:-10px;" onclick="$('#add_multiple_rooms').modal('hide');">⨉</span>
                    </h3>
                </div>
                @if(session()->has('multiple_room_no'))
                    <li class="text-danger">{{session()->get('multiple_room_no')}}</li>
                    <script>
                        sessionStorage.setItem("addMultipleRooms", "true");
                    </script>
                @endif
                <form class="row formtype add-rooms">
                    <div class="col-md-2 pr-0">
                        <div class="form-group">
                            <label>Room Count</label>
                            <input class="form-control" type="number" id="multiple-room-count" onchange="roomNumbers()" name="multiple_room_count" min="2" value="{{old('multiple_room_count')}}" required> 
                        </div>
                    </div>
                    <div class="col-md-2 pr-0">
                        <div class="form-group">
                            <label>Room Numbers</label>
                            <div class="d-flex">
                                <input class="form-control pr-1" type="number" id="room-from" onchange="roomNumbers()" name="room_no_from" min="0" value="{{old('room_no_from')}}" placeholder="From" required> 
                                <input class="form-control ml-2" type="number" id="room-to" name="room_no_to" min="0" value="{{old('room_no_to')}}" placeholder="To" readonly>
                            </div>
                        </div>
                    </div>
                    <script>
                        const roomCount = document.getElementById('multiple-room-count')
                              roomFrom = document.getElementById('room-from')
                              roomTo = document.getElementById('room-to');
                            
                                function roomNumbers(){
                                    roomTo.value = parseInt(roomCount.value) + parseInt(roomFrom.value) - 1;
                                }
                    </script>
                    <div class="col-md-1 pr-0">
                        <div class="form-group">
                            <label>Floor</label>
                            <input type="number" class="form-control" min="0" id="multiple_room_floor" name="multiple_room_floor" value="{{old('multiple_room_floor')}}" required>
                        </div>
                    </div>
                    <div class="col-md-3 pr-0">
                        <div class="form-group">
                            <label>Room Type</label>
                            <select class="form-control" id="multiple_room_type" name="multiple_room_type" required>
                                @if(old('multiple_room_type') != null)
                                    @php 
                                        $output = '';
                                        foreach ($room_types as $room_type){
                                            $output .= '<option ' . ( old('multiple_room_type') == ucwords(strtolower($room_type->room_name)) ? 'selected' : '' ) . '>' . ucwords(strtolower($room_type->room_name)) . '</option>';
                                        }
                                        echo $output;
                                    @endphp
                                @else
                                    <option value="">Select</option>
                                    @foreach ($room_types as $room_type)
                                        <option>{{ucwords(strtolower($room_type->room_name))}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 pr-0">
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" id="multiple_room_status" name="multiple_room_status" required>
                                @if(old('multiple_room_status') != null)
                                    @php 
                                        $options = array('Open', 'Out Of Order');
                                        $output = '';
                                        for( $i=0; $i<count($options); $i++ ) {
                                            $output .= '<option ' . ( old('multiple_room_status') == $options[$i] ? 'selected' : '' ) . '>' . $options[$i] . '</option>';
                                        }
                                        echo $output;
                                    @endphp
                                @else
                                    <option value="">Select</option>
                                    <option>Open</option>
                                    <option>Out Of Order</option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 ps-0 d-flex">
                        <div class="form-group mt-auto w-100">
                            <input type="button" id="add-rooms-btn" class="form-control btn btn-secondary text-light" value="Add Rooms">
                        </div>
                    </div>
                </form>
                <form id="add-rooms-form" action="/add_multiple_rooms" method="POST">
                    @csrf
                    <table id="add-rooms-table" class="table table-center mb-3 d-none">
                        <thead>
                            <tr>
                            <th>Room Number</th>
                            <th>Floor</th>
                            <th>Room Type</th>
                            <th>Status</th>
                            <th></th>
                            </tr>
                        </thead>
                        <tbody id="table-rows" class="border">
                        </tbody>
                    </table>
                <input type="submit" class="btn btn-primary ml-1" value="Add Multiple Rooms">
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function getRooms() {
        let rooms;
            $.ajax({
                async: false,
                type: "GET",
                url: "get_rooms",
                success: function (response) {
                    rooms = response;
                }
            });
        return rooms;  
    }
    document.getElementById('add-rooms-btn').addEventListener('click', ()=>{
        if(document.querySelector('.add-rooms').reportValidity()){
            for (let i = 0; i < roomCount.value; i++) {
                let insertRow = document.getElementById('table-rows').insertRow();

                let warning = "<p class='text-danger mb-1'>* Room no. "+(parseInt(roomFrom.value)+i)+" already exists.</p>"
                    input = "<input class='form-control pr-1 room-no' type='number' oninput='verifyRoomNo(this)' name='room_no[]' value='"+(parseInt(roomFrom.value)+i)+"' min='0' required>";
                    getRooms().forEach(room => {
                        if(room.room_no == parseInt(roomFrom.value)+i)
                            input = warning+input;
                    });
                insertRow.insertCell(0).innerHTML = input;
                insertRow.insertCell(1).innerHTML = "<input type='number' class='form-control floor' min='0' name='floor[]' value='"+document.getElementById('multiple_room_floor').value+"' required>";
                insertRow.insertCell(2).innerHTML = "<select class='form-control room-type' name='room_type[]' required>@foreach ($room_types as $room_type)<option>{{ucwords(strtolower($room_type->room_name))}}</option>@endforeach</select>";
                insertRow.insertCell(3).innerHTML = "<select class='form-control status' name='status[]' required><option>Open</option><option>Out Of Order</option></select>";
                insertRow.insertCell(4).innerHTML = "<div class='text-secondary py-2 clickable' onclick='removeRows(this)' style='font-size: 1.3em;'><i class='fa fa-trash'></i></div>"; 
                insertRow.querySelector('.room-type').value = document.getElementById('multiple_room_type').value;
                insertRow.querySelector('.status').value = document.getElementById('multiple_room_status').value;
            }  
            document.getElementById('add-rooms-table').classList.remove('d-none');
            document.getElementById('table-rows').querySelectorAll('.room-no').forEach((room_no,i) => {
                document.getElementById('table-rows').querySelectorAll('.room-no').forEach((room_nos,j) => {
                    if(room_nos.parentElement.querySelector('p') == null && i != j && j>i && room_no.value == room_nos.value)
                        $($.parseHTML("<p class='text-danger mb-1'>* Room no. "+room_no.value+" is not unique.</p>")).prependTo(room_nos.parentElement);
                });
            });
        }
    })
    function removeRows(trash) {
        trash.closest("tr").remove();
        if(document.getElementById('table-rows').rows.length == 0)
            document.getElementById('add-rooms-table').classList.add('d-none');
    }
    function verifyRoomNo(input) {
        if(input.parentElement.querySelector('p') != null)
            input.parentElement.querySelector('p').remove();
        getRooms().forEach(room => {
            if(room.room_no == input.value)
                $($.parseHTML("<p class='text-danger mb-1'>* Room no. "+input.value+" already exists.</p>")).prependTo(input.parentElement);
        });
        document.getElementById('table-rows').querySelectorAll('.room-no').forEach((room_no,i) => {
            document.getElementById('table-rows').querySelectorAll('.room-no').forEach((room_nos,j) => {
                if(room_nos.parentElement.querySelector('p') == null && i != j && j>i && room_no.value == room_nos.value)
                    $($.parseHTML("<p class='text-danger mb-1'>* Room no. "+room_no.value+" is not unique.</p>")).prependTo(room_nos.parentElement);
            });
        });
    }
</script>