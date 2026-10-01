<script src="https://kit.fontawesome.com/8e1461a564.js" crossorigin="anonymous"></script>
<script src="home/dist/js/bootstrap.bundle.min.js"></script>
<script>
/*--------------------------NAVIGATION BAR--------------------------*/
const profileCta = document.getElementById('profile-cta')
profileBox =  document.getElementById('profile-box')
profile =  document.getElementById('profile')
angle = document.getElementById('angle');

angle.setAttribute("class", "bi bi-caret-up");
setInterval(function() {
  if(profileBox.classList.contains('show'))
    angle.setAttribute("class", "bi bi-caret-up");
  else
    angle.setAttribute("class", "bi bi-caret-down");
}, 1);

profileCta.addEventListener('click', function(){
profileBox.classList.toggle("show");
profileCta.classList.toggle("profile-btn-active");
angle.setAttribute("class", "bi bi-caret-up");
});

document.addEventListener('mouseup', function(e) {
if (!profile.contains(e.target)) {
  profileBox.classList.remove("show");
  profileCta.classList.remove("profile-btn-active");
  angle.setAttribute("class", "bi bi-caret-down");
}
});

const navItem = document.getElementById("nav-menu").querySelectorAll("li");
let current = null;

  for(let i=0; i<navItem.length; i++){
    if(navItem[i].querySelector("a").pathname == window.location.pathname){
        navItem[i].classList.add('nav-item-active');
        navItem[i].querySelector("a").style.color = 'lightgray';
        current=i;
    }
    else if(window.location.pathname == '/room_details'){
        navItem[1].classList.add('nav-item-active');
        navItem[1].querySelector("a").style.color = 'lightgray';
        current=1;
    }
  }

  if(current != null){
    const isHover = e => e.parentElement.querySelector(':hover') === e;    
    document.addEventListener('mousemove', function checkHover() {
      let hovered = [];
      for(let i=0; i<navItem.length; i++){
          if(navItem[i].querySelector("a").pathname != window.location.pathname)
              hovered[i] = isHover(navItem[i]);
      }

      hovered = hovered.filter(function( element ) {
          return element !== undefined;
      });

      if(hovered[0] !== checkHover.hovered || hovered[1] !== checkHover.hovered || hovered[2] !== checkHover.hovered) {
            if(hovered[0] || hovered[1] || hovered[2])
                navItem[current].classList.remove('nav-item-active');
            else
                navItem[current].classList.add('nav-item-active');
      }
    });
  }
/*------------------------------------------------------------*/

/*--------------------------MOBILE NAVIGATION--------------------------*/
const menu = document.getElementById('mobile-cta-menu')
      exit = document.getElementById('mobile-cta-exit')
      cover = document.getElementById('cover')
      nav = document.querySelector('.mobile-nav');
      let scWidth = screen.width;

      menu.addEventListener("click", function() {
        nav.classList.add('show-nav');
        cover.style.display = "grid";
      });
      
      exit.addEventListener("click", function() {
        nav.classList.remove('show-nav');
        cover.style.display = "none";
      });

      cover.addEventListener('click', function (event) {
        nav.classList.remove('show-nav');
        cover.style.display = "none";
      });
/*--------------------------------------------------------------*/

const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
</script>