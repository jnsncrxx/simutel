<div id="change_room" class="modal fade p-0" role="dialog">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 700px;">
        <div class="modal-content container">
            <div class="modal-body">
                <div class="page-header mb-1">
                    <h3 class="page-title mb-3 position-relative">Change Room
                        <span class="position-absolute text-secondary" style="cursor:pointer;right:-20px;top:-10px;" onclick="$('#change_room').modal('hide');">⨉</span>
                    </h3>
                    <h6 class="text-secondary">CURRENT BOOKING</h6>
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
                        <b>Guests</b>
                        <p class="guests"></p>
                    </div>
                    <div class="col-6">
                        <b>Room Type</b>
                        <p class="room_type"></p>
                    </div>
                    <div class="col-6">
                        <b>Room No</b>
                        <p class="room_no"></p>
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
                <form id="change-room-form" method="POST">
                    @csrf
                    <h6 class="text-secondary mt-4">CHANGE ROOM</h6>
                    <div class="border rounded p-3 row mx-0 justify-content-between">
                        <div>
                            <p><b>Room Type</b></p>
                            <select class="form-control select-room-type mt-1" name="room_type" style="width: fit-content;" oninvalid="this.setCustomValidity('Please select room type.')" oninput="this.setCustomValidity('')" required>
                            </select>
                        </div>
                        <div>
                            <p><b>Room No</b></p>
                            <select class="form-control select-room-no mt-1" name="room_no" style="width: fit-content;" oninvalid="this.setCustomValidity('Please select room number.')" oninput="this.setCustomValidity('')" required>
                            </select>
                        </div>
                        <div>
                            <p><b>Room Price</b></p>
                            <p class="new-price form-control mt-1" name="price"></p>
                            <input type="hidden" class="new-price-value" name="amount">
                        </div>
                    </div>
                    <div class="text-center pt-4">
                        <a class="btn btn-outline-secondary px-3 mr-2" onclick="$('#change_room').modal('hide')">Close</a>
                        <input type="submit" class="btn btn-secondary px-4" value="Confirm">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function changeRoom(id,reserved_by,check_in,check_out,adults,children,room_type,room_no,rate,price) {
        $('#change_room')[0].querySelector('.booking-id').innerHTML = id;
        $('#change_room')[0].querySelector('.reserved_by').innerHTML = reserved_by;
        $('#change_room')[0].querySelector('.dates').innerHTML = check_in + ' - ' + check_out;
        adults = (adults<2)? adults + ' Adult' : adults + ' Adults';
        children = (children<2)? children + ' Child' : children + ' Children';
        $('#change_room')[0].querySelector('.guests').innerHTML = adults + ', ' + children;
        $('#change_room')[0].querySelector('.room_type').innerHTML = room_type;
        $('#change_room')[0].querySelector('.room_no').innerHTML = room_no;
        $('#change_room')[0].querySelector('.rate').innerHTML = rate;
        $('#change_room')[0].querySelector('.price').innerHTML = price;
        let options = '<option value="">Select</option>';
        sendDates(check_in,check_out).room_types.forEach(roomType => {
            options += '<option>'+roomType.room_name+'</option>';
        });
        $('#change_room')[0].querySelector('.select-room-type').innerHTML = options;
        roomNoOptions();
        function roomNoOptions() {
            let roomType = dates.room_types.find(roomType=>roomType.room_name == $('#change_room')[0].querySelector('.select-room-type').value);

            let options = '<option value="">Select</option>', newPrice = 0;
            if(roomType){
                for(let key in roomType.available_room_no) {
                    options += '<option>'+roomType.available_room_no[key].room_no+'</option>';
                }
                $('#change_room')[0].querySelector('.new-price-value').value = roomType.rent;
                newPrice = roomType.rent;
            }
            $('#change_room')[0].querySelector('.select-room-no').innerHTML = options;
            $('#change_room')[0].querySelector('.new-price').innerHTML = '₱'+(Math.round(newPrice * 100) / 100).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }
        $('#change_room')[0].querySelector('.select-room-type').addEventListener('change', roomNoOptions);
        $('#change_room').modal('show');
        document.getElementById('change-room-form').action = "/change_room/"+id;
    }
</script>