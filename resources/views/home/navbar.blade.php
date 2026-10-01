<nav class="fixed-top bg-dark">
    <div class="navbar navbar-expand-lg navbar-dark bg-dark py-0 px-2 px-md-3 px-lg-4">
        <div class="container-fluid py-1 py-sm-0">
            <span id="mobile-cta-menu" class="mobile-menu d-lg-none"><i class="fa-solid fa-bars fa-lg"></i></span>
            <a class="navbar-brand p-0 ms-0 my-0 me-0 me-lg-4" href="/">PUPSJhotel</a>
            <div class="menu w-100 d-none d-lg-flex">
                <ul id="nav-menu" class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/rooms?currency={{(isset($_GET['currency']) && $_GET['currency'] != 'Points')?$_GET['currency']:'PHP'}}">Rooms</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link">About Us</a>
                    </li>
                </ul>
            </div>
                <span id="profile" class="profile">
                    <span id="profile-cta" class="profile-btn navbar-text"> 
            @auth
                        <img class="d-inline rounded-circle" src="{{(isset(Auth::user()->profile_photo_path))?'/storage/'.Auth::user()->profile_photo_path:'/admin/assets/img/guest.png'}}" alt="">
                    <span id="arrow" class="d-none d-lg-inline ps-1">{{Auth::user()->member->guest->first_name}} <i id="angle" class="bi bi-caret-down"></i></span>
                </span>
                <div id="profile-box" class="profile-box">
                    <div class="buttons p-3">
                        <div class="border bg-secondary border-warning border-2 rounded-1 p-2 text-light" style="box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;">
                            <h5 class="d-flex mb-0" style="font-family: inherit; width: max-content;"><i class="fa-solid fa-suitcase me-1"></i>Member┃<span style="font-family: initial">{{str_pad(Auth::user()->member->id,6,"0",STR_PAD_LEFT).'-SJ'}}</span></h5>
                            <h6 class="mt-2 mb-0" style="font-family: inherit;">Current point balance</h6>
                            <h3 style="margin: 0; font-family: initial;">{{Auth::user()->member->points}}</h3>
                            <h6 class="m-0" style="width:max-content; font-family: inherit;">Member since {{date('M d, Y', strtotime(Auth::user()->created_at))}}</h6>
                        </div>
                    </div>
                    <div class="glass" style="backdrop-filter: blur(10px);">
                        <div class="p-2">
                            <p class="px-2 py-1 m-0"><b>Account</b></p>
                            <a class="btn border-0 py-1 text-start rounded-0 text-dark btn-outline-secondary w-100" href='/user/profile'>Profile</a>
                            <a class="btn border-0 py-1 text-start rounded-0 text-dark btn-outline-secondary w-100"  href='/account?tab=membership'>Membership</a>
                            <p class="px-2 py-1 mb-0 mt-2"><b>Reservations</b></p>
                            <a class="btn border-0 py-1 text-start rounded-0 text-dark btn-outline-secondary w-100" href='/account?tab=reservations&reservations=upcoming'>Upcoming</a>
                            <a class="btn border-0 py-1 text-start rounded-0 text-dark btn-outline-secondary w-100" href='/account?tab=reservations&reservations=past'>Past</a>
                            <a class="btn border-0 py-1 text-start rounded-0 text-dark btn-outline-secondary w-100" href='/account?tab=reservations&reservations=cancelled'>Cancelled</a>
                        </div>
                        <form action="/logout" method="post" class="border-top">
                            @csrf
                            <button type="submit" class="btn border-0 text-center rounded-0 text-dark btn-outline-secondary w-100">Logout</button>
                        </form>
                    </div>
                </div>
            @else
                        <i class="fa-solid fa-circle-user fa-xl fa-lg-2xl"></i>
                    <span id="arrow" class="d-none d-lg-inline ps-1">Sign In or Join <i id="angle" class="bi bi-caret-down"></i></span>
                </span>
                <div id="profile-box" class="profile-box text-center">
                    <div class="buttons p-4 px-5">
                        <h6>Welcome to Simutel!</h6>
                        <a class="btn btn-outline-secondary w-100 my-2" href="/login">Login</a>
                        <a class="btn btn-outline-secondary w-100" href="/register">Register</a>
                    </div>
                    <div class="glass" style="backdrop-filter: blur(4px);"></div>
                </div>
            </span>
            @endauth
        </div>
    </div>
</nav>
<div class="container-fluid fixed-top cover" id="cover"></div>
<div class="p-3 pt-1 pt-md-0 mobile-nav">
    <div class="border-bottom pb-1 pb-md-0">
        <div class="d-flex">
            <div class="align-self-center"><h3 id="mobile-cta-exit" style="cursor: pointer; color: dimgray; margin:0">⨉</h3></div>
            <div class="navbar mx-auto p-0"><a class="navbar-brand p-0" href="#">PUPSJhotel</a></div>
        </div>
    </div>
    <ul>
        <li>
            <a href="#">Home</a>
        </li>
        <li>
            <a href="#">Rooms</a>
        </li>
        <li>
            <a href="#">Contact</a>
        </li>
        <li>
            <a href="#">About Us</a>
        </li>
    </ul>
</div>