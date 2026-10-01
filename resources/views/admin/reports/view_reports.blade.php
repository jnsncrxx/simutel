@include('admin.css')
<style>
  td , th{
    overflow:hidden;
     white-space:nowrap
  }
</style>
<body>
    @include('admin.payments.payment_details')
	<div class="main-wrapper">
	@include('admin.header')
	@include('admin.sidebar')
    <div class="page-wrapper">
        <div class="content container-fluid">
          <div class="page-header">
            <div class="row align-items-center">
              <div class="col">
                <div class="mt-5">
                  <h4 class="card-title float-left mt-2">Reports</h4>
                </div>
              </div>
            </div>
          </div>
          <div class="row mx-0">
            <div class="col-md-4 pl-0 form-group">
                <label>Report type</label>
                <select class="form-control" name="report" required>
                        <option value="Standard">Cancellation</option>
                </select>
            </div>
            <div class="pr-2">
              <label>Date</label>
              <input type="text" id="date-range" class="form-control">
            </div>
          </div>
          <div class="row">
          @php
              function strContains($str1, $str2) {
                  return str_contains(strtolower($str1), strtolower($str2)) || str_contains(strtolower($str2), strtolower($str1));
              }
              if(isset($_GET["date"]) && $_GET["date"] != null){
                  $reservations = $reservations->filter(function ($item){
                    $period = new DatePeriod(
                      new DateTime(explode('-',$_GET["date"])[0]),
                      new DateInterval('P1D'),
                      new DateTime(explode('-',$_GET["date"])[1].' +1 day')
                    );
                    foreach ($period as $date) {
                      if($date->format('Y-m-d') >= date('Y-m-d', strtotime($item->check_in)) && $date->format('Y-m-d') <= date('Y-m-d', strtotime($item->check_out)))
                        return true;
                    }        
                  })->values();
              }
              if(isset($_GET["status"]) && $_GET["status"] != null){
                  $reservations = $reservations->filter(function ($item) {
                          return strContains($item->status, $_GET["status"]);
                  })->values();
              }
          @endphp
          </div>
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="datatable table table-stripped table-hover table-center mb-0">
                      <thead>
                        <tr>
                          <th>Confirmation #</th>
                          <th>Reserved By</th>
                          <th>Check-in</th>
                          <th>Check-out</th>
                          <th>Nights</th>
                          <th>Room Type</th>
                          <th>Rate</th>
                          <th>Amount</th>
                          <th>Cancellation Fee</th>
                          <th>Paid</th>
                          <th>Cancellation Date&Time</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($reservations as $reservation)
                          <tr>
                            <td>{{'PUPSJ-'.str_pad($reservation->id,6,"0")}}</td>
                            <td>{{ucwords($reservation->reserved_by->first_name).' '.ucwords($reservation->reserved_by->last_name)}}</td>
                            <td>{{$reservation->check_in}}</td>
                            <td>{{$reservation->check_out}}</td>
                            <td>{{date_diff(date_create($reservation->check_in), date_create($reservation->check_out))->format("%a")}}
                              {{-- @php $diff = date_diff(date_create($reservation->check_in), date_create($reservation->check_out))->format("%a");
                                  echo $diff;
                                  echo ($diff < 2)? ' Night' : ' Nights';
                              @endphp --}}
                            </td>
                            <td>{{$reservation->room_type}}</td>
                            <td>{{ucwords($reservation->rate.' Rate')}}</td>
                            @php
                                $totalCharges = 0;
                                foreach ($reservation->additional_charges as $charge) {
                                    $totalCharges += $charge->amount; 
                                }
                            @endphp
                            <td>₱{{number_format((float)$reservation->amount + (float)$totalCharges, 2, '.')}}</td>
                            <td>₱{{number_format((float)$reservation->amount*.5 + (float)$totalCharges, 2, '.')}}</td>
                            @php
                              $paid = 0;
                              foreach ($reservation->payments as $payment) {
                                  $paid += $payment->amount;
                              }
                              $reservation->paid = $paid;
                            @endphp
                            <td>₱{{number_format((float)$reservation->paid, 2, '.')}}</td>
                            <td>{{$reservation->cancellations->amount_charge}}</td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
@include('admin.script')
<script>
document.getElementById('side-reports').classList.add('active');

$('#date-range').daterangepicker({
    ranges: {
        'Today': [moment(), moment()],
        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        'This Month': [moment().startOf('month'), moment().endOf('month')],
        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    },
    "alwaysShowCalendars": true,
    "startDate": "{{explode('-',$_GET['date'])[0]}}",
    "endDate": "{{explode('-',$_GET['date'])[1]}}"
}, function(start, end, label) {
  let params = new URLSearchParams(new URL(window.location.href).search);
  params.delete('date');
  window.location.href = window.location.origin + window.location.pathname+"?"+params.toString()+"&date="+start.format('MM/DD/YYYY')+'-'+end.format('MM/DD/YYYY');
});
</script>
</body>
</html>