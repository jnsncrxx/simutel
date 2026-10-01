<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rooms</title>
    @include('home.css')
    @include('home.rooms.css')
</head>
<body>
@include('home.navbar')
<section class="hero d-flex">
</section>
<section class="description">
    <div class="container text-center my-3">
        <h3>Rooms</h3>
        <p class="m-auto">All 250 elegantly appointed guestrooms, Grand Club rooms and suites are adorned with honeyed wood paneling, maple hardwood floors, pristine richly veined gray-white marble bathrooms, a separate toilet area, spacious walk-in closet and huge floor-to-ceiling picturesque windows that provide a breathtaking view of the city skyline.</p>
        <div class="row w-100 mt-4 pb-3 d-none d-sm-flex text-center justify-content-center">
            <div class="col-6" style="border-right: 1px solid gray">
                <div class="row">
                    <i class="fa-solid fa-clock col-6"></i>
                    <div class="col-6"> 
                        <h4>CHECK-IN</h4>
                        <p>2:00 PM</p>
                    </div>
                </div>
            </div>
            <div class="col-6 me-4">
                <h4>CHECK-OUT</h4>
                <p>12:00 PM</p>
            </div>
        </div>
    </div>
</section>
<section class="rooms-section">
    <div class="container-fluid py-2">
        <div class="header row container border-bottom mx-auto px-0">
            <div class="col-6 d-flex justify-content-end">
                <a id="rooms-cta">Rooms</a>
            </div>
            <div class="col-6 d-flex justify-content-between pe-0">
                <a id="suites-cta">Suites</a>
                <a class="d-flex align-items-center">
                    <i class="fa-solid fa-coins fs-5 text ms-2 me-1"></i>
                    <h5 class="m-0">Currency:&nbsp;</h5>
                    <select class="form-select clickable" style="width: auto" onchange="window.location.href = 'rooms?currency='+this.value">
                        @php 
                            $response_json = file_get_contents('https://api.exchangerate-api.com/v4/latest/PHP');
                            if(false !== $response_json) {
                                try {
                                    $currency_api = (array) json_decode($response_json)->rates;
                                    ksort($currency_api);
                                }
                                catch(Exception $e) {
                                    $currency_api = (array) json_decode($response_json)->rates;
                                    ksort($currency_api);
                                }
                            }   
                            foreach ($currency_api as $key => $value){
                                echo ($key == $_GET['currency'])?'<option selected>':'<option>';
                                echo $key.'</option>';
                            }
                        @endphp
                    </select>
                </a>
            </div>
        </div>
        <div id="rooms" class="container py-3 mb-5">
        @foreach ($room_types as $room_type)
            @if(!str_contains(strtolower($room_type->room_name), 'suite') && $room_type->status == 'Active')
                @include('home.rooms.room')
            @endif
        @endforeach
        </div>
        <div id="suites" class="container py-3 mb-5">
        @foreach ($room_types as $room_type)
            @if(str_contains(strtolower($room_type->room_name), 'suite') && $room_type->status == 'Active')
                @include('home.rooms.room')
            @endif
        @endforeach
        </div>
    </div>
</section>
@include('home.navbar-script')
@include('home.rooms.script')
</body>
</html>
