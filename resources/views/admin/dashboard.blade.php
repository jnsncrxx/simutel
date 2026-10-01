@include('admin.css')
<style>
/*----------Scrollbar styles----------*/
	.arrivals::-webkit-scrollbar, .departures::-webkit-scrollbar, .stays::-webkit-scrollbar, tbody::-webkit-scrollbar {
		width: 7px;
	}

	.arrivals::-webkit-scrollbar-track, .departures::-webkit-scrollbar-track, .stays::-webkit-scrollbar-track, tbody::-webkit-scrollbar-track {
		background: #f5f5f5;
		border-radius: 10px;
	}

	.arrivals::-webkit-scrollbar-thumb, .departures::-webkit-scrollbar-thumb, .stays::-webkit-scrollbar-thumb, tbody::-webkit-scrollbar-thumb {
		border-radius: 10px;
		background: #ccc;  
	}

	.arrivals::-webkit-scrollbar-thumb:hover, .departures::-webkit-scrollbar-thumb:hover, .stays::-webkit-scrollbar-thumb:hover, tbody::-webkit-scrollbar-thumb:hover {
		background: #999;  
	}
/*------------------------------------*/
	.arrivals, .departures, .stays  {
        max-height: 214px;
		overflow-y: auto;
    }
	.empty {
		min-height: 150px;
	}
	.table td:first-child {
    	text-align: left;
	}
	@media only screen and (min-width: 576px) {
		.empty {
			min-height: 214px;
		}
    }
	@media only screen and (min-width: 992px) {
        .arrivals, .departures, .stays {
            height: 214px;
        }
    }

