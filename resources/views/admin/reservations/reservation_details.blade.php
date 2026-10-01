<style>
    .guest-info{
        overflow: hidden;
    }
    .guest-info p{
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .table tbody tr {
        border-bottom: unset;
    }
</style>
<div id="reservation_details" class="modal fade p-0" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content container">
            <div class="modal-body">
                <div class="page-header">
                    <h3 class="page-title mb-3 position-relative">Reservation Details
                        <span class="position-absolute text-secondary" style="cursor:pointer;right:-20px;top:-10px;" onclick="$('#reservation_details').modal('hide');">⨉</span>
                    </h3>
                    <h4 class="mb-3 text-secondary">Booking #<span id="booking-id"></span></h4>
                    <div class="row mx-0">
                        <div class="col-md-4 pl-0">
                            <div class="form-group border rounded p-3">
                                <div class="d-flex mb-0 pt-2">
                                    <h6 class="text-secondary mb-0 mr-auto">PAYER</h6>
                                    <p onclick="changeBookingPayer()" class="text-info clickable">Change</p>
                                </div>
                                <div class="d-flex py-4">
                                    <img id="payer-img" class="d-inline rounded-circle my-auto" width="55" height="55">
                                    <div class="ml-3 text-secondary guest-info">
                                        <span id="payer-name" class="text-info clickable"></span>
                                        <p id="payer-contact"></p>
                                        <p id="payer-email"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group border rounded p-3 reservation-guests">
                                <div class="d-flex mb-0 pt-2">
                                    <h6 class="text-secondary mb-0 mr-auto">GUESTS</h6>
                                    <a onclick="addBookingExtraGuest()" id="extra_guest_btn" class="text-info clickable">Add extra guest</a>
                                </div>
                                <div class="d-flex py-4 border-bottom">
                                    <div class="position-relative my-auto">
                                        <img class="d-inline rounded-circle guest-img" width="55" height="55">
                                        <i class="fa fa-child position-absolute d-none" data-toggle="tooltip" title="Child" style="bottom: -2px; right: -6px; color: #74ce07;"></i>
                                    </div>
                                    <div class="ml-3 text-secondary guest-info">
                                        <span class="text-info guest-name clickable"></span>
                                        <p class="guest-contact"></p>
                                        <p class="guest-email"></p>
                                    </div>
                                    <div class="dropdown dropdown-action ml-auto">
                                        <a class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v ellipse_color clickable"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right"> 
                                            <input type="hidden" class="guest-id">
                                            <a class="dropdown-item clickable">Edit</a>
                                            <a class="dropdown-item clickable" onclick="removeBookingGuest(this.parentElement.children[0].value)">Remove</a>
                                            <a class="dropdown-item clickable" onclick="moveBookingGuest(this.parentElement.children[0].value)">Move Guest</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 pl-0">
                            <div class="form-group border rounded p-3">
                                <div class="d-flex mb-0">
                                    <h6 class="text-secondary my-auto mr-auto">DATES OF STAY</h6>
                                    <p class="p-2 border rounded">Room: <span id="room-no"></span></p>
                                </div>
                                <div class="d-flex py-3 border-bottom">
                                    <h6 class="my-auto mr-auto">Check-in:</h6>
                                    <h5 id="check-in-time" onclick="editTime()" class="text-info my-auto clickable"></h5>
                                    <h5 id="check-in-date" class="text-secondary my-auto ml-3 clickable"></h5>
                                </div>
                                <div class="d-flex py-3 border-bottom">
                                    <h6 class="my-auto mr-auto">Check-out:</h6>
                                    <h5 id="check-out-time" onclick="editTime()" class="text-info my-auto clickable"></h5>
                                    <h5 id="check-out-date" class="text-secondary my-auto ml-3 clickable"></h5>
                                </div>
                                <div class="d-flex py-3">
                                    <h6 class="my-auto mr-auto">Nights:</h6>
                                    <h5 id="details-nights" class="my-auto"></h5>
                                </div>
                                <div class="d-flex py-3">
                                    <h6 class="my-auto mr-auto">Source:</h6>
                                    <h6 id="details-source" class="my-auto"></h6>
                                </div>
                                <div class="d-flex py-3">
                                    <h6 class="my-auto mr-auto">Status:</h6>
                                    <div class="dropdown dropdown-action"> 
                                        <a id="details-status" class="action-icon dropdown-toggle rounded-0 btn btn-sm" data-toggle="dropdown" aria-expanded="false">
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right shadow rounded-0 dropdown-menu-box"> 
                                            <button class="dropdown-item px-3 d-flex checked-in">
                                                <p class="my-auto">Change to <i class="fa-solid mx-2 fa-angle-right"></i></p>
                                                <p class="btn btn-sm bg-warning-light w-100">Checked-in</p>
                                            </button>
                                            <button class="dropdown-item px-3 d-flex checked-out">
                                                <p class="my-auto">Change to <i class="fa-solid mx-2 fa-angle-right"></i></p>
                                                <p class="btn btn-sm bg-primary-light w-100">Checked-out</p>
                                            </button>
                                            <button class="dropdown-item px-3 d-flex cancelled">
                                                <p class="my-auto">Change to <i class="fa-solid mx-2 fa-angle-right"></i></p>
                                                <p class="btn btn-sm bg-secondary text-light w-100">Cancelled</p>
                                            </button> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 px-0">
                            <div id="balance" class="form-group border rounded p-3">
                                <div class="mt-2">
                                    <h6 class="text-secondary pt-1">BALANCE</h6>
                                </div>
                                <div class="row mx-0">
                                    <div class="col-md-6 p-1">
                                        <div class="px-2 pt-1 border rounded">
                                            <p>Amount</p>
                                            <h5></h5>
                                        </div>
                                    </div>
                                    <div class="col-md-6 p-1">
                                        <div class="px-2 pt-1 border rounded">
                                            <p>Paid</p>
                                            <h5></h5>
                                        </div>
                                    </div>
                                    <div class="col-12 p-1">
                                        <div class="px-2 pt-1 border border-success rounded">
                                            <p>Balance</p>
                                            <h5></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group border rounded p-3">
                                <div class="mt-2">
                                    <h6 class="text-secondary pt-1">SPECIAL REQUESTS</h6>
                                    <div class="border rounded p-2 requests" style="min-height: 85px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mx-0">
                        <div class="col-6 pl-0">
                            <div class="form-group border rounded p-3" style="min-height: 180px">
                                <div class="d-flex mb-0 pt-2">
                                    <h6 class="text-secondary mb-0 mr-auto">PAYMENTS</h6>
                                    <a onclick="addBookingPayment()" class="text-info clickable">Add payment</a>
                                </div>
                                <div class="mt-2" style="overflow-x:auto;">
                                    <table class="table table-hover">
                                        <thead style="background: whitesmoke">
                                            <tr>
                                                <th>Date</th>
                                                <th>Payment Method</th>
                                                <th>Amount</th>
                                                <th>Receipt</th>
                                            </tr>
                                        </thead>
                                        <tbody id="payments">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 px-0">
                            <div id="additional" class="form-group border rounded p-3" style="min-height: 180px">
                                <div class="mt-2 row mx-0">
                                    <div class="col-6 p-0">
                                        <h6 class="text-secondary pt-1">ADDITIONAL CHARGES</h6>
                                    </div>
                                    <div class="col-6 p-0">
                                        <div class="d-flex ms-auto">
                                            <input type="checkbox" onclick="
                                            const additional = document.getElementById('additional');
                                                if(this.checked){
                                                    additional.querySelector('.charge').classList.add('d-none');
                                                    additional.querySelector('.custom').classList.replace('d-none','d-flex');
                                                }
                                                else{
                                                    additional.querySelector('.charge').classList.remove('d-none');
                                                    additional.querySelector('.custom').classList.replace('d-flex','d-none');
                                                }" 
                                            class="ml-auto mb-2 mr-1">
                                            <h6 class="text-secondary pt-1">CUSTOM</h6>
                                        </div>
                                    </div>
                                    <form class="col-12 p-0 d-flex">
                                            <select class="form-control mr-2 charge" required>
                                                <option value="">Select</option>
                                                <option value="2000">Laundry Service - PHP 2,000</option>
                                                <option value="1800">Massage Service (60 mins) - PHP 1,800</option>
                                                <option value="2700">Massage Service (90 mins) - PHP 2,700</option>
                                                <option value="1500">Extra Roll away Bed - PHP 1,500</option>
                                                <option value="75">Extra Bottled Water (250ml) - PHP 75</option>
                                                <option value="50">Mini Bar (Snack) - PHP 50</option>
                                                <option value="90">Mini Bar (Beverage) - PHP 90</option>
                                                <option value="4500">Wine/Champagne - PHP 4,500</option>
                                                <option value="1700">Bouquet of Flowers - PHP 1,700</option>
                                                <option value="1800">Flower Basket - PHP 1,800</option>
                                                <option value="2500">Box of Flowers - PHP 2,500</option>
                                                <option value="1200">Vase Arrangement - PHP 1,200</option>
                                            </select>
                                            <div class="d-none w-100 custom">
                                                <div class="col-9 p-0">
                                                    <input type="text" class="form-control description" placeholder="Description" required>
                                                </div>
                                                <div class="col-3 px-2">
                                                    <input type="number" class="form-control amount" placeholder="Amount" required>
                                                </div>
                                            </div>
                                        <button type="button" class="btn bg-info ml-auto text-light add-charges" ><i class="fa-solid fa-plus"></i></button>
                                    </form>
                                </div>
                                <table class="table table-center mb-3">
                                    <colgroup>
                                        <col style="width: 60%;">
                                        <col style="width: 30%;">
                                        <col style="width: 10%;">
                                    </colgroup>
                                    <thead>
                                        <tr>
                                        <th>Description</th>
                                        <th>Amount</th>
                                        <th></th>
                                        </tr>
                                    </thead>
                                    <tbody class="border table-rows">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="form-group border rounded p-3">
                        <div class="mt-2">
                            <h6 class="text-secondary pt-1">ACCOMODATIONS</h6>
                        </div>
                        <div style="overflow-x:auto;">
                            <table class="table">
                                <thead style="background: whitesmoke">
                                    <tr>
                                    <th>Dates</th>
                                    <th>Nights</th>
                                    <th>Room Type</th>
                                    <th>Room No.</th>
                                    <th>Room Rate</th>
                                    <th>Adults</th>
                                    <th>Children</th>
                                    <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody id="accomodations">
                                    <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid fixed-top d-flex vh-100 invisible" style="z-index: 1050">
        <div id="remove-modal" class="text-center m-auto bg-light px-4 py-3 border rounded shadow" style="max-width: 450px">
            <img id="remove-picture" width="55" height="55" />
            <h3 class="delete_class">Are you sure want to remove <span id="remove-name"></span> from this booking?</h3>
            <div class="mt-3">
                <a id="remove-cancel" class="btn btn-white" >Cancel</a>
                <a id="remove-btn" class="btn btn-danger">Remove</a>
            </div>
        </div>
    </div>
    <div class="container-fluid fixed-top d-flex vh-100 invisible" style="z-index: 1050">
        <div id="move-modal" class="text-center m-auto bg-light px-4 py-3 border rounded shadow" style="max-width: 450px">
                <h3 class="page-title mb-3 position-relative">Move guest to another booking</h3>
                <style>
                    .search-booking-form .select2{
                        width: 100% !important;
                    }
                </style>
                <div class="form-group text-left search-booking-form">
                    <h6 class="d-block">Booking</h6>
                    <select id="search-booking" class="form-control">
                        <option></option>
                    </select>
                </div>
            <div class="mt-4">
                <a id="move-cancel" class="btn btn-white">Cancel</a>
                <a id="move-btn" class="btn btn-primary">Move</a>
            </div>
        </div>
    </div>
</div>
<script>
let booking;
function reservationDetails(id, e) {
    if(e == 'session' || e.target.tagName == 'TD' || e.target.classList.contains('view')){
        $('#reservation_details').modal('show');
        let reservation;
        $.ajax({
            async: false,
            type: "GET",
            url: "get_reservations/"+id,
            success: function (reservations) {
                reservation = reservations.find(reservation=>reservation.id==id);
                let reservationsData = [];
                for(let key in reservations){
                    if(reservations[key].id != id){
                        reservationsData.push({id: reservations[key].id, text: "#" + reservations[key].id + " " + ucwords(reservations[key].reserved_by.first_name) + " " + ucwords(reservations[key].reserved_by.last_name)+' ('+ moment(reservations[key].check_in).format("MMM Do") +' - '+ moment(reservations[key].check_out).format("MMM Do") +')'});
                    }
                }
                function matchCustom(params, data) {
                        // If there are no search terms, return all of the data
                        if ($.trim(params.term) === '') {
                            if(data.text === '')
                            return {text: "Please enter 1 or more characters"};
                        }
                        else {
                            // Do not display the item if there is no 'text' property
                            if (typeof data.text === 'undefined') {
                                return null;
                            }

                            // `params.term` should be the term that is used for searching
                            // `data.text` is the text that is displayed for the data object
                            if (data.text.toLowerCase().indexOf(params.term.toLowerCase()) > -1) {
                            var modifiedData = $.extend({}, data, true);

                            // You can return modified objects from here
                            // This includes matching the `children` how you want in nested data sets
                            return modifiedData;
                            }
                        }
                        // Return `null` if the term should not be displayed
                        return null;
                }
                $('#search-booking').html('').select2({
                    data: reservationsData,
                    placeholder: "Search booking",
                    allowClear: true,
                    matcher: matchCustom
                });
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
            }
        });

        booking = reservation;
        document.getElementById('booking-id').innerHTML = reservation.id;

/*---------------------------------------PAYER-------------------------------------------------*/
        if(reservation.payer.member)
            document.getElementById('payer-img').src = (reservation.payer.member.user.profile_photo_path)?"/storage/"+reservation.payer.member.user.profile_photo_path:"admin/assets/img/guest.png";
        else
            document.getElementById('payer-img').src = "admin/assets/img/guest.png";
        document.getElementById('payer-name').innerHTML = ucwords(reservation.payer.first_name) +' '+ ucwords(reservation.payer.last_name);
        document.getElementById('payer-contact').innerHTML = reservation.payer.contact;
        document.getElementById('payer-email').innerHTML = reservation.payer.email;
/*---------------------------------------------------------------------------------------------*/

/*---------------------------------------DATES OF STAY-----------------------------------------*/
        document.getElementById('room-no').innerHTML = (reservation.room_no)?reservation.room_no:'Unassigned';
        document.getElementById('check-in-time').innerHTML = reservation.check_in.split(" ")[1].slice(0,5);
        document.getElementById('check-in-date').innerHTML = moment(reservation.check_in.split(" ")[0]).format('DD-MM-YYYY');
        document.getElementById('check-out-time').innerHTML = reservation.check_out.split(" ")[1].slice(0,5);
        document.getElementById('check-out-date').innerHTML = moment(reservation.check_out.split(" ")[0]).format('DD-MM-YYYY');
        document.getElementById('details-nights').innerHTML = Math.ceil(Math.abs(new Date(reservation.check_in) - new Date(reservation.check_out)) / (1000 * 60 * 60 * 24));
        document.getElementById('details-source').innerHTML = reservation.source;

        function hideElement(element,action) {
            if(action == 'hide' && element != '.dropdown-menu-box')
                $('#reservation_details')[0].querySelector(element).classList.replace('d-flex','d-none');
            else if(action != 'hide' && element != '.dropdown-menu-box')
                $('#reservation_details')[0].querySelector(element).classList.replace('d-none','d-flex');

            if(action == 'hide' && element == '.dropdown-menu-box')
                $('#reservation_details')[0].querySelector(element).classList.add('d-none');
            else if(action != 'hide' && element == '.dropdown-menu-box')
                $('#reservation_details')[0].querySelector(element).classList.remove('d-none');
        }
        document.getElementById('details-status').className = "action-icon dropdown-toggle rounded-0 btn btn-sm";
        if(reservation.status == 'Confirmed'){
            document.getElementById('details-status').className += " bg-success-light d-flex";
            hideElement('.dropdown-menu-box');
            hideElement('.checked-in');
            hideElement('.checked-out','hide');
            hideElement('.cancelled');
            document.getElementById('details-status').innerHTML = "<span class='mr-1'>"+reservation.status+"</span><i class='fa-solid pt-1 ml-auto my-auto fa-angle-down'></i>";
        }
        else if(reservation.status == 'Checked-in'){
            document.getElementById('details-status').className += " bg-warning-light d-flex";
            hideElement('.dropdown-menu-box');
            hideElement('.checked-in','hide');
            hideElement('.checked-out');
            hideElement('.cancelled');
            document.getElementById('details-status').innerHTML = "<span class='mr-1'>"+reservation.status+"</span><i class='fa-solid pt-1 ml-auto my-auto fa-angle-down'></i>";
        }
        else if(reservation.status == 'Due In'){
            document.getElementById('details-status').style.background = "#f9d3ff";
            document.getElementById('details-status').style.color = "violet";
            document.getElementById('details-status').classList.add('d-flex');
            hideElement('.dropdown-menu-box');
            hideElement('.checked-in');
            hideElement('.checked-out','hide');
            hideElement('.cancelled','hide');
            document.getElementById('details-status').innerHTML = "<span class='mr-1'>"+reservation.status+"</span><i class='fa-solid pt-1 ml-auto my-auto fa-angle-down'></i>";
        }
        else if(reservation.status == 'Due Out'){
            document.getElementById('details-status').className += " bg-info-light d-flex";
            hideElement('.dropdown-menu-box');
            hideElement('.checked-in','hide');
            hideElement('.checked-out');
            hideElement('.cancelled','hide');
            document.getElementById('details-status').innerHTML = "<span class='mr-1'>"+reservation.status+"</span><i class='fa-solid pt-1 ml-auto my-auto fa-angle-down'></i>";
        }
        else if(reservation.status == 'Checked-out'){
            document.getElementById('details-status').classList.add('bg-primary-light');
            hideElement('.dropdown-menu-box','hide');
            document.getElementById('details-status').innerHTML = reservation.status;
        }
        else if(reservation.status == 'Cancelled'){
            document.getElementById('details-status').classList.add('bg-secondary');
            document.getElementById('details-status').classList.add('text-light');
            hideElement('.dropdown-menu-box','hide');
            document.getElementById('details-status').innerHTML = reservation.status;
        }
        else if(reservation.status == 'No Show'){
            document.getElementById('details-status').classList.add('bg-danger-light');
            hideElement('.dropdown-menu-box','hide');
            document.getElementById('details-status').innerHTML = reservation.status;
        }
        $('#reservation_details')[0].querySelector('.checked-in').addEventListener('click',()=>{
            $('#reservation_details').modal('hide');
            confirmCheckIn(reservation.id,reservation.check_in,reservation.check_out,ucwords(reservation.room_type),reservation.adults,reservation.children);
        });
        $('#reservation_details')[0].querySelector('.checked-out').addEventListener('click',()=>{
            $('#reservation_details').modal('hide');
            confirmCheckOut(reservation.id,reservation.check_in,reservation.check_out,reservation.adults,reservation.children);
        });
        $('#reservation_details')[0].querySelector('.cancelled').addEventListener('click',()=>{
            $('#reservation_details').modal('hide');
            confirmCancel(reservation.id,ucwords(reservation.reserved_by.first_name) +' '+ ucwords(reservation.reserved_by.last_name));
        });
/*---------------------------------------------------------------------------------------------*/

/*---------------------------------------BALANCE-----------------------------------------------*/
        const balance = document.getElementById('balance').querySelectorAll('h5');
        totalCharges = 0;
        reservation.additional_charges.forEach(charge => {
            totalCharges += parseInt(charge.amount);
        });
        balance[0].innerHTML = '₱'+(Math.round((parseInt(reservation.amount) + parseInt(totalCharges)) * 100) / 100).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        balance[1].innerHTML = '₱'+(Math.round(reservation.paid * 100) / 100).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        balance[2].innerHTML = '₱'+(Math.round((reservation.paid-reservation.amount) * 100) / 100).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        
        document.querySelector('.requests').innerHTML = reservation.requests;

/*---------------------------------------------------------------------------------------------*/

/*---------------------------------------PAYMENTS----------------------------------------------*/
        const payments = document.getElementById('payments');
        payments.innerHTML = '';
        reservation.payments.forEach(reservationPayment => {
            payments.innerHTML += "<tr onclick='paymentDetails("+reservationPayment.id+")' class='clickable'></tr>";
            payments.lastChild.insertCell(0).innerHTML = moment(reservationPayment.created_at).format('L');
            payments.lastChild.insertCell(1).innerHTML = reservationPayment.payment_method;
            payments.lastChild.insertCell(2).innerHTML = '₱'+(Math.round(reservationPayment.amount * 100) / 100).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            payments.lastChild.insertCell(3).innerHTML = 'Download';
        });
/*---------------------------------------------------------------------------------------------*/
        
/*---------------------------------------ADDITIONALS-----------------------------------------*/
        chargesRows(reservation.additional_charges);
        additional.querySelector('.add-charges').addEventListener('click', ()=>{
            let description, amount;
            if(additional.querySelector('.custom').classList.contains('d-none')){
                description = additional.querySelector('.charge').options[additional.querySelector('.charge').selectedIndex].text.split(" - ")[0];
                amount = additional.querySelector('.charge').value;
                additional.querySelector('.charge').required = true;
                additional.querySelector('.description').required = false;
                additional.querySelector('.amount').required = false;
            }
            else{
                description = additional.querySelector('.description').value;
                amount = additional.querySelector('.amount').value;
                additional.querySelector('.charge').required = false;
                additional.querySelector('.description').required = true;
                additional.querySelector('.amount').required = true;
            }
            if(additional.querySelector('form').reportValidity()) {
                $.ajax({
                    async: false,
                    type: "POST",
                    url: "additional_charges/"+reservation.id,
                    data: {description: description, amount: amount},
                    success: function (response) {
                        chargesRows(response);
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });
            }
        });
/*---------------------------------------------------------------------------------------------*/

/*---------------------------------------ACCOMODATIONS-----------------------------------------*/
        const accomodations = document.getElementById('accomodations').querySelectorAll('td');
        accomodations[0].innerHTML = moment(reservation.check_in.split(" ")[0]).format('ll') +" - "+ moment(reservation.check_out.split(" ")[0]).format('ll');
        accomodations[1].innerHTML = Math.ceil(Math.abs(new Date(reservation.check_in) - new Date(reservation.check_out)) / (1000 * 60 * 60 * 24));
        accomodations[2].innerHTML = reservation.room_type;
        accomodations[3].innerHTML = (reservation.room_no)?reservation.room_no:'Unassigned';
        accomodations[4].innerHTML = reservation.rate;
        accomodations[5].innerHTML = reservation.adults;
        accomodations[6].innerHTML = reservation.children;
        accomodations[7].innerHTML = '₱'+(Math.round(reservation.amount * 100) / 100).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
/*---------------------------------------------------------------------------------------------*/
    
/*---------------------------------------GUESTS------------------------------------------------*/
    const reservationGuests = document.querySelector('.reservation-guests');
    if(document.getElementById('collapseGuests') && document.getElementById('collapseGuestsBtn')){
        document.getElementById('collapseGuests').remove();
        document.getElementById('collapseGuestsBtn').remove();
    }
    if(reservation.status == "Checked-in")
        document.getElementById('extra_guest_btn').style.visibility = "visible";
    else
        document.getElementById('extra_guest_btn').style.visibility = "hidden";    
    reservationGuests.querySelector('.guest-img').src = "";
    reservationGuests.querySelector('.guest-name').innerHTML = "";
    reservationGuests.querySelector('.guest-contact').innerHTML = "";
    reservationGuests.querySelector('.guest-email').innerHTML = "";
    reservationGuests.querySelector('.fa-child').classList.add('d-none');

    if(!reservation.guests.length){
        reservationGuests.children[1].classList.add('invisible');
        reservationGuests.children[1].classList.replace('py-4', 'py-3');
        document.getElementById('details-status').parentElement.parentElement.classList.replace('py-3', 'pt-2');
    }
    else if(reservation.guests.length <= 1)
        reservationGuests.children[1].classList.remove('border-bottom');
    else{
        reservationGuests.children[1].classList.remove('invisible');
        reservationGuests.children[1].classList.replace('py-3', 'py-4');
        document.getElementById('details-status').parentElement.parentElement.classList.replace('pt-2', 'py-3');
    }
    for(let i=1; i<reservation.guests.length; i++){
        if(i==1){
            reservationGuests.innerHTML += "<div id='collapseGuests' class='collapse'></div><a id='collapseGuestsBtn' class='btn w-100 bg-info text-light'></a>"
            document.getElementById('collapseGuests').appendChild(reservationGuests.children[1].cloneNode(true));
        }
        else
            document.getElementById('collapseGuests').appendChild(reservationGuests.children[1].cloneNode(true));
    }

    $('#collapseGuestsBtn').html("More Guests (" + (reservation.guests.length-1) + ")");
    $("#collapseGuestsBtn").click(function(){
        if($('#collapseGuests').hasClass('show'))
            $('#collapseGuestsBtn').html("More Guests (" + (reservation.guests.length-1) + ")");  
        else
            $('#collapseGuestsBtn').html("Hide Guests");

        $('#collapseGuests').collapse('toggle');
    });

    const guestImg = reservationGuests.querySelectorAll('.guest-img')
        guestName = reservationGuests.querySelectorAll('.guest-name')
        guestContact = reservationGuests.querySelectorAll('.guest-contact')
        guestEmail = reservationGuests.querySelectorAll('.guest-email')
        guestId = reservationGuests.querySelectorAll('.guest-id')
        guestChild = reservationGuests.querySelectorAll('.fa-child');
        let i=0;

        reservation.guests.forEach(guest => {
            if(guest.member)
                guestImg[i].src = (guest.member.user.profile_photo_path)?"/storage/"+guest.member.user.profile_photo_path:"admin/assets/img/guest.png";
            else
                guestImg[i].src = "admin/assets/img/guest.png";
            guestName[i].innerHTML = guest.first_name +' '+ guest.last_name;
            guestContact[i].innerHTML = guest.contact;
            guestEmail[i].innerHTML = guest.email;
            guestId[i].value = guest.id;
            var today = new Date();
            var birthDate = new Date(guest.birthday);
            var age = today.getFullYear() - birthDate.getFullYear();
            var m = today.getMonth() - birthDate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            if(age < 18)
                guestChild[i].classList.remove('d-none');
            i++;
        });
/*---------------------------------------------------------------------------------------------*/
    }
}
function chargesRows(charges) {
    totalCharges = 0;
    if(charges.length == 0)
        additional.querySelector('.table-rows').parentElement.classList.add('d-none');
    else {
        additional.querySelector('.table-rows').parentElement.classList.remove('d-none');
        additional.querySelector('.table-rows').innerHTML = "";
        charges.forEach(charge =>{
            let insertRow = additional.querySelector('.table-rows').insertRow();
            insertRow.insertCell(0).innerHTML = charge.description;
            insertRow.insertCell(1).innerHTML = '₱'+(Math.round(charge.amount * 100) / 100).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            insertRow.insertCell(2).innerHTML = "<button class='btn bg-danger ml-auto pb-1 pt-0 text-light' onclick='removeCharge(this,"+charge.id+")'>━</button>";
            totalCharges += parseInt(charge.amount);
        });
    }
    const balance = document.getElementById('balance').querySelectorAll('h5');
    balance[0].innerHTML = '₱'+(Math.round((parseInt(booking.amount) + parseInt(totalCharges)) * 100) / 100).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    balance[2].innerHTML = '₱'+(Math.round((booking.paid-(parseInt(booking.amount) + parseInt(totalCharges))) * 100) / 100).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    for (const row of document.querySelector('.booking_card').querySelectorAll("td")) {
        if (row.textContent.includes(booking.id)) {
            row.parentElement.querySelectorAll("td")[6].innerHTML = balance[0].innerHTML;
        }
    }
}

function removeCharge(removeCharge,id){
    $.ajax({
        async: false,
        type: "GET",
        url: "remove_charges/"+id,
        success: function (response) {
            chargesRows(response);
        },
        error: function(xhr, status, error) {
            console.log(xhr.responseText);
        }
    });
}
function addBookingExtraGuest() {
    $('#reservation_details').modal('hide');
    addExtraGuest(booking.id);
}
function removeBookingGuest(id) {
    const removeModal = document.getElementById('remove-modal')
        removePicture = document.getElementById('remove-picture')
        removeName = document.getElementById('remove-name')
        removeCancel = document.getElementById("remove-cancel")
        removeBtn = document.getElementById("remove-btn");

        let bookingGuest = booking.guests.find(guest=>guest.id==id);
        if(bookingGuest.member)
            removePicture.src = (bookingGuest.member.user.profile_photo_path)?bookingGuest.member.user.profile_photo_url:"admin/assets/img/guest.png";
        else
            removePicture.src = "admin/assets/img/guest.png";
        removeName.innerHTML = ucwords(bookingGuest.first_name) + " " + ucwords(bookingGuest.last_name);

        removeCancel.addEventListener('click', ()=>{
            removeModal.classList.remove('visible');
        });

        $('#reservation_details').on('hidden.bs.modal', function (e) {
            removeModal.classList.remove('visible');
        });

        removeBtn.href = "/remove_booking_guest/"+ booking.id+"?guestID="+id;

        removeModal.classList.add('visible');
}
function moveBookingGuest(id) {
    $("#search-booking").val(null).trigger("change"); 
    const moveModal = document.getElementById('move-modal')
        moveCancel = document.getElementById("move-cancel")
        moveBtn = document.getElementById("move-btn");

        moveCancel.addEventListener('click', ()=>{
            moveModal.classList.remove('visible');
        });

        $('#reservation_details').on('hidden.bs.modal', function (e) {
            moveModal.classList.remove('visible');
        });

        $("#search-booking").on('select2:select', function (e) {
            moveBtn.href = "/move_booking_guest/"+booking.id+"?guestID="+id+"&toBooking="+e.params.data.id;
        });

        moveModal.classList.add('visible');
}

function changeBookingPayer() {
    $('#reservation_details').modal('hide');
    changePayer(booking.id);
}
function addBookingPayment() {
    $('#reservation_details').modal('hide');
    addPayment(booking.id);
}
function paymentDetails(id) {
    sessionStorage.setItem('payment_id', id);
    window.location.href='payments';
}

</script>