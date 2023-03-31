var initMap = {
	latitudeDD : 46.989472, 
	longitudeDD : 6.928512,
	zoom : 10
};
var map;

var initCircle = {
	latitudeDD : initMap.latitudeDD,
	longitudeDD : initMap.longitudeDD,
	radius : 20000,
	fillColor : "#000000",
	strokeWeight : 5,
	strokeColor : "#000000",
	movable : false
}
var circle;

var radioMapModeDD;
var radioMapModeDMS;
var inputMapSearch;
var inputMapLatitudeDD;
var inputMapLongitudeDD;
var inputMapLatitudeDMS;
var inputMapLongitudeDMS;
var inputMapZoom;
var inputCircleLatitudeDD;
var inputCircleLongitudeDD;
var inputCircleLatitudeDMS;
var inputCircleLongitudeDMS;
var inputCircleRadius;
var inputCircleFillColor;
var inputCircleStrokeWeight;
var inputCircleStrokeColor;
var checkCircleMovable;

function initialisation()
{
	radioMapModeDD = document.getElementById("mapModeDD");
	radioMapModeDMS = document.getElementById("mapModeDMS");
	inputMapSearch = document.getElementById("mapSearch");
	inputMapLatitudeDD = document.getElementById("mapLatitudeDD");
	inputMapLongitudeDD = document.getElementById("mapLongitudeDD");
	inputMapLatitudeDMS = document.getElementById("mapLatitudeDMS");	
	inputMapLongitudeDMS = document.getElementById("mapLongitudeDMS");	
	inputMapZoom = document.getElementById("mapZoom");
	inputCircleLatitudeDD = document.getElementById("circleLatitudeDD");
	inputCircleLongitudeDD = document.getElementById("circleLongitudeDD");
	inputCircleLatitudeDMS = document.getElementById("circleLatitudeDMS");
	inputCircleLongitudeDMS = document.getElementById("circleLongitudeDMS");
	inputCircleRadius = document.getElementById("circleRadius");
	inputCircleFillColor = document.getElementById("circleFillColor");
	inputCircleStrokeWeight = document.getElementById("circleStrokeWeight");
	inputCircleStrokeColor = document.getElementById("circleStrokeColor");
	checkCircleMovable = document.getElementById("circleMovable");

	getGPSCoo();
	setInitValues();
	
	radioMapModeDD.addEventListener("change", function() {
		mapModeChanged(radioMapModeDD.checked);
	});
	
	radioMapModeDMS.addEventListener("change", function() {
		mapModeChanged(radioMapModeDD.checked);
	});
	
	// écoute les changements fait par l'utilisateur
	inputMapLatitudeDD.addEventListener("change", function() {
		/*if(event != null)
			alertObj(event);*/
		inputMapLatitudeDMS.value = convertDDToDMS(this.value);
		//setMap();
	});
	inputMapLongitudeDD.addEventListener("change", function() {
		inputMapLongitudeDMS.value = convertDDToDMS(this.value);
		//setMap();
	});
	inputMapLatitudeDMS.addEventListener("change", function() {
		inputMapLatitudeDD.value = parseDMS(this.value);
		setMap();
	});
	inputMapLongitudeDMS.addEventListener("change", function() {
		inputMapLongitudeDD.value = parseDMS(this.value);
		setMap();
	});
	inputMapZoom.addEventListener("change", setMap);
	

	map = new google.maps.Map(document.getElementById("divMap"));
	map.addListener('center_changed', function() {
		// Propage l'évenement produit par l'api Google pour qu'il soit capté par le "change" défini juste au-dessus.
		// https://stackoverflow.com/questions/16250464/trigger-change-event-when-the-input-value-changed-programatically
		inputMapLatitudeDD.value = map.getCenter().lat();
		inputMapLatitudeDD.dispatchEvent(new Event('change')); 

		inputMapLongitudeDD.value = map.getCenter().lng();
		inputMapLongitudeDD.dispatchEvent(new Event('change'));
	});
	map.addListener('zoom_changed', function() {
		inputMapZoom.value = map.getZoom();
	});
	setMap();

	// écoute les changements fait par l'utilisateur
	inputCircleLatitudeDD.addEventListener("change", function() {
		inputCircleLatitudeDMS.value = convertDDToDMS(this.value);
		//setCircle();
	});
	inputCircleLongitudeDD.addEventListener("change", function() {
		inputCircleLongitudeDMS.value = convertDDToDMS(this.value);
		//setCircle();
	});
	inputCircleLatitudeDMS.addEventListener("change", function() {
		inputCircleLatitudeDD.value = parseDMS(this.value);
		setCircle();
	});
	inputCircleLongitudeDMS.addEventListener("change", function() {
		inputCircleLongitudeDD.value = parseDMS(this.value);
		setCircle();
	});
	inputCircleRadius.addEventListener("change", setCircle);
	inputCircleFillColor.addEventListener("change", setCircle);
	inputCircleStrokeWeight.addEventListener("change", setCircle);
	inputCircleStrokeColor.addEventListener("change", setCircle);
	checkCircleMovable.addEventListener("change", setCircle);

	circle = new google.maps.Circle();
	circle.addListener('center_changed', function() {
		inputCircleLatitudeDD.value = circle.getCenter().lat();
		inputCircleLatitudeDD.dispatchEvent(new Event('change')); 
		
		inputCircleLongitudeDD.value = circle.getCenter().lng();
		inputCircleLongitudeDD.dispatchEvent(new Event('change'));
	});
	circle.addListener('radius_changed', function() {
		inputCircleRadius.value = circle.getRadius();
	});
	setCircle();
	
	initSearchBox();
}

