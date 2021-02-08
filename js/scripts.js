const map;

function initMap() {
	map = new google.maps.Map(document.getElementById('g-map'), {
		center: {lat: 35.5786665, lng: -80.6581295},
		zoom: 8
	});
}

const viewMapBtn = document.getElementById('view-map');
const googleMap = document.getElementById('g-map');

viewMapBtn.addEventListener('click', function() {

  if (googleMap.style.maxHeight === '0px') {
		function addMarker(coords, eventAddress, eventLocation, id) {
			console.log(coords);
			var markerContent = `<div id="event-${id}">`+`<a target="_blank" href="https://www.google.com/maps/search/?api=1&query=${coords.lat},${coords.lng}" class="marker-header">${eventLocation}</a>`+'<div class="marker-divider"></div>'+`<p class="marker-location">${eventAddress}</p>`;

			var marker = new google.maps.Marker({
				position: coords,
				map: map,
				id: id,
				icon: '/wp-content/uploads/2020/10/CVPC-website-location-marker.png'
			});

			var infoWindow = new google.maps.InfoWindow({
				content: markerContent
			});

			marker.addListener('click', function() {
				infoWindow.open(map, marker);
			});
		}

		async function getLongAndLat(eventAddress, eventLocation, id) {
			let response = await fetch(`https://maps.googleapis.com/maps/api/geocode/json?address=${eventAddress}&key=''`);
			let user = await response.json();
			return addMarker(user.results[0].geometry.location, eventAddress, eventLocation, id);
		}

		// FRONTEND LOGIC

		const events = document.getElementsByClassName('event-address');
		const locations = document.getElementsByClassName('event-location');
		const ids = document.getElementsByClassName('calendar-card');

		for (let i = 0; i < events.length; i++) {
			for (let j = 0; j < locations.length; j++) {
				for (var k = 0; k < ids.length; k++) {
					if (i === j && i === k) {
						let address = events[i].innerHTML;
						let location = locations[i].innerHTML;
						let id = ids[i].id;
						getLongAndLat(address, location, id);
					}
				}
			}
		}
    googleMap.style.maxHeight = googleMap.style.height;
  } else {
    googleMap.style.maxHeight = '0px';
  }
});
