<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PUPSJ Hotel</title>
    @include('home.css')
</head>
<body>
    @include('home.navbar')
    <section class="hero d-flex flex-column justify-content-center text-center">
        <div class="welcome align-self-center">
            <h2>Welcome to</h2>
            <h1>PUPSJhotel</h1>
        </div>
        <div class="w-100" id="book-top"><button type="button" id="book-btn" class="book-btn btn btn-warning d-sm-none rounded-0 mx-auto">BOOK NOW</button></div>
        <div id="book-form" class="book-form container-fluid d-none d-sm-flex align-items-sm-end align-items-center justify-content-center">
            <form action="/choose" method="get">
                <div id="book-box" class="box mx-auto justify-content-center row">
                    <div class="d-sm-none pe-1 text-end">
                        <i id="book-exit" class="fa-solid fa-xmark-large text-end">x</i>
                    </div>
                    <div class="d-sm-flex w-100 px-sm-2 pb-sm-2 justify-content-center">
                        <div class="row w-100 m-0 p-0 pb-2 pb-sm-0">
                            <div class="book-date p-0 col-6">
                                <label for="check_in">Check-in:</label>
                                <input type="date" class="p-1 text-center" id="check-in" name="check_in" value="" onkeydown="return false" onclick="this.showPicker()"/>
                            </div>
                            <div class="book-date p-0 ps-1 col-6">
                                <label for="check_out">Check-out:</label>
                                <input type="date" class="p-1 text-center" id="check-out" name="check_out" value="" onkeydown="return false" onclick="this.showPicker()"/>
                            </div>
                        </div>
                        <div class="d-none d-sm-flex ms-2"><button type="submit" class="d-none d-sm-inline btn btn-warning rounded-0 mt-auto">BOOK NOW</button></div>
                    </div>
                    <div class="rooms row col-sm-3 pb-2 d-sm-none">
                        <div class="col-6 col-sm-12 d-flex align-items-center justify-content-center">
                            <label for="rooms">Rooms:</label>
                        </div>
                        <div class="col-6 col-sm-12 p-0 ps-1">
                            <span class="quantity d-flex mx-auto">
                                <span class="input-number-decrement" id="input-number-decrement">–</span>
                                <input class="input-number" name="rooms" type="number" value="1" min="1" required>
                                <span class="input-number-increment" id="input-number-increment">+</span>
                            </span>
                        </div>
                    </div>
                    <div class="adults row col-sm-3 pb-2 d-sm-none">
                        <div class="col-6 col-sm-12 d-flex align-items-center justify-content-center">
                            <label for="adults">Adults:</label>
                        </div>
                        <div class="col-6 col-sm-12 p-0 ps-1">
                            <span class="quantity d-flex mx-auto">
                                <span class="input-number-decrement" id="input-number-decrement">–</span>
                                <input class="input-number" name="adults" type="number" value="1" min="1" required>
                                <span class="input-number-increment" id="input-number-increment">+</span>
                            </span>
                        </div>
                    </div>
                    <div class="children row col-sm-3 pb-2 d-sm-none">
                        <div class="col-6 col-sm-12 d-flex align-items-center justify-content-center">
                            <label for="children">Children:</label>
                        </div>
                        <div class="col-6 col-sm-12 p-0 ps-1 ps-sm-0">
                            <span class="quantity d-flex mx-auto">
                                <span class="input-number-decrement" id="input-number-decrement">–</span>
                                <input class="input-number" name="children" type="number" value="0" min="0" required>
                                <span class="input-number-increment" id="input-number-increment">+</span>
                            </span>
                        </div>
                    </div>
                    <input type="hidden" name="currency" value="PHP">
                    <button type="submit" class="btn btn-warning rounded-0 d-sm-none">BOOK NOW</button>
                </div>
            </form>
        </div>
    </section>
    <section class="description">
        <div class="container py-5 my-3 text-center">
            <h1>A Grand Place to See and Be Seen</h1>
            <p class="m-auto py-3">Our hotel offers a unique combination of comfort and luxury. Experience many moments whether you are a businessman, backpacker, solitary traveler, or traveling with your family, our hotel will exceed all of your expectations with its superior service. PUPSJ Hotel is more than iust a hotel; it is a place to live and experience exciting activities.</p>
        </div>
    </section>
    <section class="home-amenities">
        <div class="container text-center">
            <h1>Amenities</h1>
            <div class="row gy-5 ms-1 me-3 d-flex justify-content-center">
                <div class="col-6 col-md-4 col-lg-5">
                    <img src="/home/images/Amenities/wifi.png" alt="wifi">
                    <h2>Free Internet Access</h1>
                </div>
                <div class="col-6 col-md-4 col-lg-5">
                    <img src="/home/images/Amenities/restaurant.png" alt="restaurant">
                    <h2>On-Site Restaurant</h1>
                </div>
                <div class="col-6 col-md-4 col-lg-5">
                    <img src="/home/images/Amenities/service.png" alt="service">
                    <h2>Room Service</h1>
                </div>
                <div class="col-6 col-md-4 col-lg-5">
                    <img src="/home/images/Amenities/gym.png" alt="gym">
                    <h2>Fitness Center</h1>
                </div>
                <div class="collapse row gy-5 m-0" id="collapseAmenities">
                    <div class="col-6 col-md-4 col-lg-5">
                        <img src="/home/images/Amenities/pool.png" alt="pool">
                        <h2>Pool</h1>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2 mx-lg-4">
                        <img src="/home/images/Amenities/spa.png" alt="spa">
                        <h2>Spa</h1>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2 mx-lg-4">
                        <img src="/home/images/Amenities/events.png" alt="events">
                        <h2>Events</h1>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2 mx-lg-4">
                        <img src="/home/images/Amenities/concierge.png" alt="concierge">
                        <h2>Concierge</h1>
                    </div>
                    <div class="col-12 col-md-4 col-lg-2 mx-lg-4">
                        <img src="/home/images/Amenities/facility.png" alt="facility">
                        <h2>Facilities</h1>
                    </div>
                </div>
                <button id="amenities-btn" class="btn btn-outline-secondary rounded-0 mx-auto" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAmenities" aria-expanded="false"aria-controls="collapseAmenities">
                  All Amenities &emsp;
                </button>
            </div>
        </div>
    </section>
    <section class="location">
        <div class="container header text-center my-5 pt-3"><h1>Our Location</h1></div>
        <div class="container details">
            <div class="row m-0">
                <div class="col-12 col-md-6 col-lg-7 p-0 "><iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15444.331091896334!2d121.0401643!3d14.5943591!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xf48b60882ff9710a!2sPolytechnic%20University%20of%20the%20Philippines%20-%20San%20Juan!5e0!3m2!1sen!2sph!4v1658576669690!5m2!1sen!2sph" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe></div>
                <div class="col-12 col-md-6 col-lg-4 ms-lg-4 align-self-center">
                    <div class="row p-3 gy-3 gy-lg-5 pe-lg-0">
                        <div class="col-12">
                            <h1>Address:</h1>
                            <p>223 Ortega St., cor. A. Mabini St. Brgy. Addition Hills, San Juan City, Metro Manila, Philippines 1500</p>
                        </div>
                        <div class="col-6">
                            <h1>Phone:</h1>
                            <p>+63 2 7738 5071</p>
                        </div>
                        <div class="col-6">
                            <h1>Email:</h1>
                            <p>sanjuan@pup.edu.ph</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="rooms">
        <div class="container-fluid p-0 mt-5 pt-2">
            <div class="container header text-center pb-5"><h1>Rooms</h1></div>
            <div class="row">
                <div class="main col-12 g-0">
                    <img src="/home/images/rooms/mobile-main-room.jpg" alt="main room">
                </div>
                <div class="col-md-6 room1 ms-md-4 mt-lg-5">
                    <img src="/home/images/rooms/room1.jpg" alt="room">
                </div>
                <div class="col-md-5 p-3 pt-md-3 ms-md-3 mx-lg-4 mt-lg-5 pt-lg-4 pt-xl-5">
                    <div class="col-12">
                        <p>Enjoy modern conveniences and spacious layouts without compromising style and elegance</p>
                    </div>
                    <div class="col-12">
                        <a class="btn btn-outline-secondary rounded-0 col-12 text-center" href="/rooms?currency={{(isset($_GET['currency']))?$_GET['currency']:'PHP'}}">VIEW ROOMS</a>
                    </div>
                </div>
                <div class="room2 col-lg-12 m-0 d-none d-lg-flex justify-content-end">
                    <div class="col-lg-6 shape border">
                    </div>
                    <div class="col-lg-6 img1">
                        <img src="/home/images/rooms/room2.jpg" alt="room">
                    </div>
                    <div class="col-lg-6 img2">
                        <img src="/home/images/rooms/room3.jpg" alt="room">
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- <section id="hotel" class="hotel">
        <div class="container g-0 pt-md-4">
            <div class="container header text-center my-5 pt-md-3"><h1>Our Hotel</h1></div>
            <div class="row g-0 gy-md-2 gy-lg-4">
                <div class="col-12 col-md-6 col-lg-12">
                    <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseClub" aria-expanded="false" aria-controls="collapseClub">
                      Grand Club
                    </button>
                    <div class="collapse" id="collapseClub">
                        <div class="card card-body">
                            <div class="row gy-md-2">
                                <img class="col-lg-8 m-lg-0" src="/home/images/facilities/club.jpg" alt="Grand Club">
                                <div class="col-lg-5 p-lg-5 align-self-lg-center">
                                    <h2 class="col-lg-12 d-none d-md-inline">Grand Club</h2>
                                    <p>Located on the 57th floor, the Grand Club provides unparalleled views of the Metro. Guests with exclusive suite and club floor access can enjoy a wide range of amenities, from all-day refreshments to complimentary one-hour use of the Boardroom.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-12">
                    <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePool" aria-expanded="false" aria-controls="collapsePool">
                      Pool
                    </button>
                    <div class="collapse" id="collapsePool">
                        <div class="card card-body">
                            <div class="row gy-md-2 d-lg-flex justify-content-lg-end">
                                <img class="col-lg-8 m-lg-0" src="/home/images/facilities/pool.jpg" alt="Pool">
                                <div class="left col-lg-5 p-lg-5 align-self-lg-center">
                                    <h2 class="col-lg-12 d-none d-md-inline">Pool</h2>
                                    <p class="pool">The stage is set for an idyllic urban escape at the 6th floor of PUPSJ Hotel, where you can step outdoors and take a dip on the heated pool amidst the vibrant city of San Juan.</p>
                                    <p>Pool hours are 6:00 AM to 8:00 PM</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-12">
                    <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSpa" aria-expanded="false" aria-controls="collapseSpa">
                      Spa
                    </button>
                    <div class="collapse" id="collapseSpa">
                        <div class="card card-body">
                            <div class="row gy-md-2">
                                <img class="col-lg-8 m-lg-0" src="/home/images/facilities/spa.jpg" alt="Spa">
                                <div class="col-lg-5 p-lg-5 align-self-lg-center">
                                    <h2 class="col-lg-12 d-none d-md-inline">Spa</h2>
                                    <p>Experience expertly practiced spa experiences to rekindle your well-being. Our Spa offers soulful treatments that are a blend of culture and indigenous technology, all conducted in private treatment rooms with ensuite bathrooms.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-12">
                    <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseGym" aria-expanded="false" aria-controls="collapseGym">
                      24/7 Fitness Center
                    </button>
                    <div class="collapse" id="collapseGym">
                        <div class="card card-body">
                            <div class="row gy-md-2 d-lg-flex justify-content-lg-end">
                                <img class="col-lg-8 m-lg-0" src="/home/images/facilities/gym.jpg" alt="Gym">
                                <div class="left col-lg-5 p-lg-5 align-self-lg-center">
                                    <h2 class="col-lg-12 d-none d-md-inline">Gym</h2>
                                    <p>Discover a new fitness hub that will accommodate your training routine how you want it, when you want it. Our 24-hour fitness center will keep you on top of your game with high-tech cardio and strengthening equipment, a customized fitness plan and a personal trainer available on-site.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDining" aria-expanded="true" aria-controls="collapseDining">
                      Dining
                    </button>
                    <div class="collapse show" id="collapseDining">
                        <div class="card card-body">
                            <div class="row gy-md-2">
                              <img class="col-md-6 ms-md-0 col-lg-8" src="/home/images/facilities/dining.jpg" alt="Dining">
                                <div class="col-md-5 p-lg-5 align-self-lg-center">
                                    <h2 class="col-md-12 d-none d-md-inline">Dining</h2>
                                    <p>PUPSJ Hotel is a landmark building that offers a myriad of many exciting experiences and dramatic restaurant concepts that include three unique concept restaurants, a timeless bar and several dining options to ignite your palate</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}
    <footer>
        <div class="details text-center p-2">
            <p>We are located at 223 Ortega St., cor. A. Mabini St. Brgy. Addition Hills, San Juan City, Metro Manila, Philippines 1500</p>
            <p>Give us a call: +63 2 7738 5071 or Reach us out: sanjuan@pup.edu.ph</p>
        </div>
        <div class="container-fluid pages py-5 px-4">
            <div class="row g-4 justify-self-center">
                <div class="col logo col-md-4"><h1>PUPSJ<span> HOTEL</span></h1></div>
                <div class="col-12 col-md-3">
                    <a href="#">Reservations</a>
                    <a href="#">Contact Us</a>
                    <a href="#">About Us</a>
                    <a href="#">Privacy Policy</a>
                </div>
                <div class="col-12 col-md-5">
                    <a href="#">Customer Service</a>
                    <a href="#">Careers</a>
                    <a href="#">FAQs</a>
                    <a href="#">Terms and Conditions</a>
                </div>
                <div class="col-12 socials border-top pt-4 mt-5 mb-4">
                    <h1>Connect with us!</h1>
                    <img src="/home/images/icons/facebook.png" alt="facebook">
                    <img src="/home/images/icons/twitter.png" alt="twitter">
                    <img src="/home/images/icons/instagram.png" alt="instagram">
                    <img src="/home/images/icons/youtube.png" alt="youtube">
                </div>
            </div>
        </div>

        <div class="container-fluid credit text-center p-1">
            <h1>©2022 PUPSJ HOTEL</h1>
        </div>
    </footer>

