<div id="check_out" class="modal fade p-0" role="dialog">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 700px;">
        <div class="modal-content container">
            <div class="modal-body">
                <div class="page-header mb-1">
                    <h3 class="page-title mb-3 position-relative">Confirm Check-out
                        <span class="position-absolute text-secondary" style="cursor:pointer;right:-20px;top:-10px;" onclick="$('#check_out').modal('hide');">⨉</span>
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
                            <p>#<span id="check-out-booking-id"></span></p>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="rounded-circle p-1 mr-3" style="background: #eceef1;">
                           <i class="fa-solid fa-user-group center-icon"></i>
                        </div>
                        <div class="d-flex">
                            <div class="mr-3">
                                <b>Adults</b>
                                <p class="adults"></p>
                            </div>
                            <div>
                                <b>Children</b>
                                <p class="children"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <form id="check-out-form" method="POST">
                    @csrf
                    <div class="border rounded p-3 row mx-0 justify-content-between">
                        <div>
                            <p><b>Check-in</b></p>
                            <p><b class="date-in"></b></p>
                            <select class="form-control mt-2 time_in" name="time_in" required>
                                @for($i=0; $i<=24; $i++)
                                    <option value="{{($i<10)?'0'.$i:$i}}:00">{{($i<10)?'0'.$i:$i}}:00</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <p><b>Check-out</b></p>
                            <p><b class="date-out"></b></p>
                            <select class="form-control mt-2 time_out" name="time_out" required>
                                @for($i=0; $i<=24; $i++)
                                    <option>{{($i<10)?'0'.$i:$i}}:00</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="status" value="Checked-out">
                    <div class="text-center pt-4">
                        <a class="btn btn-outline-secondary px-3 mr-2" onclick="$('#check_out').modal('hide')">Close</a>
                        <input type="submit" class="btn btn-secondary px-4" value="Confirm">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function confirmCheckOut(id,date_in,date_out,adults,children) {
        $('#check_out')[0].querySelector('.date-in').innerHTML = moment(date_in.split(" ")[0]).format('MM-DD-YYYY');
        $('#check_out')[0].querySelector('.time_in').value = date_in.split(" ")[1].slice(0,5);
        $('#check_out')[0].querySelector('.date-out').innerHTML = moment(date_out.split(" ")[0]).format('MM-DD-YYYY');
        $('#check_out')[0].querySelector('.time_out').value = date_out.split(" ")[1].slice(0,5);
        $('#check_out')[0].querySelector(".adults").innerHTML = adults;
        $('#check_out')[0].querySelector(".children").innerHTML = children;
        $('#check_out').modal('show');
        document.getElementById('check-out-booking-id').innerHTML = id;
        document.getElementById('check-out-form').action = "/update_reservation_status/"+id;
    }
</script>