<div id="edit_time" class="modal fade p-0" role="dialog">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 700px;">
        <div class="modal-content container">
            <div class="modal-body">
                <div class="page-header mb-1">
                    <h3 class="page-title mb-3 position-relative">Edit Check In / Check Out time
                        <span class="position-absolute text-secondary" style="cursor:pointer;right:-20px;top:-10px;" onclick="$('#edit_time').modal('hide');">⨉</span>
                    </h3>
                    <h6 class="text-secondary">BOOKING</h6>
                </div>
                <div class="border rounded p-3 row mx-0 justify-content-between mb-2">
                    <div class="d-flex">
                        <div class="rounded-circle p-1 mr-3" style="background: #eceef1;">
                           <i class="fa-regular fa-file-lines center-icon"></i>
                        </div>
                        <div>
                            <b>Booking No.</b>
                            <p>#<span class="booking-id"></span></p>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="rounded-circle p-1 mr-3" style="background: #eceef1;">
                           <i class="fas fa-user center-icon"></i>
                        </div>
                        <div class="mr-3">
                            <b>Reserved by</b>
                            <p class="reserved_by"></p>
                        </div>
                    </div>
                </div>
                <form id="edit-time-form" method="POST">
                    @csrf
                    <div class="border rounded p-3 row mx-0 justify-content-between">
                        <div>
                            <p><b>Check-in time</b></p>
                            <p><b class="date-in"></b></p>
                            <select class="form-control mt-2 time_in" name="time_in" required>
                                @for($i=0; $i<=24; $i++)
                                    <option>{{($i<10)?'0'.$i:$i}}:00</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <p><b>Check-out time</b></p>
                            <p><b class="date-out"></b></p>
                            <select class="form-control mt-2 time_out" name="time_out" required>
                                @for($i=0; $i<=24; $i++)
                                    <option>{{($i<10)?'0'.$i:$i}}:00</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="text-center pt-4">
                        <a class="btn btn-outline-secondary px-3 mr-2" onclick="$('#edit_time').modal('hide')">Close</a>
                        <input type="submit" class="btn btn-secondary px-4" value="Save">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function editTime() {
        $('#edit_time')[0].querySelector('.date-in').innerHTML = moment(booking.check_in.split(" ")[0]).format('MM-DD-YYYY');
        $('#edit_time')[0].querySelector('.time_in').value = booking.check_in.split(" ")[1].slice(0,5);
        $('#edit_time')[0].querySelector('.date-out').innerHTML = moment(booking.check_out.split(" ")[0]).format('MM-DD-YYYY');
        $('#edit_time')[0].querySelector('.time_out').value = booking.check_out.split(" ")[1].slice(0,5);
        $('#edit_time')[0].querySelector(".reserved_by").innerHTML = ucwords(booking.reserved_by.first_name) +' '+ ucwords(booking.reserved_by.last_name);
        $('#edit_time')[0].querySelector(".booking-id").innerHTML = booking.id;
        document.getElementById('edit-time-form').action = "/edit_booking_time/"+booking.id;
        $('#reservation_details').modal('hide');
        $('#edit_time').modal('show');
    }
</script>