</style>
<body>
	<div class="main-wrapper">
		@include('admin.header')
		@include('admin.sidebar')
		<div class="page-wrapper">
			<div class="content container-fluid">
				<div class="page-header">
					<div class="row">
						<div class="col-sm-12 mt-5">
							<ul class="breadcrumb">
								<li class="breadcrumb-item active">Dashboard</li>
							</ul>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xl-3 col-6">
						<div class="card board1 fill">
							<div class="card-body clickable" onclick="window.location.href='/all_reservations?date=today'">
								<div class="dash-widget-header">
									<h3 class="card_widget_header total-booking"></h3>
									<div class="ml-auto"><h1 class="m-0"><i class="fa-regular fa-file-lines"></i></h1></div>
								</div>
								<h6 class="text-muted">Total Reservations</h6> 
							</div>
						</div>
					</div>
					<div class="col-xl-3 col-6">
						<div class="card board1 fill">
							<div class="card-body clickable" onclick="window.location.href='housekeeping?reservation=vacant&housekeeping=clean,%20cleaning,%20dirty'">
								<div class="dash-widget-header">
									<h3 class="card_widget_header total-available-rooms"></h3>
									<div class="ml-auto"><h1 class="m-0"><i class="fas fa-key"></i></h1></div>
								</div>
								<h6 class="text-muted">Available Rooms</h6> 
							</div>
						</div>
					</div>
					<div class="col-xl-3 col-6">
						<div class="card board1 fill">
							<div class="card-body clickable" onclick="window.location.href='housekeeping?reservation=occupied'">
								<div class="dash-widget-header">
									<h3 class="card_widget_header total-occupied-rooms"></h3>
									<div class="ml-auto"><h1 class="m-0"><i class="fa fa-bed"></i></h1></div>
								</div>
								<h6 class="text-muted">Occupied Rooms</h6> 
							</div>
						</div>
					</div>
					<div class="col-xl-3 col-6">
						<div class="card board1 fill">
							<div class="card-body clickable" onclick="window.location.href='guests'">
								<div class="dash-widget-header">
									<h3 class="card_widget_header total-in-house-guests"></h3> 
									<div class="ml-auto"><h1 class="m-0"><i class="fa fa-user"></i></h1></div>
								</div>
								<h6 class="text-muted">Guests</h6>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 col-lg-6">
						<div class="card card-chart">
							<div class="card-header p-2 d-flex justify-content-between">
								<h4 class="card-title my-auto">RESERVATIONS</h4>
								<h1 class="border rounded text-secondary my-1 p-1 clickable" onclick="sessionStorage.setItem('add_reservation', true);window.location.href='all_reservations'" style="line-height:22px;">+</h1>
							</div>
							<div class="card-body row mx-0">
								<div class="col-md-6 pt-2 d-flex justify-content-center">
									<canvas id="reservationsChart" width="100%" height="100%" style="max-height: 210px; max-width: 210px;"></canvas>
								</div>
								<div class="reservations-data col-md-6">
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-12 col-lg-6">
						<div class="card card-chart guest-management">
							<div class="card-header p-2 mx-0 row justify-content-between">
								<h4 class="card-title my-auto pl-0 w-100 col-md-6">GUEST MANAGEMENT</h4>
								<input type="text" class="form-control search col-md-6 mt-1 mt-md-0" placeholder="&#xf002 Search by id, guest, room" style=" font-family: 'Helvetica', FontAwesome, sans-serif;">
							</div>
							<div class="card-body row mx-0">
								<div class="col-4 text-center px-1">
									<p class="mb-2"><b>Arrivals</b></p>
									<div class="arrivals pr-1">
										<p class="border rounded text-secondary h-100 m-0 d-flex align-items-center justify-content-center empty">No arrivals yet</p>
									</div>
								</div>
								<div class="col-4 text-center px-1">
									<p class="mb-2"><b>Departures</b></p>
									<div class="departures pr-1">
										<p class="border rounded text-secondary h-100 m-0 d-flex align-items-center justify-content-center empty">No departures yet</p>
									</div>
								</div>
								<div class="col-4 text-center px-1">
									<p class="mb-2"><b>Stays</b></p>
									<div class="stays pr-1">
										<p class="border rounded text-secondary h-100 m-0 d-flex align-items-center justify-content-center empty">No guest in house yet</p>
									</div>
								</div>
								<div class="copy d-none">
									<div class="border rounded d-flex align-items-center p-1 text-left mb-1 clickable">
										<div class="pr-1">
											<i class="fa-solid fa-right-to-bracket border rounded p-1"></i>
										</div>
										<div style="font-size: 13px;line-height: 16px; overflow: hidden;">
											<p class="m-0 text-info name" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"></p>
											<p class="m-0 room" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"></p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-12 col-lg-4">
						<div class="card card-chart sources">
							<div class="card-header p-2 d-flex justify-content-between">
								<h4 class="card-title my-auto w-100">SOURCES</h4>
								<select class="form-control range" style="width: fit-content">
									<option value="0">Today</option>
                                    <option value="7">Last 7 Days</option>
                                    <option value="15">Last 15 Days</option>
                                    <option value="30">Last 30 Days</option>
                                </select>
							</div>
							<div class="card-body pb-0 pr-3">
								<div class="d-flex justify-content-center align-items-center mb-2">
									<h3 class="mb-0 mr-1 total-source"></h3>
									<h5 class="m-0 text-secondary">Total</h5>
								</div>
								<canvas id="sourceChart" width="100%" height="100%" style="max-height: 200px"></canvas>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-lg-4">
						<div class="card card-chart">
							<div class="card-header px-2">
								<h4 class="card-title">ROOMS OCCUPIED / AVAILABLE</h4> 
							</div>
							<div class="card-body">
								<div class="px-3 mx-5 py-3 d-flex justify-content-center">
									<canvas id="roomsChart" width="100%" height="100%" style="max-height: 210px; max-width: 210px;"></canvas>
								</div>
								<div class="rooms-data px-2">
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-lg-4">
						<div class="card card-chart">
							<div class="card-header px-2 d-flex justify-content-between">
								<h4 class="card-title mt-auto">HOUSEKEEPING</h4> 
								<a href="housekeeping">View all</a>
							</div>
							<div class="card-body">
								<div class="px-4 mx-5 py-3 d-flex justify-content-center">
									<canvas id="housekeepingChart" width="100%" height="100%" style="max-height: 210px; max-width: 210px;"></canvas>
								</div>
								<div class="housekeeping-data px-2">
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-12 col-lg-7">
					</div>
					<div class="col-md-12 col-lg-7">
						<div class="card card-chart">
							<div class="card-header px-2">
								<h4 class="card-title">ROOMS</h4> 
							</div>
							<div class="table-responsive">
								<table class="table text-center table-striped">
								  <thead>
									<tr>
										<th class="text-left" style="white-space: nowrap">Room Type</th>
										<th>Available</th>
										<th>Booked</th>
										<th style="white-space: nowrap">Out Of Order</th>
										<th>Total</th>
									</tr>
								  </thead>
								  <tbody id="rooms">
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="col-md-12 col-lg-5">
						<div class="card card-chart top-room-types">
							<div class="card-header p-2 d-flex justify-content-between">
								<h4 class="card-title my-auto w-100">TOP BOOKED ROOM TYPES</h4>
								<select class="form-control range" style="width: fit-content">
                                    <option value="0">Today</option>
                                    <option value="7">Last 7 Days</option>
                                    <option value="15">Last 15 Days</option>
                                    <option value="30">Last 30 Days</option>
                                </select>
							</div>
							<div class="card-body pt-3 pb-0">
								<div class="d-flex justify-content-center align-items-center mb-2">
									<h3 class="mb-0 mr-1 total-bookings"></h3>
									<h5 class="m-0 text-secondary">Total</h5>
								</div>
								<canvas id="topRoomTypeChart" width="100%" height="100%" onclick="labelCLick(this,event)" onmousemove="checkLabelClickable(this,event)"></canvas>
							</div>
						</div>
					</div>
					<div class="col-md-12 col-lg-6">
						<div class="card card-chart bookings">
							<div class="card-header p-2 d-flex justify-content-between">
								<h4 class="card-title my-auto w-100">RESERVATIONS</h4>
								<select class="form-control range" style="width: fit-content">
                                    <option value="7">Last 7 Days</option>
                                    <option value="15">Last 15 Days</option>
                                    <option value="30">Last 30 Days</option>
                                </select>
							</div>
							<div class="card-body">
								<canvas id="bookingsLineChart"></canvas>
							</div>
						</div>
					</div>
					<div class="col-md-12 col-lg-6">
						<div class="card card-chart guests">
							<div class="card-header p-2 d-flex justify-content-between">
								<h4 class="card-title my-auto w-100">GUESTS</h4>
								<select class="form-control range" style="width: fit-content">
                                    <option value="7">Last 7 Days</option>
                                    <option value="15">Last 15 Days</option>
                                    <option value="30">Last 30 Days</option>
                                </select>
							</div>
							<div class="card-body">
								<canvas id="guestsLineChart"></canvas>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@include('admin.script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.1.0/chartjs-plugin-datalabels.min.js" integrity="sha512-Tfw6etYMUhL4RTki37niav99C6OHwMDB2iBT5S5piyHO+ltK2YX8Hjy9TXxhE1Gm/TmAV0uaykSpnHKFIAif/A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
document.getElementById('side-dashboard').classList.add('active');
	let reservations;
	$.ajax({
        async: false,
        type: "GET",
        url: "get_reservations",
        success: function (response) {
            reservations = response;
        },
        error: function(xhr, status, error) {
            console.log(xhr.responseText);
        }
    });
	let room_types;
	$.ajax({
        async: false,
        type: "GET",
        url: "get_all_rooms",
        success: function (response) {
            room_types = response;
        },
        error: function(xhr, status, error) {
            console.log(xhr.responseText);
        }
    });
	function generateChartLabels(element,labels,colors,data){
		for (let i = 0; i < labels.length; i++) {
			if(element.classList.contains('rooms-data')){
				let filter = (labels[i] == 'Available')?'vacant':labels[i].toLowerCase();
				element.innerHTML += "<a href='housekeeping?reservation="+filter+"' class='border-bottom d-flex mb-0 py-2'><span style='color:"+colors[i]+"'>■</span>&nbsp;"+ labels[i] +"<span class='ml-auto'>"+ data[i] +"</span></a>";
			}
			else if(element.classList.contains('reservations-data'))
				element.innerHTML += "<a href='all_reservations?date=today&status="+labels[i].toLowerCase()+"' class='border-bottom d-flex mb-0 py-2'><span style='color:"+colors[i]+"'>■</span>&nbsp;"+ labels[i] +"<span class='ml-auto'>"+ data[i] +"</span></a>";
			else if(element.classList.contains('housekeeping-data'))
				element.innerHTML += "<a href='housekeeping?housekeeping="+labels[i].toLowerCase()+"' class='border-bottom d-flex mb-0 py-2'><span style='color:"+colors[i]+"'>■</span>&nbsp;"+ labels[i] +"<span class='ml-auto'>"+ data[i] +"</span></a>";
		}
	}
	function reservationsRanged(range) {
		return reservations.filter(reservation => {
			for(let i=0; i<=range; i++){
				if(moment(new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate() - i)).format('MM-DD-YYYY')>=moment(reservation.check_in).format('MM-DD-YYYY') && moment(new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate() - i)).format('MM-DD-YYYY')<=moment(reservation.check_out).format('MM-DD-YYYY')){
					return true;
				}
			}
		});
	}
	let roomTypesLabelsRectPosition = [];
	function checkLabelClickable(element,e) {
		var p = getMousePos(element,e);
		let overLabel = false;
		roomTypesLabelsRectPosition.forEach(labelRectPosition => {
			if (p.x >= labelRectPosition.rect.x && p.x <= labelRectPosition.rect.x + labelRectPosition.rect.w &&
			p.y >= labelRectPosition.rect.y && p.y <= labelRectPosition.rect.y + labelRectPosition.rect.h)
				overLabel = true;	
		});
		if(overLabel)
			element.style.cursor = 'pointer';
		else
			element.style.cursor = 'auto';
	}
	function labelCLick(element,e) {
		var p = getMousePos(element,e);
		roomTypesLabelsRectPosition.forEach(labelRectPosition => {
			if (p.x >= labelRectPosition.rect.x && p.x <= labelRectPosition.rect.x + labelRectPosition.rect.w &&
			p.y >= labelRectPosition.rect.y && p.y <= labelRectPosition.rect.y + labelRectPosition.rect.h) {
				window.location.href = "all_reservations?date="+document.querySelector('.top-room-types').querySelector('.range').options[document.querySelector('.top-room-types').querySelector('.range').selectedIndex].text.toLowerCase()+"&room_types="+labelRectPosition.label.toLowerCase();
			}
		});
	}
	function getMousePos(element, e) {
    	var r = element.getBoundingClientRect();
		return {
			x: e.clientX - r.left,
			y: e.clientY - r.top
		};
	}