// https://developer.mozilla.org/en-US/docs/Web/API/Geolocation/Using_geolocation
// https://stackoverflow.com/questions/2577305/get-gps-location-from-the-web-browser
function getGPSCoo()
{
	if("geolocation" in navigator)
	{
		navigator.geolocation.getCurrentPosition(function(position) {
			moveMapAndCircleTo(position.coords.latitude, position.coords.longitude);
			navigator.geolocation.clearWatch(watchID); // Stop watching the user
		});
	}
}

function moveMapAndCircleTo(lat, lng)
{
	inputMapLatitudeDD.value = lat;
	inputMapLongitudeDD.value = lng;
	
	inputCircleLatitudeDD.value = lat;
	inputCircleLongitudeDD.value = lng;
	
	initMap.latitudeDD = lat;
	initMap.longitudeDD = lng;

	initCircle.latitudeDD = lat;
	initCircle.longitudeDD = lng;
	
	refresh();
}

function setInitValues()
{
	radioMapModeDD.checked = true;
	radioMapModeDD.dispatchEvent(new Event('change')); 
	
	inputMapLatitudeDD.value = initMap.latitudeDD;
	inputMapLongitudeDD.value = initMap.longitudeDD;
	inputMapLatitudeDMS.value = convertDDToDMS(initMap.latitudeDD);
	inputMapLongitudeDMS.value = convertDDToDMS(initMap.longitudeDD);
	inputMapZoom.value = initMap.zoom;
	
	inputCircleLatitudeDD.value = initCircle.latitudeDD;
	inputCircleLongitudeDD.value = initCircle.longitudeDD;
	inputCircleLatitudeDMS.value = initCircle.latitudeDMS;
	inputCircleLongitudeDMS.value = initCircle.longitudeDMS;
	inputCircleRadius.value = initCircle.radius;
	inputCircleFillColor.value = initCircle.fillColor;
	inputCircleStrokeWeight.value = initCircle.strokeWeight;
	inputCircleStrokeColor.value = initCircle.strokeColor;
	checkCircleMovable.checked = initCircle.movable;
}

function setMap()
{
	// TODO blinder les inputs
	var latDD = inputMapLatitudeDD.value * 1.0;
	var lngDD = inputMapLongitudeDD.value * 1.0;
	var zoom = inputMapZoom.value * 1.0;

	map.setCenter(new google.maps.LatLng(latDD, lngDD));
	map.setZoom(zoom);
	//map.setMapTypeId("roadmap"); // https://developers.google.com/maps/documentation/javascript/maptypes
	
	// https://developers.google.com/maps/documentation/javascript/events?hl=fr
	google.maps.event.trigger(map, 'center_changed');
	google.maps.event.trigger(map, 'zoom_changed');
}

function setCircle()
{
	// TODO blinder les inputs
	var latDD = inputCircleLatitudeDD.value * 1.0;
	var lngDD = inputCircleLongitudeDD.value * 1.0;
	var radius = inputCircleRadius.value * 1.0;
	var strokeWeight = inputCircleStrokeWeight.value;
	var strokeColor = inputCircleStrokeColor.value;
	var fillColor = inputCircleFillColor.value;
	var movable = checkCircleMovable.checked;
	
	// https://www.touraineverte.fr/google-maps-api-version-3/reference/CircleOptions.html#CircleOptions
	var circleOptions = {
		map: map,
		center: new google.maps.LatLng(latDD, lngDD),
		radius: radius,
		strokeWeight: strokeWeight,
		strokeColor: strokeColor,
		fillColor: fillColor,
		editable: movable,
		draggable: movable
	}
	circle.setValues(circleOptions);
}

function refresh()
{
	setMap();
	setCircle();
}

function reset()
{
	setInitValues();
	refresh();
}

// https://stackoverflow.com/questions/1140189/converting-latitude-and-longitude-to-decimal-values
function parseDMS(input)
{
    var parts = input.split(/[^\d\w\.]+/);   
    return convertDMSToDD(parts[0], parts[1], parts[2], parts[3]);
}

function convertDMSToDD(degrees, minutes, seconds, direction)
{   
    var dd = Number(degrees) + Number(minutes)/60 + Number(seconds)/(60*60);

    if (direction == "S" || direction == "W") // Don't do anything for N or E
        dd = dd * -1;
    return dd;
}

