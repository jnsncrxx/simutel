<div id="edit_room" class="modal p-0" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="page-header">
                    <h3 class="page-title position-relative">Edit Room
                        <span class="position-absolute text-secondary" style="cursor:pointer;right:-5px;top:-10px;" onclick="$('#edit_room').modal('hide');">⨉</span>
                    </h3>
                </div>
                @error('edit_room_no')
                <li class="text-danger">Room number {{old('edit_room_no')}} already exists.</li>
                    <script>sessionStorage.setItem("idRoomNo", {{$message}});</script>
                @enderror
                <form id="edit-form" method="POST">
                @csrf
                    <div class="row formtype">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Room Number</label>
                                <input class="form-control" type="number" id="room_no" name="edit_room_no" min="0" required> 
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Room Type</label>
                                <select class="form-control" id="room_type" name="room_type" disabled>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Floor</label>
                                <input type="number" class="form-control" id="floor" min="0" name="floor" required>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control" id="status" name="status">
                                </select>
                            </div>
                        </div>
                    </div>
                    <input type="submit" class="btn btn-primary ml-1" value="Save">
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function edit(id){
        let room = getRoom(id);
        document.getElementById('room_no').value = room.room_no;
        document.getElementById('floor').value = room.floor;
        output = '';
        {!!$room_types!!}.forEach(room_type => {
            if(room_type.id == room.room_type_id)
                output += '<option selected>'+room_type.room_name+'</option>';
            else
                output += '<option>'+room_type.room_name+'</option>';
        });
        document.getElementById('room_type').innerHTML = output;
        options = ['Open', 'Out Of Order'];
            output = '';
            for(let i=0; i<options.length; i++) {
                if(options[i] == room.status)
                    output += '<option selected>'+options[i]+'</option>';
                else
                    output += '<option>'+options[i]+'</option>';
            }
        document.getElementById('status').innerHTML = output;
        document.getElementById('edit-form').action = "/update_room/" +id;
    };
</script>