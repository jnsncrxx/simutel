<div id="cancel_booking" class="modal fade p-0" role="dialog">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 700px;">
        <div class="modal-content container">
            <div class="modal-body">
                <div class="page-header mb-1">
                    <h3 class="page-title mb-3 position-relative">Cancel Booking
                        <span class="position-absolute text-secondary" style="cursor:pointer;right:-20px;top:-10px;" onclick="$('#cancel_booking').modal('hide');">⨉</span>
                    </h3>
                    <h6 class="text-secondary">BOOKING</h6>
                </div>
                <div class="border rounded p-3 row mx-0 justify-content-between mb-2">
                    <div class="d-flex">
                        <div class="rounded-circle p-1 mr-3" style="background: #eceef1;">
                           <i class="fa-regular fa-file-lines" style="display: table-cell;font-size: 25px;vertical-align: middle;text-align: center;height: 40px;width: 40px;"></i>
                        </div>
                        <div>
                            <b>Booking No.</b>
                            <p>#<span id="cancel-booking-id"></span></p>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="rounded-circle p-1 mr-3" style="background: #eceef1;">
                           <i class="fas fa-user" style="display: table-cell;font-size: 23px;vertical-align: middle;text-align: center;height: 40px;width: 40px;"></i>
                        </div>
                        <div class="mr-3">
                            <b>Reserved by</b>
                            <p class="reserved_by"></p>
                        </div>
                    </div>
                </div>
                <h5 class="mt-4 text-secondary text-center">Are you sure you want to cancel this booking?</h5>
                <form id="cancel-form" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="Cancelled">
                    <div class="text-center pt-4">
                        <a class="btn btn-outline-secondary px-3 mr-2" onclick="$('#cancel_booking').modal('hide')">Close</a>
                        <input type="submit" class="btn btn-secondary px-4" value="Cancel booking">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function confirmCancel(id,reserved_by) {
        $('#cancel_booking')[0].querySelector(".reserved_by").innerHTML = reserved_by;
        $('#cancel_booking').modal('show');
        document.getElementById('cancel-booking-id').innerHTML = id;
        document.getElementById('cancel-form').action = "/update_reservation_status/"+id;
    }
</script>