<style>
 .tabs a {
    cursor: pointer;
    color: lightgray;
    font-size: 1.2em;
    font-weight: 500;
    padding: 10px 0;
 }
 .active-tab {
    color: dimgray !important;
    cursor: default !important;
    position: relative;
 }
 .active-tab::before {
    position: absolute;
    content: "";
    width: 100%;
    height: 3px;
    bottom: -2px;
    left: 0;
    margin-left: auto;
    right: 0;
    margin-right: auto;
    background: dimgray;
 }
</style>
<div id="add_reservation" class="modal fade p-0" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content container p-0">
            <div class="modal-body">
                <div class="page-header mb-1">
                    <div class="position-relative">
                        <h3 class="page-title">Add Reservation</h3>
                        <div class="border rounded px-2" style="background-color: #d4f9ff; width: fit-content">Booking details: <span class="d-inline-block"><span id="rooms"></span> ┃ <span id="guests"></span> ┃ <span id="price"></span></span></div>
                        <h3 class="position-absolute text-secondary clickable" style="right:-5px;top:-10px;" onclick="$('#add_reservation').modal('hide');">⨉</h3>
                    </div>
                </div>
                <div class="tabs d-flex text-center mb-3 border-bottom">
                    <a id="book-cta" class="col-6 active-tab">Book a room</a>
                    <a id="guest-cta" class="col-6 mx-auto">Primary Guest</a>
                </div>
                <div id="book-tab" style="display: contents;">
                    @include('admin.reservations.book-room')
                </div>
                <div id="guest-tab" style="display: none;">
                    @include('admin.reservations.primary-guest')
                </div>
                <div class="border-top text-center mt-5 pt-3">
                    <a id="cancel" class="btn btn-outline-secondary px-3 mr-2" onclick="$('#add_reservation').modal('hide')">Cancel</a>
                    <a id="previous" class="btn btn-secondary px-4 mr-2 d-none">Previous</a>
                    <a id="next" class="btn btn-secondary px-4 mr-2">Next</a>
                    <a id="book" class="btn btn-secondary px-4 d-none" onclick="addBooking()">Confirm</a>
                    <div id="book-spinner" class="btn d-none">
                        <span class="spinner-border text-secondary" style="height: 25px; width: 25px"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<form id="reservation-form" action="/admin_add_reservation" method="POST">
    @csrf
    <input type="hidden" name="done" value="true">
</form>
<script>
    const bookBtn = document.getElementById('book-cta')
          bookTab = document.getElementById('book-tab')
          guestBtn = document.getElementById('guest-cta')
          guestTab = document.getElementById('guest-tab')
          cancel = document.getElementById('cancel')
          previous = document.getElementById('previous')
          next = document.getElementById('next')
          book = document.getElementById('book')
          tabs = [[bookBtn,bookTab],[guestBtn, guestTab]];

          function showTab(from, to) {
            from[0].classList.remove('active-tab');
            from[1].style.display = 'none';
            to[0].classList.add('active-tab');
            to[1].style.display = 'contents';
            checkActive();
          }
          function checkActive() {
            if(bookBtn.classList.contains('active-tab')){
                previous.classList.add('d-none');
                book.classList.add('d-none');
                next.classList.remove('d-none');
                return tabs[0];
            }
            else if(guestBtn.classList.contains('active-tab')){
                previous.classList.remove('d-none');
                book.classList.remove('d-none');
                next.classList.add('d-none');
                return tabs[1];
            }
          }
          tabs.forEach(tab => {
            tab[0].addEventListener('click', ()=>{
                showTab(checkActive(), tab);
            });
          });

          function currentTab() {
            return tabs.findIndex(tab=>tab[0] === checkActive()[0]);
          }
          next.addEventListener('click', ()=>{
            showTab(checkActive(), tabs[currentTab()+1]);
          });
          previous.addEventListener('click', ()=>{
            showTab(checkActive(), tabs[currentTab()-1]);
          });

    function addBooking() {
        if(document.getElementById('guest-form').reportValidity() && rows.length != 0){
            document.getElementById('book').remove();
            document.getElementById('book-spinner').classList.remove('d-none');
            let primary = {primaryId: primaryId.value, primaryFN: primaryFN.value, primaryLN: primaryLN.value, primaryEmail: primaryEmail.value, primaryContact: primaryContact.value, primaryBirthday: primaryBirthday.value, source: document.getElementById('source').value};
            $.ajax({
                async: false,
                type: "POST",
                url: "admin_add_reservation",
                data: {primary: primary, bookings: rows},
                success: function () {
                    document.getElementById('reservation-form').submit();
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }
    }
</script>