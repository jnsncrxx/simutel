<script>
  const roomsBtn = document.getElementById('rooms-cta')
        suitesBtn = document.getElementById('suites-cta')
        rooms = document.getElementById('rooms')
        suites = document.getElementById('suites');

        roomsBtn.style.cursor = 'default';
        suitesBtn.style.cursor = 'pointer';
        suites.style.display = 'none';
        roomsBtn.style.borderBottom = '3px solid var(--gray)';

        roomsBtn.addEventListener('click', function(){
            rooms.style.display = 'grid';
            suites.style.display = 'none';
            roomsBtn.style.borderBottom = '3px solid var(--gray)';
            suitesBtn.style.borderBottom = 'none';
            roomsBtn.style.cursor = 'default';
            suitesBtn.style.cursor = 'pointer';
        });

        suitesBtn.addEventListener('click', function(){
            rooms.style.display = 'none';
            suites.style.display = 'grid';
            roomsBtn.style.borderBottom = 'none';
            suitesBtn.style.borderBottom = '3px solid var(--gray)';
            roomsBtn.style.cursor = 'pointer';
            suitesBtn.style.cursor = 'default';
        });


const amenitiesBtn =  document.getElementsByClassName("amenities")
      amenitiesAngle = document.getElementsByClassName("amenities-angle");

  for (let i = 0; i < amenitiesBtn.length; i++) {
    amenitiesBtn[i].addEventListener('click', function() {
        if(amenitiesBtn[i].getAttribute('aria-expanded') == 'true')
          amenitiesAngle[i].setAttribute("class", "amenities-angle bi bi-caret-up");
        else
          amenitiesAngle[i].setAttribute("class", "amenities-angle bi bi-caret-down");
    });
  }
</script>