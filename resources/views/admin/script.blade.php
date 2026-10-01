<script src="admin/assets/js/jquery-3.5.1.min.js"></script>
<script src="admin/assets/js/moment.min.js"></script>
<script src="admin/assets/js/popper.min.js"></script>
<script src="admin/assets/js/bootstrap.min.js"></script>
<script src="admin/assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="admin/assets/plugins/datatables/datatables.min.js"></script>
<script src="admin/assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="admin/assets/plugins/raphael/raphael.min.js"></script>
<script src="admin/assets/js/script.js"></script>
<script src="//unpkg.com/alpinejs" defer></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script src="https://kit.fontawesome.com/8e1461a564.js" crossorigin="anonymous"></script>
<script>
    function ucwords (str) {
        return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
            return $1.toUpperCase();
        });
    }
    function pad(pad, str, padLeft) {
        if (typeof str === 'undefined') 
            return pad;
        if (padLeft) {
            return (pad + str).slice(-pad.length);
        } else {
            return (str + pad).substring(0, pad.length);
        }
    }
    function validateForm(searchInput){
        if(searchInput.value && !(!searchInput.value.trim().length)){
            getFilters('','search',searchInput);
        }
        else {
            getFilters('SPAN','search',searchInput);
        }
    }

    function getFilters(e,filter,element) {
        let params = new URLSearchParams(new URL(window.location.href).search);
        params.delete(filter);
        if (e == 'SPAN') {
            $('.dropdown-menu').hide();
            window.location.href = window.location.origin + window.location.pathname+"?"+params.toString();
        }
        else {
            let filters = '';
            if(filter == 'date'){
                filters = (!element.includes("-"))?element:moment(element).format('DD-MM-YYYY');
            }
            else if(filter == 'search'){
                filters = element.value;
            }
            else{
                element.querySelectorAll('[type="checkbox"]').forEach(checkbox => {
                    if(checkbox.checked)
                        filters += checkbox.value.toLowerCase() +', ';
                });
                filters = filters.substring(0, filters.length - 2);
            }
            if(filters)
                window.location.href = window.location.origin + window.location.pathname+"?"+params.toString()+"&"+filter+"="+filters;
            else
                window.location.href = window.location.origin + window.location.pathname+"?"+params.toString();
        }
    }

    function fillFilterButton(filterElements, getFilter, button, filterName) {
        filterElements.forEach(element => {
            if(filterName == 'date'){
                if(Array.from(element.classList).some(filter=>getFilter.includes(filter) && !moment(getFilter, "DD-MM-YYYY", true).isValid())){
                    styleSelectedFilter(element);
                }
                if(moment(getFilter, "DD-MM-YYYY", true).isValid()){
                    styleSelectedFilter(Array.from(filterElements).find(filter=>filter.classList.contains('custom')));
                }
                function styleSelectedFilter(selectedFilter){
                    selectedFilter.classList.add('bg-secondary');
                    if(selectedFilter.classList.contains('custom'))
                        selectedFilter.querySelector('div').classList.add('text-light');
                    else 
                        selectedFilter.classList.add('text-light');
                }
            }
            else {
                element.checked = getFilter.split(", ").some(filter=>filter.toLowerCase() == element.value.toLowerCase());
            }
        });
        button.innerHTML = "<span onclick=\"getFilters(event.target.tagName,'"+filterName+"')\">⨉</span> "+ucwords(getFilter)+" <i class='fa-solid fa-angle-down'></i>";
        button.classList.add('bg-secondary');
        button.classList.add('text-light');
    }
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
    let today = currentYear + '-' + currentMonth + '-' + currentDay;
    function checkOutMin(checkInInput, checkOutInput){
        let checkinValue = new Date (checkInInput.value);
        if(!checkInInput.value)
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
        checkOutInput.setAttribute('min', checkinDate);

        if(checkInInput.value >= checkOutInput.value || !checkOutInput.value)
            checkOutInput.value = checkinDate;
    }
</script>
<style>
    .center-icon {
        display: table-cell;
        font-size: 25px;
        vertical-align: middle;
        text-align: center;
        height: 40px;
        width: 40px;
    }
</style>


