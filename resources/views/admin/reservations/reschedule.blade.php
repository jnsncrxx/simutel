<div id="reschedule" class="modal fade p-0" role="dialog">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 700px;">
        <div class="modal-content container">
            <div class="modal-body">
                <div class="page-header mb-1">
                    <h3 class="page-title mb-3 position-relative">Edit Booking Dates
                        <span class="position-absolute text-secondary" style="cursor:pointer;right:-20px;top:-10px;" onclick="$('#reschedule').modal('hide');">⨉</span>
                    </h3>
                    <h6 class="text-secondary">CURRENT BOOKING DETAILS</h6>
                </div>
                <div class="border rounded p-3 row mx-0 justify-content-between mb-2" style="grid-row-gap: 15px;">
                    <div class="col-6">
                        <b>Booking No.</b>
                        <p>#<span class="booking-id"></span></p>
                    </div>
                    <div class="col-6">
                        <b>Reserved by</b>
                        <p class="reserved_by"></p>
                    </div>
                    <div class="col-6">
                        <b>Dates</b>
                        <p class="dates"></p>
                    </div>
                    <div class="col-6">
                        <b>Nights</b>
                        <p class="nights"></p>
                    </div>
                    <div class="col-6">
                        <b>Guests</b>
                        <p class="guests"></p>
                    </div>
                    <div class="col-6">
                        <b>Room Type</b>
                        <p class="room_type"></p>
                    </div>
                    <div class="col-6">
                        <b>Room Rate</b>
                        <p class="rate"></p>
                    </div>
                    <div class="col-6">
                        <b>Price</b>
                        <p class="price"></p>
                    </div>
                </div>
                <form id="reschedule-form" method="POST">
                    @csrf
                    <h6 class="text-secondary mt-4">EDIT BOOKING DATES</h6>
                    <div class="border rounded p-3 row mx-0 justify-content-between">
                        <div>
                            <p><b>Check-in</b></p>
                            <input class="form-control text-center check_in mt-1" type="date" name="check_in" min="{{date('Y-m-d')}}" onkeydown="return false" onclick="this.showPicker()"/>
                        </div>
                        <div>
                            <p><b>Check-out</b></p>
                            <input class="form-control text-center check_out mt-1" type="date" name="check_out" min="{{date('Y-m-d', strtotime('+1 day'))}}" onkeydown="return false" onclick="this.showPicker()"/>
                        </div>
                        <div>
                            <p><b>Nights</b></p>
                            <p class="new-nights form-control mt-1 text-center" name="nights"></p>
                        </div>
                        <div>
                            <p><b>New Price</b></p>
                            <p class="new-price form-control mt-1" name="price"></p>
                            <input type="hidden" class="new-price-value" name="amount">
                        </div>
                    </div>
                    <div class="text-center pt-4">
                        <a class="btn btn-outline-secondary px-3 mr-2" onclick="$('#reschedule').modal('hide')">Close</a>
                        <input type="submit" class="btn btn-secondary px-4" value="Confirm">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function reschedule(id,reserved_by,check_in,check_out,adults,children,room_type,rate,price) {
        $('#reschedule')[0].querySelector('.booking-id').innerHTML = id;
        $('#reschedule')[0].querySelector('.reserved_by').innerHTML = reserved_by;
        $('#reschedule')[0].querySelector('.dates').innerHTML = moment(check_in.split(" ")[0]).format('ll') + ' - ' + moment(check_out.split(" ")[0]).format('ll');
        $('#reschedule')[0].querySelector('.nights').innerHTML = Math.ceil(Math.abs(new Date(check_in) - new Date(check_out)) / (1000 * 60 * 60 * 24));
        adults = (adults<2)? adults + ' Adult' : adults + ' Adults';
        children = (children<2)? children + ' Child' : children + ' Children';
        $('#reschedule')[0].querySelector('.guests').innerHTML = adults + ', ' + children;
        $('#reschedule')[0].querySelector('.room_type').innerHTML = room_type;
        $('#reschedule')[0].querySelector('.rate').innerHTML = rate;
        $('#reschedule')[0].querySelector('.price').innerHTML = '₱'+(Math.round(price * 100) / 100).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        $('#reschedule')[0].querySelector('.new-nights').innerHTML = Math.ceil(Math.abs(new Date(check_in) - new Date(check_out)) / (1000 * 60 * 60 * 24));
        $('#reschedule')[0].querySelector('.new-price').innerHTML = '₱'+(Math.round(price * 100) / 100).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        
        const editCheckIn = $('#reschedule')[0].querySelector('.check_in');
            editCheckOut = $('#reschedule')[0].querySelector('.check_out');
            editCheckIn.value = '';
            editCheckOut.value = '';
            editCheckIn.setAttribute('min', '');
            editCheckOut.setAttribute('min', '');

        editCheckIn.setAttribute('min', check_in);
        editCheckIn.value = check_in;
        editCheckOut.value = check_out;
        checkOutMin(editCheckIn,editCheckOut);

        editCheckIn.addEventListener("change", () => {
            if(!editCheckIn.value)
                editCheckIn.value = check_in;

            checkOutMin(editCheckIn, editCheckOut);
            computeNewPrice();
        })
        editCheckOut.addEventListener("change", () => {
            if(!editCheckIn.value)
                editCheckIn.value = check_in;

            if(editCheckIn.value > editCheckOut.value || !editCheckOut.value)
                checkOutMin(editCheckIn,editCheckOut);
            computeNewPrice();
        })

        function computeNewPrice() {
            let newPrice = 0, newNights = 0, pricePerNight = 0;
            pricePerNight = price/Math.ceil(Math.abs(new Date(check_in) - new Date(check_out)) / (1000 * 60 * 60 * 24));
            newNights = Math.ceil(Math.abs(new Date(editCheckIn.value) - new Date(editCheckOut.value)) / (1000 * 60 * 60 * 24))
            newPrice = newNights * pricePerNight;
            $('#reschedule')[0].querySelector('.new-nights').innerHTML = newNights;
            $('#reschedule')[0].querySelector('.new-price').innerHTML = '₱'+(Math.round(newPrice * 100) / 100).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            $('#reschedule')[0].querySelector('.new-price-value').value = (Math.round(newPrice * 100) / 100).toFixed(2);
        }
        $('#reschedule').modal('show');
        document.getElementById('reschedule-form').action = "/reschedule/"+id;
    }
</script>