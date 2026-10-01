<style>
.primary .select2{
    width: 100% !important;
}
</style>
<form id="guest-form" class="row formtype border rounded mx-0 mb-4 pt-3">
    @csrf
    <div class="col-12">
        <div class="form-group primary">
            <label class="d-block">Search Member</label>
            <select id="select-primary" class="form-control">
                <option></option>
            </select>
        </div>
    </div>
    <div class='col-md-4'>
        <div class='form-group'>
            <label>First Name</label>
            <input id="first-name" class='form-control' type='text' pattern='[^0-9@]+' required> 
        </div>
    </div>
    <div class='col-md-4'>
        <div class='form-group'>
            <label>Last Name</label>
            <input id="last-name" class='form-control' type='text' pattern='[^0-9@]+' required> 
        </div>
    </div>
    <div class='col-md-4'>
        <div class='form-group'>
            <label>Email</label>
            <input id="email" class='form-control' type='email'> 
        </div>
    </div>
    <div class='col-md-4'>
        <div class='form-group'>
            <label>Contact</label>
            <input id="contact" class='form-control contact' type='tel' pattern='^(09)\d{9}$'> 
        </div>
    </div>
    <div class='col-md-4'>
        <div class='form-group'>
            <label>Birthday</label>
            <input id="birthday" class='form-control text-center' type='date' onkeydown='return false' onclick='this.showPicker()' 
            max='{{date('Y-m-d', strtotime('-18 year'))}}'> 
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Source</label>
            <input type="hidden" name="done" value="true">
            <select class="form-control" id="source" name="source" required>
                <option value="">Select</option>
                <option value="Reception">Reception</option>
                <option value="Email">Email</option>
                <option value="Call">Call</option>
            </select>
        </div>
    </div>
    <input type="hidden" id="primary-id">
</form>
<script>
const primaryFN = document.getElementById('first-name')
      primaryLN = document.getElementById('last-name')
      primaryEmail = document.getElementById('email')
      primaryContact = document.getElementById('contact')
      primaryBirthday = document.getElementById('birthday')
      primaryId = document.getElementById('primary-id');
      
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
        $('#select-primary').select2({
            data: membersData,
            placeholder: "Search by name or email",
            allowClear: true,
            matcher: matchCustom
        });
    }
});
$('#select-primary').on('select2:select', function (e) {
    let data = e.params.data;

    primaryId.value = data.details.id;
    primaryFN.value = data.details.first_name;
    primaryLN.value = data.details.last_name;
    primaryEmail.value = data.details.email;
    primaryContact.value = data.details.contact;
    primaryBirthday.value = data.details.birthday;

    primaryId.readOnly = true;
    primaryFN.readOnly = true;
    primaryLN.readOnly = true;
    primaryEmail.readOnly = true;
    primaryContact.readOnly = true;
    primaryBirthday.readOnly = true;
});

$('#select-primary').on('select2:clear', function (e) {
    primaryId.value = "";
    primaryId.value = "";
    primaryFN.value = "";
    primaryLN.value = "";
    primaryEmail.value = "";
    primaryContact.value = "";
    primaryBirthday.value = "";

    primaryId.readOnly = false;
    primaryFN.readOnly = false;
    primaryLN.readOnly = false;
    primaryEmail.readOnly = false;
    primaryContact.readOnly = false;
    primaryBirthday.readOnly = false;
});
</script>