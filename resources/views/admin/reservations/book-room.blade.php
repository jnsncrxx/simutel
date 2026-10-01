<link href="admin/assets/select2-4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
.table th, .table td {
    white-space: nowrap;
}
.table tbody tr {
    border-bottom: unset;
}
</style>
<div class="row formtype mx-0 mb-5 pt-3 border rounded">
    <div class="col-md-2">
        <div class="form-group">
            <label>Check-in</label>
            <input class="form-control text-center" type="date" id="check-in" name="check_in" value="{{old('check_in')}}" onkeydown="return false" onclick="this.showPicker()"/>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label>Check-out</label>
            <input class="form-control text-center" type="date" id="check-out" name="check_out" value="{{old('check_out')}}" onkeydown="return false" onclick="this.showPicker()"/>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label>Room Rate</label>
            <select class="form-control" id="rate" name="rate" required>
                    <option value="Standard">Standard</option>
                    <option value="Member">Member (less 20%)</option>
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label>Room Type</label>
            <select class="form-control" id="room-type" name="room_type" required>
            </select>
        </div>
    </div>
    <div class="col-md-1">
        <div class="form-group">
            <label>Rooms</label>
            <select id="room-count" class="form-control" name="room_count" required>
            </select>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group" style="margin-top:30px">
            <a id="add-room" class="form-control btn btn-secondary text-light">Add Booking</a>
        </div>
    </div>
</div>
<div style="overflow-x:auto;">
    <table id="book-table" class="table table-center d-none">
        <thead>
            <tr>
            <th>Check-in</th>
            <th>Check-out</th>
            <th>Nights</th>
            <th>Room Rate</th>
            <th>Room Type</th>
            <th>Room No.</th>
            <th>Adults</th>
            <th>Children</th>
            <th>Price <span id="nights"></span></th>
            <th></th>
            </tr>
        </thead>
        <tbody id="table-rows" class="border">
        </tbody>
    </table>
</div>
<script src="admin/assets/select2-4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>    
    const addRoom = document.getElementById('add-room')
          bookTable = document.getElementById('book-table')
          tableRows = document.getElementById("table-rows")
          checkIn = document.getElementById('check-in')
          checkOut = document.getElementById('check-out')
          rate = document.getElementById("rate")
          selectRoomType = document.getElementById('room-type')
          roomCount = document.getElementById('room-count')
          var dates, arrayDates = [], rows = [];

    checkIn.setAttribute('min', today);
    if(!checkIn.value)
        checkIn.value = today;
    checkOutMin(checkIn,checkOut);

    checkIn.addEventListener("change", () => {
        if(!checkIn.value)
            checkIn.value = today;

            checkOutMin(checkIn,checkOut);
        dates = sendDates(checkIn.value,checkOut.value);
        roomOptions();
    })
    checkOut.addEventListener("change", () => {
        if(!checkIn.value)
            checkIn.value = today;

        if(checkIn.value > checkOut.value || !checkOut.value)
            checkOutMin(checkIn,checkOut);

        dates = sendDates(checkIn.value,checkOut.value);
        roomOptions();
    })
/*--------------------------------------------------------------*/
/*---------------SEND POST REQUEST WITHOUT REFRESH---------------*/
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    dates = sendDates(checkIn.value,checkOut.value);
    function sendDates(checkInDate,checkOutDate){
        var getDates;
        $.ajax({
            async: false,
            type: "POST",
            url: "filter_available",
            data: {check_in:checkInDate, check_out:checkOutDate},
            success: function (dates) {
                let options = '';
                dates.room_types.forEach(roomType => {
                    options += '<option value="'+roomType.room_name+'">'+roomType.room_name+" - ₱"+(Math.round(roomType.rent * 100) / 100).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")+'</option>';
                });
                selectRoomType.innerHTML = options;
                getDates = dates;
            }
        });
        return getDates;
    };
