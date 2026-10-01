<style>
    h1,h2,h3,h4,h5,h6, p {
        margin: 0;
    }
    #myProgress {
        width: 100%;
        background-color: #ddd;
    }

    #myBar {
        height: 15px;
        background-color: #c5a334;
        line-height: 30px;
        color: white;
    }
    .circle {
        background: #c5a334; 
        top: 50%;
        transform: translate(-50%, -50%);
        border: 2px solid var(--gold);
        color: white;
        width: 68px;
        line-height: 60px;
        font-size: 32px;
        font-family: initial;
    }
</style>
<div class="d-flex pt-3">
    @php
        $nights = 0;
        $reservations = $reservations->filter(function ($reservation) {
            if($reservation->status == 'Checked-out'){
                foreach ($reservation->guests as $guest) {
                    if($guest->id == Auth::user()->member->guest->id)
                        return true;
                }
            }
        })->values();  
        foreach ($reservations as $reservation) {
            $nights += date_diff(date_create($reservation->check_in), date_create($reservation->check_out))->format("%d");
        }
    @endphp
    <div class="p-3 text-light d-grid" style="background: #a08633; box-shadow: rgb(60 64 67 / 30%) 1px 5px 10px 1px, rgb(60 64 67 / 15%) 0px 2px 8px 1px">
        <h4 class="text-center mx-4">Membership</h4>
        <div class="mt-auto" style="line-height: 1em; width: max-content;">
            <h4>{{Auth::user()->member->guest->first_name.' '.Auth::user()->member->guest->last_name}}</h4>
            <p style="font-family: initial">Member┃{{str_pad(Auth::user()->member->id,6,"0",STR_PAD_LEFT).'-SJ'}}</p>
            <small>since {{date('F d, Y', strtotime(Auth::user()->created_at))}}</small>
        </div>
    </div>
    <div class="border w-100 ms-3 p-3 pe-4 pb-5 text-center" style="line-height: 1em; box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;">
        <h3 style="color: var(--gold); font-family: inherit;"><b>Track your milestone</b></h3>
        <p style="font-family: initial">You're on your way to the next milestone reward!</p>
        <div class="pt-5 pb-4 ms-4 me-5">
            <div id="myProgress" class="rounded-5 position-relative">
                <div id="myBar" class="rounded-5" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Total nights stayed: {{$nights}}"></div>
                <style>
                    .tooltip-inner {
                        background-color: var(--gray) !important;
                    }
                </style>
                <button type="button" onclick="milestoneModal(20)" class="rounded-circle circle position-absolute" style="left: 33.33333333333333%">20
                    <span class="position-relative"><span class="position-absolute fs-6" style="color: #c5a334; bottom: -160%;right: -8px;">nights</span></span>
                </button>
                <button type="button" onclick="milestoneModal(30)" class="rounded-circle circle position-absolute" style="left: 50%">30
                    <span class="position-relative"><span class="position-absolute fs-6" style="color: #c5a334; bottom: -160%;right: -8px;">nights</span></span>
                </button>
                <button type="button" onclick="milestoneModal(40)" class="rounded-circle circle position-absolute" style="left: 66.66666666666666%">40
                    <span class="position-relative"><span class="position-absolute fs-6" style="color: #c5a334; bottom: -160%;right: -8px;">nights</span></span>
                </button>
                <button type="button" onclick="milestoneModal(60)" class="rounded-circle circle position-absolute" style="left: 100%">60
                    <span class="position-relative"><span class="position-absolute fs-6" style="color: #c5a334; bottom: -160%;right: -8px;">nights</span></span>
                </button>
            </div>
        </div>
    </div>
</div>
<div class="row mx-0 text-center my-4" style="font-family: initial">
    <div class="col-4 border border-2 p-3">
        <h1 style="color: var(--gold); font-family: inherit;">{{Auth::user()->member->points}}</h1>
        <h5>Current Point Balance</h5>
    </div>
    <div class="col-4">
        <div class="border border-2 p-3 gx-2">
            <h1 style="color: var(--gold); font-family: inherit;">{{Auth::user()->member->total_points}}</h1>
            <h5>Total Earned Points</h5>
        </div>
    </div>
    <div class="col-4 border border-2 p-3">
        <h1 style="color: var(--gold); font-family: inherit;">{{$nights}}</h1>
        <h5>Total Nights Stayed</h5>
    </div>
</div>
<div class="modal fade" id="milestoneModal">
    <div class="modal-dialog modal-dialog-centered" style="max-width: fit-content;">
      <div class="modal-content px-2">
            <div class="modal-header px-1 py-2 border-0">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-1 text-center">
                <div class="mx-5" style="color: var(--gold)">
                    <h1 class="rounded-circle mx-auto mb-2" style="width: 70px;line-height: 60px; border: 4px solid var(--gold);">★</h1>
                    <h4><span class="nights" style="font-size: xx-large"></span> TOTAL NIGHTS STAYED</h4>
                    <h5 style="line-height: 1em">OR</h5>
                    <h4><span class="points" style="font-size: xx-large"></span> POINTS</h4>
                </div>
                <small style="font-family: initial">in a calendar year</small>
                <div class="border-top py-4 mt-3 d-flex">
                    <div class="free mx-auto text-start">

                    </div>
                </div>
            </div>
      </div>
    </div>
</div>
<script src="admin/assets/js/jquery-3.5.1.min.js"></script>
<script>
    i = 1;
    var bar = document.getElementById("myBar")
    var width = 0;
    var id = setInterval(frame, 10);
    function frame() {
      if (width >= {{$nights}}/60*100) {
        clearInterval(id);
        i = 0;
      } else {
        width++;
        bar.style.width = width + "%";
      }
    }
    function milestoneModal(num){
        const modal = document.getElementById('milestoneModal');
        modal.querySelector('.nights').innerHTML = num;
        modal.querySelector('.points').innerHTML = num + ',000';
        if(num == 20){
            modal.querySelector('.free').innerHTML = "<h5><i class='fa-regular fa-circle-check'></i> 1 Free Night (Rooms Category)</h5>";
        }
        else if(num == 30){
            modal.querySelector('.free').innerHTML = "<h5><i class='fa-regular fa-circle-check'></i> 2 Free Nights (Rooms Category)</h5>";
        }
        else if(num == 40){
            modal.querySelector('.free').innerHTML = "<h5><i class='fa-regular fa-circle-check'></i> 1 Free Nights (Rooms Category)</h5><h5><i class='fa-regular fa-circle-check'></i> 1 Suite Upgrade Award</h5>";
        }
        else{
            modal.querySelector('.free').innerHTML = "<h5><i class='fa-regular fa-circle-check'></i> 2 Free Nights (Rooms Category)</h5><h5><i class='fa-regular fa-circle-check'></i> 1 Suite Upgrade Award</h5>";
        }
        $('#milestoneModal').modal('show');
    }
</script>