// Background Particle Animation
var particleAnimate = new NodesJs({
	id: 'particle-animate',
	width: window.innerWidth,
	height: window.innerHeight,
	particleSize: 2,
	lineSize: 1,
	particleColor: [184, 82, 67, .1],
	lineColor: [184, 82, 67],
	backgroundFrom: [255, 255, 255],
	backgroundTo: [255, 255, 230],
	backgroundDuration: 4000,
	nobg: false,
	number: window.hasOwnProperty('orientation') ? 30: 100,
	speed: 20
});

// Example starter JavaScript for disabling form submissions if there are invalid fields
(function () {
	'use strict'

	// Fetch all the forms we want to apply custom Bootstrap validation styles to
	var forms = document.querySelectorAll('.needs-validation')

	// Loop over them and prevent submission
	Array.prototype.slice.call(forms)
	.forEach(function (form) {
		form.addEventListener('submit', function (event) {
			if (!form.checkValidity()) {
				event.preventDefault()
				event.stopPropagation()
			}

			form.classList.add('was-validated')
		}, false)
	})
})()

function getFirstSlugURL(url) {
	const path = new URL(url).pathname;

	const pathParts = path.split('/').filter(part => part);
	const firstSlug = pathParts.length > 0 ? pathParts[0] : null;

	return firstSlug;
}

/** Password **/
function PwHideShow() {
	var x = document.getElementById("password");
	if (x.type === "password") {
		x.type = "text";
		$('#show_hide_password i').removeClass( "fa-eye-slash" );
		$('#show_hide_password i').addClass( "fa-eye" );
	} else {
		x.type = "password";
		$('#show_hide_password i').addClass( "fa-eye-slash" );
		$('#show_hide_password i').removeClass( "fa-eye" );
	}
}