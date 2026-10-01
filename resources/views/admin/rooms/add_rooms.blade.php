<div id="add_room" class="modal p-0" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="page-header">
                    <h3 class="page-title position-relative">Add Room
                        <span class="position-absolute text-secondary" style="cursor:pointer;right:-5px;top:-10px;" onclick="$('#add_room').modal('hide');">⨉</span>
                    </h3>
                </div>
                @error('room_no')
                <li class="text-danger">Room number already exists.</li>
                    <script>
                        sessionStorage.setItem("addRoom", "true");
                    </script>
                @enderror
                <form id="add-form" action="/add_room" method="POST">
                @csrf
                <div class="row formtype">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Room Number</label>
                            <input class="form-control" type="number" name="room_no" min="0" value="{{old('room_no')}}" required> 
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label>Room Type</label>
                            <select class="form-control" name="room_type" required>
                                @if(old('room_type') != null)
                                    @php 
                                        $output = '';
                                        foreach ($room_types as $room_type){
                                            $output .= '<option ' . ( old('room_type') == ucwords(strtolower($room_type->room_name)) ? 'selected' : '' ) . '>' . ucwords(strtolower($room_type->room_name)) . '</option>';
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
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Floor</label>
                            <input type="number" class="form-control" min="0" name="floor" value="{{old('floor')}}" required>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" name="status" required>
                                @if(old('status') != null)
                                    @php 
                                        $options = array('Open', 'Out Of Order');
                                        $output = '';
                                        for( $i=0; $i<count($options); $i++ ) {
                                            $output .= '<option ' . ( old('status') == $options[$i] ? 'selected' : '' ) . '>' . $options[$i] . '</option>';
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
                </div>
                <input type="submit" class="btn btn-primary ml-1" value="Add Room">
                </form>
            </div>
        </div>
    </div>
</div>