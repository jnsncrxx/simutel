<script>
/*--------------------------CALENDAR--------------------------*/
const checkIn = document.getElementById('check-in')
checkOut = document.getElementById('check-out');

const d = new Date();
let currentYear = d.getFullYear()
currentMonth = d.getMonth()+1
currentDay = d.getDate();

if(currentDay<10){
  currentDay = '0' + currentDay;
} 
if(currentMonth<10){
  currentMonth = '0' + currentMonth;
} 
today = currentYear + '-' + currentMonth + '-' + currentDay;

  var url_string = window.location.href; 
  var url = new URL(url_string);
  var getCheckIn = url.searchParams.get("check_in");

  if(!getCheckIn)
    checkIn.value = today;

checkIn.setAttribute('min', today);

checkOutMin();
function checkOutMin(){
  let checkinValue = new Date (checkIn.value);
  if(!checkIn.value)
    checkinValue = new Date (today);

  let checkinDay = new Date (checkinValue);
  checkinDay.setDate(checkinDay.getDate() + 1);
  plusDay = checkinDay.getDate();

  let checkinMonth = checkinValue.getMonth()+1;
  if(plusDay === 1 && currentDay !== 1){
    checkinMonth = checkinValue.getMonth();
    checkinMonth += 2;

    if(checkinMonth === 13)
      checkinMonth = 1;
  }

  let checkinYear = checkinValue.getFullYear();
  if(plusDay === 1 && checkinMonth === 1 && currentMonth !== 1){
    checkinYear = new Date (checkinValue);
    checkinYear.setYear(checkinYear.getFullYear() + 1);
    checkinYear = checkinYear.getFullYear();
  }

  if(plusDay<10){
    plusDay = '0' + plusDay;
  } 
  if(checkinMonth<10){
    checkinMonth = '0' + checkinMonth;
  } 

  checkinDate = checkinYear + '-' + checkinMonth + '-' + plusDay;
  checkOut.setAttribute('min', checkinDate);

  if(checkIn.value >= checkOut.value || !checkOut.value)
    checkOut.value = checkinDate;
}

checkOut.addEventListener("change", () => {
if(!checkIn.value)
checkIn.value = today;

if(checkIn.value > checkOut.value || !checkOut.value)
checkOutMin();
})

checkIn.addEventListener("change", () => {
if(!checkIn.value)
checkIn.value = today;

checkOutMin();
})
/*--------------------------------------------------------------*/
</script>