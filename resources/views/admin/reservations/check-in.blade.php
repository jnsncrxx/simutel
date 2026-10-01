<style>
    .select-guest .select2{
        width: 100% !important;
    }
</style>
<div id="check_in" class="modal fade p-0" role="dialog" style="overflow: auto">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 700px;">
        <div class="modal-content container">
            <div class="modal-body">
                <div class="page-header mb-1">
                    <h3 class="page-title mb-3 position-relative">Confirm Check-in
                        <span class="position-absolute text-secondary" style="cursor:pointer;right:-20px;top:-10px;" onclick="$('#check_in').modal('hide');">⨉</span>
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
                            <p>#<span id="check-in-booking-id"></span></p>
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
                <form id="check-in-form" method="POST">
                    @csrf
                    <div class="border rounded p-3 row mx-0 justify-content-between">
                        <div>
                            <p><b>Check-in time</b></p>
                            <p><b class="date-in"></b></p>
                            <select class="form-control mt-2" name="time_in" required>
                                @for($i=0; $i<=24; $i++)
                                    <option>{{($i<10)?'0'.$i:$i}}:00</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <p><b>Check-out time</b></p>
                            <p><b class="date-out"></b></p>
                            <select class="form-control mt-2" name="time_out" required>
                                @for($i=0; $i<=24; $i++)
                                    <option>{{($i<10)?'0'.$i:$i}}:00</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <h6 class="text-secondary mt-4">GUESTS DETAILS</h6>
                    <div class="border rounded p-3 row mx-0 justify-content-between guest-details">
                    </div>
                    <h6 class="text-secondary mt-4">ROOM</h6>
                    <div class="border rounded p-3 row mx-0 justify-content-between">
                        <div>
                            <p><b>Room Type</b></p>
                            <p class="room-type"></p>
                        </div>
                        <div>
                            <p><b>Room No</b></p>
                            <select class="form-control select-room-no" name="room_no" style="width: fit-content;" oninvalid="this.setCustomValidity('Please select room number.')" oninput="this.setCustomValidity('')" required>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="status" value="Checked-in">
                    <div class="text-center pt-4">
                        <a class="btn btn-outline-secondary px-3 mr-2" onclick="$('#check_in').modal('hide')">Close</a>
                        <input type="button" class="btn btn-secondary px-4" value="Confirm" onclick="
                            Array.from(document.getElementById('check-in-form').querySelectorAll('[required]')).some(input => {
                                if(!input.value){
                                    if(input.parentElement.parentElement.parentElement.parentElement.children[0].classList.contains('collapsed'))
                                        input.parentElement.parentElement.parentElement.parentElement.children[0].click();
                                    document.getElementById('check-in-form').reportValidity();
                                    return true;
                                }
                            })
                            if(document.getElementById('check-in-form').reportValidity())
                                document.getElementById('check-in-form').submit();
                        ">
                    </div>
                </form>
                <div class="copy d-none">
                    <div class="border rounded w-100 mb-2">
                        <div class="px-2 d-flex bg-secondary text-light collapsed" data-toggle="collapse" role="button" aria-expanded="false" onclick="(this.classList.contains('collapsed'))?this.querySelector('i').classList.replace('fa-angle-down','fa-angle-up'):this.querySelector('i').classList.replace('fa-angle-up','fa-angle-down')">
                        </div>
                        <div class="row border-top pt-3 pb-2 mx-0 collapse">
                            <div class="col-12">
                                <div class="form-group select-guest mb-2">
                                    <select class="form-control search-member">
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" class="member_id" name="member_id[]">
                            <div class='col-md-6'>
                                <div class='form-group mb-2'>
                                    <label>First Name <span class="text-danger">*</span></label>
                                    <input class='form-control first_name' name="first_name[]" type='text' pattern='[^0-9@]+' required> 
                                </div>
                            </div>
                            <div class='col-md-6'>
                                <div class='form-group mb-2'>
                                    <label>Last Name <span class="text-danger">*</span></label>
                                    <input class='form-control last_name' name="last_name[]" type='text' pattern='[^0-9@]+' required> 
                                </div>
                            </div>
                            <div class='col-md-5'>
                                <div class='form-group mb-2'>
                                    <label>Email</label>
                                    <input class='form-control email' name="email[]" type='email'> 
                                </div>
                            </div>
                            <div class='col-md-3'>
                                <div class='form-group mb-2'>
                                    <label>Contact</label>
                                    <input class='form-control contact' name="contact[]" type='tel' pattern='^(09)\d{9}$'> 
                                </div>
                            </div>
                            <div class='col-md-4'>
                                <div class='form-group mb-2'>
                                    <label>Birthday <span class="text-danger child d-none">*</span></label>
                                    <input class='form-control text-center birthday' name="birthday[]" type='date' onkeydown='return false' max='{{date('Y-m-d', strtotime('-18 year'))}}' onclick='this.showPicker()'> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function confirmCheckIn(id,room_no,date_in,date_out,room_type,adults,children) {
        $('#check_in')[0].querySelector('.date-in').innerHTML = moment(date_in.split(" ")[0]).format('MM-DD-YYYY');
        $('#check_in')[0].querySelector('.date-out').innerHTML = moment(date_out.split(" ")[0]).format('MM-DD-YYYY');
        $('#check_in')[0].querySelector(".room-type").innerHTML = room_type;
        $('#check_in')[0].querySelector(".adults").innerHTML = adults;
        $('#check_in')[0].querySelector(".children").innerHTML = children;

        $('#check_in')[0].querySelector(".guest-details").innerHTML = '';
        for (let i = 1; i <= adults+children; i++) {
            $('#check_in')[0].querySelector(".guest-details").appendChild($('#check_in')[0].querySelector(".copy").children[0].cloneNode(true));
        }
        const guestFormToggles = $('#check_in')[0].querySelector(".guest-details").querySelectorAll("[data-toggle='collapse']")
            guestForms = $('#check_in')[0].querySelector(".guest-details").querySelectorAll(".collapse");
            guestFormToggles.forEach((toggle,index) => {
                if(index < adults){
                    toggle.setAttribute("data-target", "#adultForm"+index);
                    toggle.innerHTML = "GUEST #"+(index+1)+" (adult) <i class='fa-solid ml-auto my-auto fa-angle-down'></i>";
                    guestForms[index].setAttribute("id", "adultForm"+index);
                    guestForms[index].querySelector('.birthday').max='{{date('Y-m-d', strtotime('-18 year'))}}';
                }
                else {
                    toggle.setAttribute("data-target", "#childForm"+index);
                    toggle.innerHTML = "GUEST #"+(index+1)+" (child) <i class='fa-solid ml-auto my-auto fa-angle-down'></i>";
                    guestForms[index].querySelector('.search-member').parentElement.parentElement.remove();
                    guestForms[index].querySelector('.first_name').parentElement.parentElement.className = 'col-md-4';
                    guestForms[index].querySelector('.last_name').parentElement.parentElement.className = 'col-md-4';
                    guestForms[index].querySelector('.email').parentElement.parentElement.className = 'd-none';
                    guestForms[index].querySelector('.contact').parentElement.parentElement.className = 'd-none';
                    guestForms[index].querySelector('.birthday').parentElement.parentElement.className = 'col-md-4';
                    guestForms[index].setAttribute("id", "childForm"+index);
                    guestForms[index].querySelector('.birthday').max='{{date('Y-m-d')}}';
                    guestForms[index].querySelector('.birthday').min='{{date('Y-m-d', strtotime('-17 year'))}}';
                    guestForms[index].querySelector('.birthday').required = true;
                    guestForms[index].querySelector('.child').classList.remove('d-none');
                }
            });
            $.ajax({
                async: false,
                type: "GET",
                url: "get_members",
                success: function (members) {
                    let membersData = [];
                    for(let key in members){
                        membersData.push({id: members[key].id, text: members[key].first_name+' '+members[key].last_name + ' ('+ members[key].email +')', details: members[key]});
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
                            if (data.text.indexOf(params.term) > -1) {
                            var modifiedData = $.extend({}, data, true);

                            // You can return modified objects from here
                            // This includes matching the `children` how you want in nested data sets
                            return modifiedData;
                            }
                        }
                        // Return `null` if the term should not be displayed
                        return null;
                    }
                    guestForms.forEach(guestForm => {
                        $(guestForm.querySelector(".search-member")).select2({
                            data: membersData,
                            placeholder: "Search guest by name or email",
                            allowClear: true,
                            matcher: matchCustom
                        });
                    });
                }
            });
            guestForms.forEach(guestForm => {
                $(guestForm).on('show.bs.collapse', function () {
                    Array.from(guestForms).filter(x=> x!=guestForm).forEach(otherForms=>{
                        $(otherForms).collapse('hide');
                        otherForms.parentElement.querySelector('i').classList.replace('fa-angle-up','fa-angle-down');
                    });
                })
                $(guestForm.querySelector(".search-member")).on('select2:select', function (e) {
                    var data = e.params.data;
                    let firstName = guestForm.querySelector(".first_name")
                    lastName = guestForm.querySelector(".last_name")
                    email = guestForm.querySelector(".email")
                    contact = guestForm.querySelector(".contact")
                    birthday = guestForm.querySelector(".birthday")
                    memberId = guestForm.querySelector(".member_id");
            
                    memberId.value = data.details.id;
                    firstName.value = data.details.first_name;
                    lastName.value = data.details.last_name;
                    email.value = data.details.email;
                    contact.value = data.details.contact;
                    birthday.value = data.details.birthday;
                    memberId.readOnly = true;
                    firstName.readOnly = true;
                    lastName.readOnly = true;
                    email.readOnly = true;
                    contact.readOnly = true;
                    birthday.readOnly = true;
                });
                $(guestForm.querySelector(".search-member")).on('select2:clear', function (e) {
                    let firstName = guestForm.querySelector(".first_name")
                    lastName = guestForm.querySelector(".last_name")
                    email = guestForm.querySelector(".email")
                    contact = guestForm.querySelector(".contact")
                    birthday = guestForm.querySelector(".birthday")
                    memberId = guestForm.querySelector(".member_id");

                    memberId.value = "";
                    firstName.value = "";
                    lastName.value = "";
                    email.value = "";
                    contact.value = "";
                    birthday.value = "";
                    memberId.readOnly = false;
                    firstName.readOnly = false;
                    lastName.readOnly = false;
                    email.readOnly = false;
                    contact.readOnly = false;
                    birthday.readOnly = false;
                });
            });
        
        let roomType = sendDates(date_in,date_out).room_types.find(roomType=>roomType.room_name == room_type);
        let options = (room_no)?'<option>'+room_no+'</option>':'<option value="">Unassigned</option>';
        for(let key in roomType.available_room_no) {
            options += '<option>'+roomType.available_room_no[key].room_no+'</option>';
        }
        $('#check_in')[0].querySelector('.select-room-no').innerHTML = options;
        if(room_no){
            $("#check_in .select-room-no").html($(".select-room-no option").sort(function (a, b) {
                return a.text == b.text ? 0 : a.text < b.text ? -1 : 1
            }))
        }
        document.getElementById('check-in-booking-id').innerHTML = id;
        document.getElementById('check-in-form').action = "/update_reservation_status/"+id;
        $('#check_in').modal('show');
    }
</script>