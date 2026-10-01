<div id="add_payment" class="modal fade p-0" role="dialog">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 700px;">
        <div class="modal-content container">
            <div class="modal-body">
                <div class="page-header">
                    <h3 class="page-title mb-3 position-relative">Add payment
                        <span class="position-absolute text-secondary" style="cursor:pointer;right:-20px;top:-10px;" onclick="$('#add_payment').modal('hide');">⨉</span>
                    </h3>
                    <h4 class="text-secondary">Booking #<span id="add-payment-booking-id"></span></h4>
                </div>
                <form id="add-payment-form" method="POST">
                    @csrf
                    <div class="row border rounded mx-0 pt-3">
                       <div class="col-12">
                            <div class="form-group">
                                <label>Payment Method</label>
                                <select class="form-control" name="method" required>
                                        <option value="">Select</option>
                                        <option>Cash</option>
                                        <option>Visa</option>
                                        <option>Mastercard</option>
                                </select>
                            </div>
                       </div>
                       <div class="col-12">
                            <div class="form-group">
                                <label>Amount</label>
                                <input class="form-control" type="number" name="amount" required> 
                            </div>
                       </div>
                    </div>
                    <div class="text-center pt-5">
                        <a class="btn btn-outline-secondary px-3 mr-2" onclick="$('#add_payment').modal('hide')">Cancel</a>
                        <input type="submit" class="btn btn-secondary px-4" value="Add Payment">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function addPayment(id) {
        $('#add_payment').modal('show');
        document.getElementById('add-payment-booking-id').innerHTML = id;
        document.getElementById('add-payment-form').action = "/add_booking_payment/"+id;
    }
</script>