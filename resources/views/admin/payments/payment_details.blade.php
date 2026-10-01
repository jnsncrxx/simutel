<div id="payment-modal" class="modal fade p-0" role="dialog">
    <div class="modal-dialog modal-dialog-centered" style="max-width: fit-content;">
        <div class="modal-content">
            <div class="modal-body">
                <div class="page-header">
                    <h3 class="page-title position-relative">Payment Details
                        <span class="position-absolute text-secondary" style="cursor:pointer;right:-5px;top:-10px;" onclick="$('#payment-modal').modal('hide');">⨉</span>
                    </h3>
                </div>
                <div class="row text-left my-2 mx-0 border rounded p-3" style="grid-row-gap: 20px;">
                    <div class="col-4 border-bottom pb-3">
                        <h5>Payment for</h5>
                        <a onclick="sessionStorage.setItem('booking_id', this.firstElementChild.getAttribute('value'))" href="all_reservations">#<span class="booking-id"></span></a>
                    </div>
                    <div class="col-4 border-bottom pb-3">
                        <h5>Payer</h5>
                        <p class="payer"></p>
                    </div>
                    <div class="col-4 border-bottom pb-3">
                        <h5>Email</h5>
                        <p class="email"></p>
                    </div>
                    <div class="col-4">
                        <h5>Payment Date</h5>
                        <p class="date"></p>
                    </div>
                    <div class="col-4">
                        <h5>Amount</h5>
                        <p class="amount"></p>
                    </div>
                    <div class="col-4">
                        <h5>Payment Method</h5>
                        <p class="method"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function paymentDetails(e,paymentID) {
        if(e == 'TD'){
            let payment;
            $.ajax({
                async: false,
                type: "GET",
                url: "get_payment/"+paymentID,
                success: function (details) {
                    payment = details;
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
            const paymentModal = document.getElementById('payment-modal');

            paymentModal.classList.add('visible');
            paymentModal.querySelector('.booking-id').innerHTML = "PUPSJ-"+pad('000000',payment.room_reservation_id,false);
            paymentModal.querySelector('.booking-id').setAttribute('value',payment.room_reservation_id);
            paymentModal.querySelector('.payer').innerHTML = ucwords(payment.payer.first_name) +' '+ ucwords(payment.payer.last_name);
            paymentModal.querySelector('.email').innerHTML = payment.payer.email;
            paymentModal.querySelector('.date').innerHTML = moment(payment.created_at).format('MM-DD-YYYY LTS');
            paymentModal.querySelector('.amount').innerHTML = '₱'+(Math.round(payment.amount * 100) / 100).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            paymentModal.querySelector('.method').innerHTML = payment.payment_method;

            $('#payment-modal').modal('show');
        }
    }
</script>