/*-------------------------RESERVATIONS--------------------------*/
	const donutCenterText = {
		beforeDraw: function(chart) {
			if (chart.config.options.elements.center) {
			// Get ctx from string
			var ctx = chart.ctx;

			// Get options from the center object in options
			var centerConfig = chart.config.options.elements.center;
			var fontStyle = centerConfig.fontStyle || 'Arial';
			var txt = centerConfig.text;
			var color = centerConfig.color || '#000';
			var maxFontSize = centerConfig.maxFontSize || 75;
			var sidePadding = centerConfig.sidePadding || 20;
			var sidePaddingCalculated = (sidePadding / 100) * (chart.innerRadius * 2)
			// Start with a base font of 30px
			ctx.font = "30px " + fontStyle;

			// Get the width of the string and also the width of the element minus 10 to give it 5px side padding
			var stringWidth = ctx.measureText(txt).width;
			var elementWidth = (chart.innerRadius * 2) - sidePaddingCalculated;

			// Find out how much the font can grow in width.
			var widthRatio = elementWidth / stringWidth;
			var newFontSize = Math.floor(30 * widthRatio);
			var elementHeight = (chart.innerRadius * 2);

			// Pick a new font size so it will not be larger than the height of label.
			var fontSizeToUse = Math.min(newFontSize, elementHeight, maxFontSize);
			var minFontSize = centerConfig.minFontSize;
			var lineHeight = centerConfig.lineHeight || 25;
			var wrapText = false;

			if (minFontSize === undefined) {
				minFontSize = 20;
			}

			if (minFontSize && fontSizeToUse < minFontSize) {
				fontSizeToUse = minFontSize;
				wrapText = true;
			}

			// Set font settings to draw it correctly.
			ctx.textAlign = 'center';
			ctx.textBaseline = 'middle';
			var centerX = ((chart.chartArea.left + chart.chartArea.right) / 2);
			var centerY = ((chart.chartArea.top + chart.chartArea.bottom) / 2);
			ctx.font = fontSizeToUse + "px " + fontStyle;
			ctx.fillStyle = color;

			if (!wrapText) {
				ctx.fillText(txt, centerX, centerY);
				return;
			}

			var words = txt.split(' ');
			var line = '';
			var lines = [];

			// Break words up into multiple lines if necessary
			for (var n = 0; n < words.length; n++) {
				var testLine = line + words[n] + ' ';
				var metrics = ctx.measureText(testLine);
				var testWidth = metrics.width;
				if (testWidth > elementWidth && n > 0) {
				lines.push(line);
				line = words[n] + ' ';
				} else {
				line = testLine;
				}
			}

			// Move the center up depending on line height and number of lines
			centerY -= (lines.length / 2) * lineHeight;

			for (var n = 0; n < lines.length; n++) {
				ctx.fillText(lines[n], centerX, centerY);
				centerY += lineHeight;
			}
			//Draw text in center
			ctx.fillText(line, centerX, centerY);
			}
		}
	};
	const reservationsLabels = [ "Due In", "Due Out", "Checked-in", "Checked-out", "Cancelled", "No Show"];
	const reservationsData = [0, 0, 0, 0, 0, 0];
	let totalBookings = 0;
	reservationsLabels.forEach((reservationsLabel,index) => {
			reservations.forEach(reservation => {
				if(moment(new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate())).format('MM-DD-YYYY')>=moment(reservation.check_in).format('MM-DD-YYYY') && moment(new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate())).format('MM-DD-YYYY')<=moment(reservation.check_out).format('MM-DD-YYYY')){
					if(reservationsLabel == reservation.status){
						reservationsData[index]++;
						totalBookings++;
					}
				}
			});
	});
	const reservationsColors = ["#ffae77","#85eeff","#ffdf80","#82beff","#6c757d","#ff6170"];
	new Chart("reservationsChart", {
		  type: "doughnut",
		  data: {
			labels: reservationsLabels,
			datasets: [{
			  backgroundColor: reservationsColors,
			  data: reservationsData,
			  hoverOffset: 4
			}]
		  },
		  options: {
			cutout: 60,
			plugins: {
				legend: {
					display: false
				}
			},
			elements: {
				center: {
					text: totalBookings,
					maxFontSize: 35,
				}
			}
		  },
		  plugins: [donutCenterText]
	});
	generateChartLabels(document.querySelector('.reservations-data'),reservationsLabels,reservationsColors,reservationsData);
	document.querySelector('.total-booking').innerHTML = totalBookings;
