ymaps.ready(init);

lisette_jdp_var_prefix = 'lisette_jdp_';
lisette_jdp_default_city_center = { 'lng':49.122853, 'lat':55.786764 };

function init() {
}

function address_is_changed() {
	var prefix = lisette_jdp_var_prefix;
	var country = document.getElementById( prefix + 'country' );
	var state = document.getElementById( prefix + 'state' );
	var city = document.getElementById( prefix + 'city' );
	var district = document.getElementById( prefix + 'district_' + city.options[city.selectedIndex].value );
	var locality = document.getElementById( prefix + 'locality' );
	var street = document.getElementById( prefix + 'street' );
	
	var address = 
		country.options[country.selectedIndex].text + ', ' + 
		state.options[state.selectedIndex].text + ', ' +
		( locality.value == '' 
			? city.options[city.selectedIndex].text + 
				( district === undefined  
					? '' 
					: ', ' + district.options[district.selectedIndex].text
				) 
			: locality.value 
		) + ( street.value == '' ? '' : ', ' + street.value );

	var geocoder;
	geocoder = ymaps.geocode(address);
	geocoder.then(
		function (res) 
		{
			var firstGeoObject = res.geoObjects.get(0);
			var coords = firstGeoObject.geometry.getCoordinates();

			var lat = document.getElementById( prefix + 'lat' );
			var lng = document.getElementById( prefix + 'lng' );
			lat.value = coords[0]; replace_content_by(lat);
			lng.value = coords[1]; replace_content_by(lng);
		},
		function (err) 
		{
			alert("Адрес не найден!");
		}
	);
}
