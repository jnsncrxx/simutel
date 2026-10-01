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
        <a class="amenities mt-auto d-block" data-bs-toggle="collapse" href="#collapse{{$room_type->id}}" role="button" aria-expanded="false" aria-controls="collapseAmenities">
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
        <div class="d-flex align-items-center">
            <div class="d-grid">
                <h4 class="price my-auto" style="font-family: initial">{{$_GET['currency'].' '.number_format(((float)$room_type->rent-(float)$room_type->rent*.2) * $currency_api[$_GET['currency']], 2, '.')}}
                    <span class="m-0 align-self-end">Member</span>
                </h4>
                <h5 class="price my-auto" style="font-family: initial">{{$_GET['currency'].' '.number_format((float)$room_type->rent * $currency_api[$_GET['currency']], 2, '.')}}
                    <span class="m-0 align-self-end">Standard</span>
                </h5>
            </div>
            <button onclick="window.location.href='/choose?check_in={{date('Y-m-d')}}&check_out={{date('Y-m-d', strtotime('+1 day'))}}&rooms=1&adults=1&children=0&currency={{$_GET['currency']}}'" class="btn btn-warning rounded-0 ms-auto" style="height: fit-content">Check Availability</button>
        </div>
    </div>
</div>