/*---------------------------------------------------------------*/

/*-------------------------GUEST MANAGEMENT----------------------*/
	const searchInput = $('.guest-management')[0].querySelector(".search");
	let guestReservations = reservationsRanged(0);
	searchInput.addEventListener('input',()=>{
		let searchGuestReservations = guestReservations;
		if(!(!searchInput.value.trim().length)){
			searchGuestReservations = guestReservations.filter(reservation=>
				reservation.reserved_by.first_name.toLowerCase().indexOf(searchInput.value.toLowerCase()) > -1 || 
				reservation.reserved_by.last_name.toLowerCase().indexOf(searchInput.value.toLowerCase()) > -1 || 
				reservation.id == searchInput.value || 
				reservation.room_type.toLowerCase().indexOf(searchInput.value.toLowerCase()) > -1);
			$('.guest-management')[0].querySelectorAll(".empty").forEach(element => {
				element.classList.remove('d-flex');
				element.classList.add('d-none');
			});
		}
		else {
			$('.guest-management')[0].querySelectorAll(".empty").forEach(element => {
				element.classList.remove('d-none');
				element.classList.add('d-flex');
			});
		}
		showRows(searchGuestReservations);
	});
	const node = $('.guest-management')[0].querySelector(".copy").firstElementChild;

	if(guestReservations.filter(reservation=>reservation.status == 'Due In').length)
		$('.arrivals')[0].querySelector('.empty').className = 'd-none';
	if(guestReservations.filter(reservation=>reservation.status == 'Due Out').length)
		$('.departures')[0].querySelector('.empty').className = 'd-none';
	if(guestReservations.filter(reservation=>reservation.status == 'Checked-in').length)
		$('.stays')[0].querySelector('.empty').className = 'd-none';

	showRows(guestReservations);
	function showRows(reservations) {
		$('.arrivals')[0].querySelectorAll('div').forEach(rows => {
			rows.remove();
		});
		$('.departures')[0].querySelectorAll('div').forEach(rows => {
			rows.remove();
		});
		$('.stays')[0].querySelectorAll('div').forEach(rows => {
			rows.remove();
		});
		reservations.forEach(reservation => {
			function generateRows(column,color) {
				const row = $(column)[0].appendChild(node.cloneNode(true));
				row.addEventListener('click', ()=>{
					sessionStorage.setItem('booking_id', reservation.id);
					window.location.href='all_reservations?date=today';
				});
				row.querySelector('.fa-solid').classList.add(color);
				row.querySelector('.name').innerHTML = ucwords(reservation.reserved_by.first_name) +' '+ ucwords(reservation.reserved_by.last_name);
				row.querySelector('.room').innerHTML = '#'+reservation.id+' - '+ucwords(reservation.room_type);
			}
			if(reservation.status == 'Due In')
				generateRows('.arrivals','bg-primary-light');
			if(reservation.status == 'Due Out')
				generateRows('.departures','bg-danger-light');
			if(reservation.status == 'Checked-in')
				generateRows('.stays','bg-info-light');
		});
	}
