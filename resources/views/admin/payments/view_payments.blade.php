@include('admin.css')
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
                  <h4 class="card-title float-left mt-2">Payments</h4>
                </div>
              </div>
            </div>
          </div>
          @if(session()->has('message'))
            @include('admin.alert-success')
          @endif
          <div class="row mx-0 search">
            <div class="col-md-4 pl-0 pr-2">
                <div class="form-group d-flex">
                    <input type="text" class="form-control" name="search" placeholder="&#xf002 Search" style=" font-family: 'Helvetica', FontAwesome, sans-serif;" value="@php if(isset($_GET["search"])) echo $_GET["search"]; @endphp"/>
                    <input type="button" onclick="validateForm(this.parentElement.querySelectorAll('input')[0])" class="btn btn-secondary ml-1" style="width:fit-content" value="Search"/>
                </div>
            </div>
            <div class="pr-2">
                <div class="dropdown dropdown-action date"> <a class="action-icon dropdown-toggle btn btn-outline-secondary" data-toggle="dropdown">Select Date <i class="fa-solid fa-angle-down"></i></a>
                    <div class="dropdown-menu"> 
                        <a class="btn btn-secondary dropdown-item today" onclick="getFilters(event.target.tagName,'date',this.innerHTML.toLowerCase())">Today</a>
                        <a class="btn btn-secondary dropdown-item 7" onclick="getFilters(event.target.tagName,'date',this.innerHTML.toLowerCase())">Last 7 Days</a>
                        <a class="btn btn-secondary dropdown-item 15" onclick="getFilters(event.target.tagName,'date',this.innerHTML.toLowerCase())">Last 15 Days</a>
                        <a class="btn btn-secondary dropdown-item 30" onclick="getFilters(event.target.tagName,'date',this.innerHTML.toLowerCase())">Last 30 Days</a>
                        <a class="dropdown-item custom">
                            <div class="btn p-0">Custom Date</div>
                            <input class="form-control text-center p-0 pr-2" type="date" onkeydown="return false" value="{{(isset($_GET['date']) && DateTime::createFromFormat('d-m-Y', $_GET['date']) !== false)?date('Y-m-d',strtotime($_GET['date'])):''}}"  onclick="this.showPicker()" onchange="getFilters(event.target.tagName,'date',this.value)">
                        </a> 
                    </div>
                </div>
            </div>
            <div class="pr-2">
              <div class="dropdown dropdown-action methods"> <a class="action-icon dropdown-toggle btn btn-outline-secondary" data-toggle="dropdown">Payment Method <i class="fa-solid fa-angle-down"></i></a>
                  <div class="dropdown-menu"> 
                      @php
                          $methods = array("Cash", "Visa", "Mastercard");
                      @endphp
                      @forEach($methods as $method)
                          <div class="dropdown-item">
                              <label class="mb-0 d-flex">
                                  <input type="checkbox" onclick="getFilters(event.target.tagName,'method',this.closest('.dropdown-menu'))" value="{{$method}}">&nbsp;{{$method}}
                              </label>
                          </div>
                      @endforeach
                  </div>
              </div>
            </div>
          </div>
          @php
              function strContains($str1, $str2) {
                  return str_contains(strtolower($str1), strtolower($str2)) || str_contains(strtolower($str2), strtolower($str1));
              }
              if(isset($_GET["search"]) && $_GET["search"] != null){
                  $payments = $payments->filter(function ($item) {
                          return 
                          $item->room_reservation_id == $_GET["search"] ||
                          $item->room_no == $_GET["search"] ||
                          strContains($item->guests->first_name, $_GET["search"]) || strContains($item->guests->last_name, $_GET["search"]);
                  })->values();
              }
              if(isset($_GET["date"]) && $_GET["date"] != null){
                  $range = null;
                  if($_GET["date"] == 'today')
                      $range = 0;
                  else if($_GET["date"] == 'last 7 days')
                      $range = 7;
                  else if($_GET["date"] == 'last 15 days')
                      $range = 15;
                  else if($_GET["date"] == 'last 30 days')
                      $range = 30;
                  $payments = $payments->filter(function ($item) use($range){
                      if(isset($range)){
                          for($i=0; $i<=$range; $i++){
                              $days = ($i<2)?' day ':' days ';
                              if(date('Y-m-d', strtotime('-'.$i.$days)) == date('Y-m-d', strtotime($item->created_at)))
                                  return true;
                          }
                      }
                      else {
                          if(date('Y-m-d', strtotime($_GET["date"])) == date('Y-m-d', strtotime($item->created_at)))
                              return true;
                      }
                  })->values();
              }
              if(isset($_GET["method"]) && $_GET["method"] != null){
                  $payments = $payments->filter(function ($item) {
                          return strContains($item->payment_method, $_GET["method"]);
                  })->values();
              }
          @endphp
          <div class="row">
            <div class="col-sm-12">
              <div class="card">
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="datatable table table-hover table-stripped">
                      <thead>
                        <tr>
                          <th>Payer</th>
                          <th>Payment for</th>
                          <th>Payment Date</th>
                          <th>Payment Method</th>
                          <th>Amount Paid</th>
                          <th>Receipt</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($payments as $payment)
                        <tr onclick="paymentDetails(event.target.tagName, {{$payment->id}})" class="clickable">
                            <td>{{ucfirst($payment->room_reservations->payer->first_name).' '.ucfirst($payment->room_reservations->payer->last_name)}}</td>
                            <td><a onclick="sessionStorage.setItem('booking_id', {{$payment->room_reservation_id}})" href="all_reservations">#{{'PUPSJ-'.str_pad($payment->room_reservation_id,6,"0")}}</a></td>
                            <td>{{$payment->created_at}}</td>
                            <td>{{$payment->payment_method}}</td>
                            <td>₱{{number_format((float)$payment->amount, 2, '.')}}</td>
                            <td>Download</td>
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
document.getElementById('side-payments').classList.add('active');

    if(sessionStorage.getItem("payment_id")){
        paymentDetails('TD',sessionStorage.getItem("payment_id"));
        sessionStorage.removeItem("payment_id");
    }

    @if(isset($_GET["date"]))
        fillFilterButton(document.querySelector('.search').querySelector('.date').querySelector('.dropdown-menu').querySelectorAll('a'), "{{$_GET['date']}}", document.querySelector('.search').querySelector('.date').querySelector('a'), 'date');
    @endif
    @if(isset($_GET["method"]))
        fillFilterButton(document.querySelector('.search').querySelector('.methods').querySelectorAll('[type="checkbox"]'), "{{ucwords($_GET['method'])}}", document.querySelector('.search').querySelector('.methods').querySelector('a'), 'method');
    @endif
</script>
</body>
</html>