@include('home.navbar-script')
<script>
/*--------------------------BOOK NOW--------------------------*/
const bookBtn = document.getElementById('book-btn')
bookExit = document.getElementById('book-exit')
bookTop = document.getElementById('book-top')
bookForm = document.getElementById('book-form')
bookBox = document.getElementById('book-box')
navHeight = document.querySelector('nav').offsetHeight;;

var rect = bookBtn.getBoundingClientRect();
  window.addEventListener("scroll", function() {
      if(this.pageYOffset > rect.top-navHeight){
          bookTop.classList.add('btn-top');
          bookTop.style.top = navHeight + 'px';
      }
      else {
          bookTop.classList.remove('btn-top');
      }  
  });

  bookBtn.addEventListener('click', function(){
      let scWidth = screen.width;
      if(scWidth < 768){
          bookForm.classList.add('book-show');
          document.body.style.overflowY = 'hidden';
          bookBtn.style.opacity = '0';
      }
  });

  bookExit.addEventListener('click', function(){
      bookForm.classList.remove('book-show');
      document.body.style.overflowY = 'scroll';
      bookBtn.style.opacity = '1';
  });

  document.addEventListener('mouseup', function(e) {
    if (!bookBox.contains(e.target)) {   
      bookForm.classList.remove('book-show');
      bookBtn.style.opacity = '1';
    }
  });
/*------------------------------------------------------------*/
/*---------------------------AMENITIES--------------------------*/
const amenities = document.getElementById("amenities-btn");

      amenities.addEventListener('click', () => {
          amenities.classList.toggle('btn-rotate');
      })

const btnHotel = document.getElementById("hotel").querySelectorAll("button");

      btnHotel[4].classList.toggle('btn-rotate');
      for(let i=0; i < btnHotel.length; i++){
          btnHotel[i].addEventListener('click', () => {
              btnHotel[i].classList.toggle('btn-rotate');
          })
      }
/*--------------------------------------------------------------*/
</script>
@include('home.calendar-script')
@include('home.quantity-script')
</body>
</html>