/*---------------------------------------------------------------*/

/*-------------------------SOURCE--------------------------------*/
	let sourceChart, sourceLabels = ["Website","Reception","Email","Call"];

	document.querySelector('.sources').querySelector('.range').addEventListener('change', ()=>{
		sourceChart.destroy();
		generateSourceChart(reservationsRanged(document.querySelector('.sources').querySelector('.range').value));
	});
	generateSourceChart(reservationsRanged(document.querySelector('.sources').querySelector('.range').value));
	function generateSourceChart(reservations) {
		let sources = [], totalSource = 0;
		sourceLabels.forEach((sourceLabel) => {
			let sourceData = 0;
			reservations.forEach(reservation => {
				if(sourceLabel == reservation.source)
					sourceData++;
			});
			sources.push({label:sourceLabel,data:sourceData});
			totalSource += sourceData;
		});
		document.querySelector('.total-source').innerHTML = totalSource;
		sources.sort((a, b) => b.data - a.data);
		sourceChart = new Chart("sourceChart", {
			type: "bar",
			data: {
				labels: sources.map(a => a.label),
				datasets: [{
					label: 'Bookings',
					data: sources.map(a => a.data),
					borderColor: [
						'rgba(235, 237, 239, 1)',
						'rgba(235, 237, 239, 1)',
						'rgba(235, 237, 239, 1)',
						'rgba(235, 237, 239, 1)'
					],
					backgroundColor: [
						'rgba(54, 162, 235, 1)',
						'rgba(54, 162, 235, 1)',
						'rgba(54, 162, 235, 1)',
						'rgba(54, 162, 235, 1)'
					],
					borderWidth: 0,
					borderSkipped: false,
					borderRadius: 5,
					barPercentage: 0.2,
					categoryPercentage: 0.5
				}]
			},
			options: {
				indexAxis: 'y',
				plugins: {
					legend: {
						display: false
					}
				},
				scales: {
					x: {
						grid: {
							display: false,
							drawBorder : false
						},
						ticks: {
							display: false
						},
						suggestedMax: reservations.length
					},
					y: {
						beginAtZero: true,
						grid: {
							display: false,
							drawBorder : false
						},
						ticks: {
							display: false
						}
					},
				}
			},
			plugins: [{beforeDatasetsDraw: function(chart) {
				const {ctx, data, chartArea: {top, bottom, left, right, width, height},scales: {x, y}} = chart;
				ctx.save();
				const barHeight = height / y.ticks.length * data.datasets[0].barPercentage * data.datasets[0].categoryPercentage;
				data.datasets[0].data.forEach((datapoint,index)=>{
					//label text
					const fontSizeLabel = 16;
					ctx.font = fontSizeLabel+'px sans-serif';
					ctx.fillStyle = 'rgba(102, 102, 102, 1)';
					ctx.textAlign = 'left';
					ctx.textBaseline = 'middle';
					ctx.fillText(data.labels[index], left, y.getPixelForValue(index)-fontSizeLabel);

					//value text
					const fontSizeDatapoint = 16;
					ctx.font = fontSizeDatapoint+'px sans-serif';
					ctx.fillStyle = 'rgba(102, 102, 102, 1)';
					ctx.textAlign = 'right';
					ctx.textBaseline = 'middle';
					ctx.fillText(datapoint, right, y.getPixelForValue(index)-fontSizeDatapoint);

					//bg color progress bar
					ctx.beginPath();
					ctx.fillStyle = data.datasets[0].borderColor[index];
					ctx.fillRect(left, y.getPixelForValue(index) - (barHeight / 2), width, barHeight);
				});
			}}]
		});	
	}
