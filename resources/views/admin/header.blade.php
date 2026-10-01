<div class="header">
    <div class="header-left">
        <a href="/redirect" class="logo"><span class="logoclass">PUPSJ HOTEL</span></a>
        <a href="/redirect" class="logo logo-small"><span class="logoclass">PUPSJ HOTEL</span></a>
    </div>
    <a href="javascript:void(0);" id="toggle_btn"> <i class="fe fe-text-align-left"></i> </a>
    <a class="mobile_btn" id="mobile_btn"> <i class="fas fa-bars"></i> </a>
    <ul class="nav user-menu">
        <li class="nav-item dropdown has-arrow">
            <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown"> <span class="user-img"><img class="rounded-circle" src="{{(isset(Auth::user()->profile_photo_path))?'/storage/'.Auth::user()->profile_photo_path:'/admin/assets/img/guest.png'}}" width="30" height="30" alt=""></span> </a>
            <div class="dropdown-menu">
                <div class="user-header">
                    <div class="avatar avatar-sm"> <img src="{{(isset(Auth::user()->profile_photo_path))?'/storage/'.Auth::user()->profile_photo_path:'/admin/assets/img/guest.png'}}" alt="" class="avatar-img rounded-circle"> </div>
                    <div class="user-text">
                        <h6>{{Auth::user()->employee->first_name .' '. Auth::user()->employee->last_name}}</h6>
                        <p class="text-muted mb-0 mt-2">{{Auth::user()->employee->role}}</p>
                    </div>
                </div> 
                <a class="dropdown-item" href="/user/profile">My Profile</a> 
                {{-- <a class="dropdown-item" href="">Account Settings</a> --}}
                <form action="/logout" method="post">
                    @csrf
                    <button type="submit" class="dropdown-item">Logout</button>
                </form></div>
        </li>
    </ul>
    <div class="top-nav-search">
        <form>
            <input type="text" class="form-control" placeholder="Search here">
            <button class="btn" type="submit"><i class="fas fa-search"></i></button>
        </form>
    </div>
</div>