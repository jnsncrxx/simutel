<div class="content shadow-sm row p-0 mb-3">
    <div class="picture col-12 col-md-6">
        <div id="carousel{{$room_type->id}}" class="carousel slide" data-bs-ride="true">
            <div class="carousel-indicators">
            @foreach($room_type->pictures as $index => $picture)
                <button type="button" data-bs-target="#carousel{{$room_type->id}}" data-bs-slide-to="{{$index}}" {{($index == 0)? "class=active" : ''}} aria-current="true" aria-label="Slide {{$index+1}}"></button>
            @endforeach
            </div>
            <div class="carousel-inner">
            @foreach($room_type->pictures as $index => $picture)
                <div class="carousel-item {{($index == 0)? 'active' : ''}}">
                <img src="{{$picture->picture}}" class="d-block w-100">
                </div>
            @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carousel{{$room_type->id}}" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carousel{{$room_type->id}}" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
    <div class="col-12 col-md-6 px-3 py-2 d-flex flex-column">
        <h1 class="name">{{strtoupper($room_type->room_name)}}</h1>
        @if(isset($room_type->views))
            <p class="view">({{strtoupper($room_type->views)}})</p>
        @endif
        <p class="description mb-2">{{$room_type->description}}</p>
        <a class="amenities mt-auto w-25" data-bs-toggle="collapse" href="#collapse{{$room_type->id}}" role="button" aria-expanded="false" aria-controls="collapseAmenities">
        AMENITIES <i class="amenities-angle bi bi-caret-down"></i>
        </a>
        <div class="collapse" id="collapse{{$room_type->id}}">
            @php
                $amenities = explode('+',$room_type->amenities);
            @endphp
            <ul class="mb-2">
                @foreach($amenities as $amenity)
                    <li>{{$amenity}}</li>
                @endforeach
            </ul>
        </div>
        @php
            $rent = $room_type->rent;
            $extra_adults = $_GET['adults']-$room_type->default_occupancy;
            if($_GET['adults']>$room_type->default_occupancy){
                $rent = $room_type->rent + ($room_type->extra_adult*$extra_adults);
            }
        @endphp
        <div class="d-flex">
            @if($_GET['currency'] == 'Points')
                <h3 class="price my-auto" style="font-family: initial">{{$room_type->points_required}} <span class="fs-4">Points per room</span></h3>
            @else
                <div class="d-grid">
                    <h4 class="price my-auto" style="font-family: initial">{{$_GET['currency'].' '.number_format(((float)$rent-(float)$rent*.2) * $currency_api[$_GET['currency']], 2, '.')}}
                        <span class="m-0 align-self-end">Member <i class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="To avail the member rate you need atleast 500 current point balance in your account."></i></span>
                        <style>
                            .tooltip-inner {
                                font-size: .9em;
                                max-width: 400px !important;
                                background-color: var(--gray) !important;
                            }
                        </style>
                    </h4>
                    <h5 class="price my-auto" style="font-family: initial">{{$_GET['currency'].' '.number_format((float)$rent * $currency_api[$_GET['currency']], 2, '.')}}
                        <span class="m-0 align-self-end">Standard</span>
                    </h5>
                </div> 
            @endif
            <button onclick="toDetails({{$room_type->id}},{{($_GET['currency'] == 'Points')?$room_type->points_required:$rent}})"  class="btn btn-warning rounded-0 ms-auto mt-auto" style="height: fit-content">BOOK NOW</button>
        </div>
        <p class="d-flex" style="color: gray">For 1 night | {{$room_type->default_occupancy}} {{($room_type->default_occupancy<2)? ' Adult': ' Adults'}} <span class="d-flex ms-2"><i class="bi bi-person d-flex my-auto"></i>maximum: {{$room_type->max_occupancy}}</span></p>
    </div>
</div> 