// http://en.marnoto.com/2014/04/converter-coordenadas-gps.html
function convertDDToDMS(val)
{
	// TODO il manque les N, S, E, W...
	// je crois que si les valeurs sont négatives il faut passer en S et W (voir le com' de convertDMSToDD)
	var valDeg, valMin, valSec, result;
	val = Math.abs(val); // -40.601203 = 40.601203

	valDeg = Math.floor(val); // 40.601203 = 40
	result = valDeg + "º"; // 40º

	valMin = Math.floor((val - valDeg) * 60); // 36.07218 = 36
	result += valMin + "'"; // 40º36'

	valSec = Math.round((val - valDeg - valMin / 60) * 3600 * 1000) / 1000; // 40.601203 = 4.331 
	result += valSec + '"'; // 40º36'4.331"

	return result;
}

// https://stackoverflow.com/questions/3100319/event-on-a-disabled-input
// https://stackoverflow.com/questions/6172911/what-is-the-difference-between-readonly-true-readonly-readonly
// https://stackoverflow.com/questions/37497168/how-to-remove-readonly-attribute-when-button-is-clicked
// https://stackoverflow.com/questions/1306708/how-to-add-a-readonly-attribute-to-an-input
function mapModeChanged(mapModeDD)
{
	if(mapModeDD)
	{
		inputMapLatitudeDD.removeAttribute("readonly");  
		inputMapLongitudeDD.removeAttribute("readonly");  
		inputCircleLatitudeDD.removeAttribute("readonly");  
		inputCircleLongitudeDD.removeAttribute("readonly");  
		inputMapLatitudeDMS.setAttribute("readonly", true);  
		inputMapLongitudeDMS.setAttribute("readonly", true);  
		inputCircleLatitudeDMS.setAttribute("readonly", true);  
		inputCircleLongitudeDMS.setAttribute("readonly", true);  
	}
	else
	{
		inputMapLatitudeDD.setAttribute("readonly", true);  
		inputMapLongitudeDD.setAttribute("readonly", true);  
		inputCircleLatitudeDD.setAttribute("readonly", true);  
		inputCircleLongitudeDD.setAttribute("readonly", true);  
		inputMapLatitudeDMS.removeAttribute("readonly");  
		inputMapLongitudeDMS.removeAttribute("readonly");  
		inputCircleLatitudeDMS.removeAttribute("readonly");  
		inputCircleLongitudeDMS.removeAttribute("readonly");  
	}
}

// https://developers.google.com/maps/documentation/javascript/examples/places-searchbox
// This example adds a search box to a map, using the Google Place Autocomplete
// feature. People can enter geographical searches. The search box will return a
// pick list containing a mix of places and predicted search terms.

// This example requires the Places library. Include the libraries=places
// parameter when you first load the API. For example:
// <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">
function initSearchBox()
{
	// Create the search box and link it to the UI element.
	//var inputMapSearch = document.getElementById('pac-input');
	var searchBox = new google.maps.places.SearchBox(inputMapSearch);
	//map.controls[google.maps.ControlPosition.TOP_LEFT].push(inputMapSearch);

	// Bias the SearchBox results towards current map's viewport.
	/*map.addListener('bounds_changed', function() {
		searchBox.setBounds(map.getBounds());
	});*/

	//var markers = [];
	// Listen for the event fired when the user selects a prediction and retrieve more details for that place.
	searchBox.addListener('places_changed', function() {
		var places = searchBox.getPlaces();

		if (places.length == 0)
			return;
		
		// Clear out the old markers.
		/*markers.forEach(function(marker) {
			marker.setMap(null);
		});
		markers = [];*/

		// For each place, get the icon, name and location.
		var bounds = new google.maps.LatLngBounds();
		places.forEach(function(place)
		{
			if (!place.geometry)
			{
				console.log("Returned place contains no geometry");
				return;
			}
			
			/*var icon = {
				url: place.icon,
				size: new google.maps.Size(71, 71),
				origin: new google.maps.Point(0, 0),
				anchor: new google.maps.Point(17, 34),
				scaledSize: new google.maps.Size(25, 25)
			};

			// Create a marker for each place.
			markers.push(new google.maps.Marker({
				map: map,
				icon: icon,
				title: place.name,
				position: place.geometry.location
			}));*/
			
			moveMapAndCircleTo(place.geometry.location.lat(), place.geometry.location.lng());

			if (place.geometry.viewport)
				bounds.union(place.geometry.viewport); // Only geocodes have viewport.
			else
				bounds.extend(place.geometry.location);
		});
		
		//map.fitBounds(bounds); // déplace le viewport. Inutile vu que je le fais moi-même
	});
}

function alertObj(obj)
{
	var output = '';
	for (var property in obj) {
	  output += property + ': ' + obj[property]+';\n';
	}
	alert(output);
}

google.maps.event.addDomListener(window, 'load', initialisation);