/*---------------------------------------------------------------*/

/*-------------------------ROOMS---------------------------------*/
	let totalAvailableRooms = 0, totalOccupiedRooms = 0;
	room_types.forEach(room_type => {
		const insertRow = document.getElementById('rooms').insertRow();
        insertRow.insertCell(0).innerHTML = room_type.room_name;
		let available = 0, booked = 0, outOfService = 0, total = 0;
		room_type.rooms.forEach(room => {
			if(room.housekeeping.reservation_status == 'vacant' && room.housekeeping.status != 'out of service')
				available++;
			if(room.housekeeping.reservation_status == 'pending')
				booked++;
			if(room.housekeeping.reservation_status == 'occupied'){
				booked++; totalOccupiedRooms++;
			}
			if(room.housekeeping.status == 'out of service')
				outOfService++;
			total++;
		});
        insertRow.insertCell(1).innerHTML = available;
        insertRow.insertCell(2).innerHTML = booked;
        insertRow.insertCell(3).innerHTML = outOfService;
        insertRow.insertCell(4).innerHTML = total;
		totalAvailableRooms += available;
    });
	document.querySelector('.total-available-rooms').innerHTML = totalAvailableRooms;
	document.querySelector('.total-occupied-rooms').innerHTML = totalOccupiedRooms;

	const roomsLabels = ["Available", "Occupied"];
	const roomsData = [Math.round(totalAvailableRooms/(totalAvailableRooms+totalOccupiedRooms) * 100), Math.round(totalOccupiedRooms/(totalAvailableRooms+totalOccupiedRooms) * 100)];
	const roomsColors = ["#36a2eb","#7dcbff"];
	new Chart("roomsChart", {
		type: "pie",
		data: {
			labels: roomsLabels,
			datasets: [{
				data: roomsData,
				backgroundColor: roomsColors,
				hoverOffset: 4
			}]
		},
		options: {
			plugins: {
				legend: {
					display: false
				},
				datalabels: {
					formatter: function(value){
            			return value + '%';
        			},
        			color: '#ffffff'
     			},
				tooltip: {
					callbacks: {
						label: function(context) {
							return context.label + ': ' +context.parsed +'%';
						}
					}
				}
			}
		},
		plugins: [ChartDataLabels]
	});
	generateChartLabels(document.querySelector('.rooms-data'),roomsLabels,roomsColors,roomsData.map(i => i+'%'));
/*---------------------------------------------------------------*/

