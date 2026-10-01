<div id="add_extra_guest" class="modal show fade p-0" role="dialog">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 700px;">
        <div class="modal-content container">
            <div class="modal-body">
                <div class="page-header">
                    <h3 class="page-title mb-3 position-relative">Add extra guest
                        <span class="position-absolute text-secondary" style="cursor:pointer;right:-20px;top:-10px;" onclick="$('#add_extra_guest').modal('hide');">⨉</span>
                    </h3>
                    <h4 class="text-secondary">Booking #<span id="add-guest-booking-id"></span></h4>
                </div>
                <form id="add-guest-form" method="POST">
                    @csrf
                    <div class="row border rounded mx-0 pt-3">
                        <div class="col-12">
                            <style>
                                .select-guest .select2{
                                    width: 100% !important;
                                }
                            </style>
                            <div class="form-group select-guest">
                                <label class="d-block">Search Member</label>
                                <select id="add-guest" class="form-control">
                                    <option></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group mb-2">
                                <input type="checkbox" class="child" name="child" value="true" onchange="
                                    if(this.checked){
                                    this.closest('form').querySelector('.birthday').max='{{date('Y-m-d')}}';
                                    this.closest('form').querySelector('.birthday').min='{{date('Y-m-d', strtotime('-17 year'))}}';
                                    }
                                    else {
                                        this.closest('form').querySelector('.birthday').max='{{date('Y-m-d', strtotime('-18 year'))}}';
                                        this.closest('form').querySelector('.birthday').min='';
                                    }
                                ">
                                <label>Child</label>
                            </div>
                        </div>
                        <div class='col-md-6'>
                            <div class='form-group'>
                                <label>First Name</label>
                                <input class='form-control first_name' name="first_name" type='text' pattern='[^0-9@]+' required> 
                            </div>
                        </div>
                        <div class='col-md-6'>
                            <div class='form-group'>
                                <label>Last Name</label>
                                <input class='form-control last_name' name="last_name" type='text' pattern='[^0-9@]+' required> 
                            </div>
                        </div>
                        <div class='col-md-4'>
                            <div class='form-group'>
                                <label>Email</label>
                                <input class='form-control email' name="email" type='email'> 
                            </div>
                        </div>
                        <div class='col-md-4'>
                            <div class='form-group'>
                                <label>Contact</label>
                                <input class='form-control contact' name="contact" type='tel' pattern='^(09)\d{9}$'> 
                            </div>
                        </div>
                        <div class='col-md-4'>
                            <div class='form-group'>
                                <label>Birthday</label>
                                <input class='form-control text-center birthday' name="birthday" type='date' onkeydown='return false' max='{{date('Y-m-d', strtotime('-18 year'))}}' onclick='this.showPicker()'> 
                            </div>
                        </div>
                        <input type="hidden" class="member_id" name="member_id">
                    </div>
                    <div class="text-center pt-5">
                        <a class="btn btn-outline-secondary px-3 mr-2" onclick="$('#add_guest').modal('hide')">Cancel</a>
                        <input type="submit" class="btn btn-secondary px-4" value="Add Guest">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    
    function addExtraGuest(id) {
        $('#add_extra_guest').modal('show');
        document.getElementById('add-guest-booking-id').innerHTML = id;
        document.getElementById('add-guest-form').action = "/add_extra_guest/"+id;
    }
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
            $('#add-guest').select2({
                data: membersData,
                placeholder: "Search by name or email",
                allowClear: true,
                matcher: matchCustom
            });
        }
    });
    $('#add-guest').on('select2:select', function (e) {
        var data = e.params.data;
        let child = $('#add_guest')[0].querySelector(".child")
        firstName = $('#add_guest')[0].querySelector(".first_name")
        lastName = $('#add_guest')[0].querySelector(".last_name")
        email = $('#add_guest')[0].querySelector(".email")
        contact = $('#add_guest')[0].querySelector(".contact")
        birthday = $('#add_guest')[0].querySelector(".birthday")
        memberId = $('#add_guest')[0].querySelector(".member_id");

        child.parentElement.parentElement.classList.add('d-none');
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
    $('#add-guest').on('select2:clear', function (e) {
        let child = $('#add_guest')[0].querySelector(".child")
        firstName = $('#add_guest')[0].querySelector(".first_name")
        lastName = $('#add_guest')[0].querySelector(".last_name")
        email = $('#add_guest')[0].querySelector(".email")
        contact = $('#add_guest')[0].querySelector(".contact")
        birthday = $('#add_guest')[0].querySelector(".birthday")
        memberId = $('#add_guest')[0].querySelector(".member_id");
        
        child.parentElement.parentElement.classList.remove('d-none');
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
</script>