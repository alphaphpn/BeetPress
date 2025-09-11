// Check if the Geolocation API is supported by the browser
let gpsErrMsg;
let gpsLocationData;

if ("geolocation" in navigator) {
	// Geolocation is available
	navigator.geolocation.getCurrentPosition(
		// Success callback
		(position) => {
			const latitude = position.coords.latitude;
			const longitude = position.coords.longitude;
			console.log(`Latitude: ${latitude}, Longitude: ${longitude}`);
			gpsErrMsg = `Latitude: ${latitude}, Longitude: ${longitude}`;
			gpsLocationData = latitude+','+longitude;
			console.log(gpsLocationData);

			// $('#GpsErrMassage').html(gpsErrMsg);
			// $('#GpsErrMassage').attr('href','//google.com/maps/dir/7.7881218435487245,122.57361312438182/'+gpsLocationData+'/@7.7881218435487245,122.57361312438182,19z/data=!4m4!4m3!1m0!1m1!4e1');
			$('#GpsErrMassage').attr('onclick','window.open("//google.com/maps/dir/7.7881218435487245,122.57361312438182/'+gpsLocationData+'/@7.7881218435487245,122.57361312438182,19z/data=!4m4!4m3!1m0!1m1!4e1","_blank", "toolbar=yes,scrollbars=yes,resizable=yes,fullscreen=yes")');
			$('#gpsInput').val(gpsLocationData);
			// You can now use these coordinates to display a map, provide local content, etc.
		},
		// Error callback (optional)
		(error) => {
			switch(error.code) {
				case error.PERMISSION_DENIED:
				console.error("User denied the request for Geolocation.");
				gpsErrMsg = "User denied the request for Geolocation.";
				break;

				case error.POSITION_UNAVAILABLE:
				console.error("Location information is unavailable.");
				gpsErrMsg = "Location information is unavailable.";
				break;

				case error.TIMEOUT:
				console.error("The request to get user location timed out.");
				gpsErrMsg = "The request to get user location timed out.";
				break;

				default:
				console.error("An unknown error occurred.");
				gpsErrMsg = "An unknown error occurred.";
				break;
			}
		}
	);
} else {
	// Geolocation is not supported
	console.error("Geolocation is not supported by your browser.");
	gpsErrMsg = "Geolocation is not supported by your browser.";
}