/*-------------------------HOUSEKEEPING--------------------------*/
	const housekeepingLabels = ["Clean", "Cleaning", "Dirty"];
	const housekeepingData = [0, 0, 0];
	let housekeepingTotal = 0;
	housekeepingLabels.forEach((housekeepingLabel,index) => {
		room_types.forEach(room_type => {
			room_type.rooms.forEach(room => {
				if(housekeepingLabel == ucwords(room.housekeeping.status)){
					housekeepingData[index]++;
					housekeepingTotal++;
				}
			});
		});
	});
	const housekeepingColors = ["#60ff84","#85eeff","#ff6170"];
	new Chart("housekeepingChart", {
		  type: "doughnut",
		  data: {
			labels: housekeepingLabels,
			datasets: [{
			  backgroundColor: housekeepingColors,
			  data: housekeepingData,
			  hoverOffset: 4
			}]
		  },
		  options: {
			cutout: 50,
			plugins: {
				legend: {
					display: false
				}
			},
			elements: {
				center: {
					text: housekeepingTotal,
					maxFontSize: 35,
				}
			}
		  },
		  plugins: [donutCenterText]
	});
	generateChartLabels(document.querySelector('.housekeeping-data'),housekeepingLabels,housekeepingColors,housekeepingData);
/*---------------------------------------------------------------*/

/*-------------------------TOP ROOM TYPES------------------------*/
	let topRoomTypeChart;
	document.querySelector('.top-room-types').querySelector('.range').addEventListener('change', ()=>{
		topRoomTypeChart.destroy();
		generateTopRoomTypeChart(reservationsRanged(document.querySelector('.top-room-types').querySelector('.range').value));
	});

	generateTopRoomTypeChart(reservationsRanged(document.querySelector('.top-room-types').querySelector('.range').value));
	function generateTopRoomTypeChart(reservations) {
		document.querySelector('.top-room-types').querySelector('.total-bookings').innerHTML = reservations.length;
		let roomTypes = [];
		room_types.forEach(room_type => {
			roomTypes.push({label:ucwords(room_type.room_name),data:reservations.filter(reservation => reservation.room_type == room_type.room_name).length});
		});
		roomTypes.sort((a, b) => b.data - a.data);
		const progressBar = {
			beforeDatasetsDraw: function(chart) {
				roomTypesLabelsRectPosition = [];
				const {ctx, data, chartArea: {top, bottom, left, right, width, height},scales: {x, y}} = chart;
				ctx.save();
				const barHeight = height / y.ticks.length * data.datasets[0].barPercentage * data.datasets[0].categoryPercentage;
				data.datasets[0].data.forEach((datapoint,index)=>{
					//label text
					const fontSizeLabel = 16;
					ctx.font = fontSizeLabel+'px sans-serif';
					ctx.fillStyle = 'rgba(54, 162, 235, 1)';
					ctx.textAlign = 'left';
					ctx.textBaseline = 'middle';
					if(!roomTypesLabelsRectPosition.map(a => a.label).some(label => label == data.labels[index])){
						roomTypesLabelsRectPosition.push({
							label: data.labels[index],
							rect:{
								x: left,
								y: y.getPixelForValue(index)-fontSizeLabel*2,
								w: ctx.measureText(data.labels[index]).width,
								h: 30
							}
						});
					}
					ctx.fillText(data.labels[index], left, y.getPixelForValue(index)-fontSizeLabel);

					//value text
					const fontSizeDatapoint = 16;
					ctx.font = fontSizeDatapoint+'px sans-serif';
					ctx.fillStyle = 'rgba(102, 102, 102, 1)';
					ctx.textAlign = 'right';
					ctx.textBaseline = 'middle';
					ctx.fillText((datapoint)?(datapoint/reservations.length * 100).toFixed(2)+'%':0+'%', right, y.getPixelForValue(index)-fontSizeDatapoint);

					//bg color progress bar
					ctx.beginPath();
					ctx.fillStyle = data.datasets[0].borderColor[index];
					ctx.fillRect(left, y.getPixelForValue(index) - (barHeight / 2), width, barHeight);
				});
			}
		}
		topRoomTypeChart = new Chart("topRoomTypeChart", {
			type: "bar",
			data: {
				labels: roomTypes.map(a => a.label),
				datasets: [{
					label: 'Bookings',
					data: roomTypes.map(a => a.data),
					borderColor: [
						'rgba(255, 26, 104, 0.2)',
						'rgba(54, 162, 235, 0.2)',
						'rgba(255, 206, 86, 0.2)',
						'rgba(75, 192, 192, 0.2)',
						'rgba(153, 102, 255, 0.2)',
						'rgba(255, 159, 64, 0.2)',
						'rgba(0, 0, 0, 0.2)'
					],
					backgroundColor: [
						'rgba(255, 26, 104, 1)',
						'rgba(54, 162, 235, 1)',
						'rgba(255, 206, 86, 1)',
						'rgba(75, 192, 192, 1)',
						'rgba(153, 102, 255, 1)',
						'rgba(255, 159, 64, 1)',
						'rgba(0, 0, 0, 1)'
					],
					borderWidth: 0,
					borderSkipped: false,
					borderRadius: 5,
					barPercentage: 0.2,
					categoryPercentage: 0.6
				}]
			},
			options: {
				indexAxis: 'y',
				plugins: {
					legend: {
						display: false
					}
				},
				scales: {
					x: {
						grid: {
							display: false,
							drawBorder : false
						},
						ticks: {
							display: false
						},
						suggestedMax: reservations.length
					},
					y: {
						beginAtZero: true,
						grid: {
							display: false,
							drawBorder : false
						},
						ticks: {
							display: false
						}
					},
				}
			},
			plugins: [progressBar]
		});
	}
