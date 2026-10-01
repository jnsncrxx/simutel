<div id="no_show" class="modal fade p-0" role="dialog">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 600px;">
        <div class="modal-content container">
            <div class="modal-body">
                <div class="page-header mb-1">
                    <h3 class="mb-3 position-relative text-center text-secondary">Are you sure?
                        <span class="position-absolute text-secondary" style="cursor:pointer;right:-20px;top:-10px;" onclick="$('#no_show').modal('hide');">⨉</span>
                    </h3>
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
                <form id="no-show-form" method="POST">
                    @csrf
                    <p class="text-secondary"><b>Confirm selected action:</b></p>
                    <p class="text-secondary">Change status of booking #<span class="booking-id"></span> to <span class="btn btn-sm bg-danger-light">No Show</span></p>
                    <input type="hidden" name="status" value="No Show">
                    <div class="text-center pt-4">
                        <a class="btn btn-outline-secondary px-3 mr-2" onclick="$('#no_show').modal('hide')">No</a>
                        <input type="submit" class="btn btn-secondary px-4" value="Yes">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function confirmNoShow(id,reserved_by) {
        $('#no_show')[0].querySelector(".reserved_by").innerHTML = reserved_by;
        $('#no_show').modal('show');
        $('#no_show')[0].querySelectorAll('.booking-id')[0].innerHTML = id;
        $('#no_show')[0].querySelectorAll('.booking-id')[1].innerHTML = id;
        document.getElementById('no-show-form').action = "/update_reservation_status/"+id;
    }
</script>