/*---------------------------------------------------------------*/
    updateBookingDetails();
    function updateBookingDetails() {
        let roomNum = 0, totalPrice = 0, adultsCount = 0, childrenCount = 0;
        rows.forEach(row => {
            roomNum++;
            adultsCount = Number(adultsCount) + Number(row.adults);
            childrenCount = Number(childrenCount) + Number(row.children);
            totalPrice = Number(totalPrice) + Number(row.price);
        });
        document.getElementById('rooms').innerHTML = (roomNum<2)?roomNum + ' Room': roomNum + ' Rooms';
        adultsCount = (adultsCount<2)?adultsCount + ' adult': adultsCount + ' adults';
        childrenCount = (childrenCount<2)?childrenCount + ' child': childrenCount + ' children';
        document.getElementById('guests').innerHTML = adultsCount +', '+ childrenCount;
        document.getElementById('price').innerHTML = "₱" + (Math.round(totalPrice * 100) / 100).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
    roomOptions();
    function roomOptions(){
        let roomType = dates.room_types.find(roomType=>roomType.room_name == selectRoomType.value);
        let available_room_count = roomType.available_room_count;

        let options = '<option>0</option>';
        rows.forEach(row => {
            if(row.checkIn >= dates.check_in && row.checkOut <= dates.check_out || dates.check_in >= row.checkIn && dates.check_out <= row.checkOut){
                if(row.roomType == roomType.room_name){
                    available_room_count--;
                }
            }
        });
        if(available_room_count > 0)
            options = '';
        for(let i=1; i<=available_room_count; i++ ) {
            options += '<option>'+i+'</option>';
        }
        roomCount.innerHTML = options;
    }
    selectRoomType.addEventListener('change', roomOptions);
    
    function loadTableData(row) {
        let insertRow = tableRows.insertRow();
        let i=0;
        Object.keys(row).forEach(key => {
            if(key == 'adults'){
                insertRow.insertCell(i).innerHTML = "<select class='form-control select-adults w-auto'></select>";
                options = '';
                for (let i = 1; i <= dates.room_types.find(roomType=>roomType.room_name == selectRoomType.value).max_occupancy; i++) {
                    if(i == row['adults'])
                        options += '<option selected>'+i+'</option>';
                    else
                        options += '<option>'+i+'</option>';
                }
                insertRow.cells[i].querySelector('select').innerHTML = options;
            }
            else if(key == 'children'){
                insertRow.insertCell(i).innerHTML = "<select class='form-control select-children w-auto'></select>";
                options = '';
                for (let i = 0; i <= dates.room_types.find(roomType=>roomType.room_name == selectRoomType.value).max_occupancy-row['adults']; i++) {
                    if(i == row['children'])
                        options += '<option selected>'+i+'</option>';
                    else
                        options += '<option>'+i+'</option>';
                }
                insertRow.cells[i].querySelector('select').innerHTML = options;
            }
            else if(key == 'roomNo')
                insertRow.insertCell(i).innerHTML = "<select class='form-control select-room w-auto'></select>";
            else
                insertRow.insertCell(i).innerHTML = row[key];

            i++;
        });   
        insertRow.insertCell(9).innerHTML = "<div class='text-secondary py-2 trash clickable' onclick='removeRows(this)' style='font-size: 1.3em;'><i class='fa fa-trash'></i></div>"; 
    }

    addRoom.addEventListener('click', ()=> {
        for(let i=1; i<=roomCount.value; i++){
            checkInValue = new Date(checkIn.value);
            checkOutValue = new Date(checkOut.value);
            let nights = Math.ceil(Math.abs(checkInValue - checkOutValue) / (1000 * 60 * 60 * 24));
            let rent = 0, amount = 0, default_occupancy = 0;
            rent = dates.room_types.find(roomType=>roomType.room_name == selectRoomType.value).rent;
            if(rate.value == 'Member')
               rent -= rent*0.2;
            amount = rent * nights;

            vat = amount*.12;
            cht = amount*.02;
            service = amount*.1;

            amount += vat+cht+service;
            amount = (Math.round(amount * 100) / 100).toFixed(2);

            default_occupancy = dates.room_types.find(roomType=>roomType.room_name == selectRoomType.value).default_occupancy;
            loadTableData({checkIn: checkIn.value, checkOut: checkOut.value, nights: nights, rate: rate.value, roomType: selectRoomType.value, roomNo: '', adults: default_occupancy, children: 0, price: "₱" + (Math.round(amount * 100) / 100).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")});
            rows.push({checkIn: checkIn.value, checkOut: checkOut.value, rate: rate.value, roomType: selectRoomType.value, roomNo: null, adults: default_occupancy, children: 0, price: amount});
            bookTable.classList.remove('d-none');
        }

        roomOptions();
        if(arrayDates.length > 0){
            let exists = false;
            arrayDates.forEach(arrayDate => {
                if(arrayDate.check_in == dates.check_in && arrayDate.check_out == dates.check_out)
                    exists = true;
            });

            if(!exists)
                arrayDates.push(dates);
        }
        else
            arrayDates.push(dates);

        roomNo();
        guestsCount();
        updateBookingDetails();

    });
    function guestsCount() {
        const selectAdults = $('#add_reservation')[0].querySelectorAll(".select-adults")
              selectChildren = $('#add_reservation')[0].querySelectorAll(".select-children");
        for(let i=0; i<rows.length; i++){
            selectAdults[i].addEventListener('change',()=>{
                const roomType = arrayDates.find(arrayDate=>rows[i].checkIn == arrayDate.check_in && rows[i].checkOut == arrayDate.check_out).room_types.find(roomType=>rows[i].roomType == roomType.room_name);
                options = '';
                selectChildren[i].innerHTML = options;
                for (let j = 0; j <= roomType.max_occupancy-selectAdults[i].value; j++) {
                    if(rows[i].children <= roomType.max_occupancy-selectAdults[i].value && j == rows[i].children)
                        options += '<option selected>'+j+'</option>';
                    else if(rows[i].children >= roomType.max_occupancy-selectAdults[i].value && j == roomType.max_occupancy-selectAdults[i].value)
                        options += '<option selected>'+j+'</option>';
                    else
                        options += '<option>'+j+'</option>';
                }
                selectChildren[i].innerHTML = options;
                let nights = Math.ceil(Math.abs(new Date(rows[i].checkIn) - new Date(rows[i].checkOut)) / (1000 * 60 * 60 * 24));
                let rent = 0, amount = 0;
                rent = roomType.rent;
                if(rows[i].rate == 'Member')
                    rent -= rent*0.2;
                amount = rent * nights;

                vat = amount*.12;
                cht = amount*.02;
                service = amount*.1;

                amount += vat+cht+service;
                amount = (Math.round(amount * 100) / 100).toFixed(2);
                rows[i].price = amount;
                if(+selectAdults[i].value > roomType.default_occupancy)
                    rows[i].price = +rows[i].price + (roomType.extra_adult*(+selectAdults[i].value - roomType.default_occupancy));
                tableRows.rows[i].cells[8].innerHTML = "₱" + (Math.round(rows[i].price * 100) / 100).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                rows[i].adults = selectAdults[i].value;
                rows[i].children = selectChildren[i].value;
                updateBookingDetails();
            });
            selectChildren[i].addEventListener('change',()=>{
                rows[i].children = selectChildren[i].value;
                updateBookingDetails();
            });
        }
    }
    function removeRows(trash) {
        rows.splice(Array.from(tableRows.rows).indexOf(trash.parentElement.parentElement),1);
        trash.parentElement.parentElement.remove();
        roomOptions();
        roomNo();
        guestsCount();
        if(rows.length == 0)
            bookTable.classList.add('d-none');
        updateBookingDetails();
    }

    function roomNo() {
        const selectRoomNo = $('#add_reservation')[0].querySelectorAll(".select-room");
        roomNoOptions();
        for(let i=0; i<selectRoomNo.length; i++){
            selectRoomNo[i].addEventListener('change', ()=>{
                rows[i].roomNo = (selectRoomNo[i].value)?selectRoomNo[i].value:null;
                roomNoOptions();
            });
        }
        function roomNoOptions() {
            for(let i=0; i<selectRoomNo.length; i++){
                const roomType = arrayDates.find(arrayDate=>rows[i].checkIn == arrayDate.check_in && rows[i].checkOut == arrayDate.check_out).room_types.find(roomType=>rows[i].roomType == roomType.room_name);
                let options = '<option value="">Unassigned</option>';
                for(let key in roomType.available_room_no) {
                    let exists = rows.some(row=>(row.checkIn >= rows[i].checkIn && row.checkOut <= rows[i].checkOut || rows[i].checkIn >= row.checkIn && rows[i].checkOut <= row.checkOut) && row.roomNo == roomType.available_room_no[key].room_no && row.roomNo);
                    if(rows[i].roomNo == roomType.available_room_no[key].room_no)
                        options += '<option selected>'+roomType.available_room_no[key].room_no+'</option>';
                    else{
                        if(!exists)
                            options += '<option>'+roomType.available_room_no[key].room_no+'</option>';
                    }
                }
                selectRoomNo[i].innerHTML = options;
            }
        }
    }
</script>