/*---------------------------------------------------------------*/

/*-------------------------RESERVATIONS LINE CHART-------------------*/
	let bookingsLineChart, bookings = [];
	document.querySelector('.bookings').querySelector('.range').addEventListener('change', ()=>{
		bookingsLineChart.destroy();
		bookingsRanged(document.querySelector('.bookings').querySelector('.range').value);
	});
	bookingsRanged(document.querySelector('.bookings').querySelector('.range').value);
	function bookingsRanged(range) {
		bookings = [];
		for(let i=0; i<range; i++){
			let bookingsCount = 0, cancelledCount = 0, noShowCount = 0;
			reservations.forEach(reservation => {
				if(moment(new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate() - i)).format('MM-DD-YYYY')>=moment(reservation.check_in).format('MM-DD-YYYY') && moment(new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate() - i)).format('MM-DD-YYYY')<=moment(reservation.check_out).format('MM-DD-YYYY')){
					if(reservation.status == 'Cancelled')
						cancelledCount++;
					else if(reservation.status == 'No Show')
						noShowCount++;
					else
						bookingsCount++;
				}
			});
			bookings.unshift({date:moment(new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate()-i)).format('ddd DD.MM'),bookings: bookingsCount, cancelled: cancelledCount, noShow: noShowCount});
		}
		generateBookingsLineChart();
	}
	function generateBookingsLineChart(){
		bookingsLineChart = new Chart("bookingsLineChart", {
			type: 'line',
			data: {
				labels: bookings.map(a => a.date),
				datasets: [{
					label: 'Reservations',
					data: bookings.map(a => a.bookings),
					fill: false,
					borderColor: 'rgb(75, 192, 192)',
					backgroundColor: 'rgb(75, 192, 192)',
					tension: 0.1
				},
				{
					label: 'Cancelled',
					data: bookings.map(a => a.cancelled),
					fill: false,
					borderColor: 'rgb(169, 169, 169)',
					backgroundColor: 'rgb(169, 169, 169)',
					tension: 0.1
				},
				{
					label: 'No Show',
					data: bookings.map(a => a.noShow),
					fill: false,
					borderColor: 'rgba(255,97,112)',
					backgroundColor: 'rgba(255,97,112)',
					tension: 0.1
				}]
			}
		});
	}
/*---------------------------------------------------------------*/

/*-------------------------GUESTS LINE CHART---------------------*/
	let guestsLineChart, guests = [];
	document.querySelector('.guests').querySelector('.range').addEventListener('change', ()=>{
		guestsLineChart.destroy();
		guestsRanged(document.querySelector('.guests').querySelector('.range').value);
	});
	guestsRanged(document.querySelector('.guests').querySelector('.range').value);
	function guestsRanged(range) {
		guests = [];
		for(let i=0; i<range; i++){
			let totalGuests = 0, totalAdults = 0, totalChildren = 0;
			reservations.forEach(reservation => {
				if(reservation.guests.length > 0 && moment(new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate() - i)).format('MM-DD-YYYY')>=moment(reservation.check_in).format('MM-DD-YYYY') && moment(new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate() - i)).format('MM-DD-YYYY')<=moment(reservation.check_out).format('MM-DD-YYYY')){
					reservation.guests.forEach(guest =>{
						if(guest.birthday && moment().diff(guest.birthday, 'years')<18)
							totalChildren ++;
						else
							totalAdults ++;
					});
					totalGuests = totalAdults+totalChildren;
				}
			});
			console.log(totalChildren);
			console.log(totalAdults);
			guests.unshift({date:moment(new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate()-i)).format('ddd DD.MM'),guests: totalGuests});
			if(i == 0)
				document.querySelector('.total-in-house-guests').innerHTML = totalGuests;
		}
		generateGuestsLineChart();
	}
	function generateGuestsLineChart(){
		guestsLineChart = new Chart("guestsLineChart", {
			type: 'line',
			data: {
				labels: guests.map(a => a.date),
				datasets: [{
					label: 'Guests',
					data: guests.map(a => a.guests),
					fill: false,
					borderColor: 'rgb(75, 192, 192)',
					backgroundColor: 'rgb(75, 192, 192)',
					tension: 0.1
				}]
			},
			options: {
				plugins: {
					legend: {
						display: false
					}
				}
			}
		});
		
	}
/*---------------------------------------------------------------*/
</script>
</body>
</html>