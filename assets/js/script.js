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
})();

// Get the button scrollToTop
let scrollToTop = document.getElementById("scroll-to-top");

// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
	if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
		scrollToTop.style.display = "block";
	} else {
		scrollToTop.style.display = "none";
	}
}

// When the user clicks on the button, scroll to the top of the document
function topFunction() {
	document.body.scrollTop = 0;
	document.documentElement.scrollTop = 0;
}

$(window).scroll(function() {
	$(".slideanim").each(function() {
		var pos = $(this).offset().top;

		var winTop = $(window).scrollTop();

		if (pos < winTop + 600) {
			$(this).addClass("slide");
		}
	});
});

function googleTranslateElementInit() {
	new google.translate.TranslateElement({
		pageLanguage: 'en', 
		includedLanguages: 'en,tl', // Customize included languages
		layout: google.translate.TranslateElement.InlineLayout.SIMPLE
	}, 'google_translate_element');
}

// function googleTranslateElementInit() {
// 	new google.translate.TranslateElement({
// 		pageLanguage: 'en', // Set your default page language
// 		includedLanguages: 'en,es,fr,de', // Customize included languages
// 		layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
// 		autoDisplay: false
// 	}, 'google_translate_element');
// }

function getFirstSlugURL(url) {
	const path = new URL(url).pathname;

	const pathParts = path.split('/').filter(part => part);
	const firstSlug = pathParts.length > 0 ? pathParts[0] : null;

	return firstSlug;
}

/** Copy to Clipboard **/
function copytoclipbrd(id,etype) {
	// Get the text field
	var copyText = document.getElementById(id);

	if (etype=="value") {
		// Select the text field
		copyText.select();
		copyText.setSelectionRange(0, 99999); // For mobile devices

		// Copy the text inside the text field
		navigator.clipboard.writeText(copyText.value);

		// Alert the copied text
		// alert("Copied the text: " + copyText.value);
	} else {
		// Copy the text inside the text field
		navigator.clipboard.writeText(copyText.innerHTML);

		// Alert the copied text
		// alert("Copied the text: " + copyText.innerHTML);
	}	
}

/** Password **/
function PwHideShow() {
	var x = document.getElementById("password");
	if (x.type === "password") {
		x.type = "text";
		$('#show_hide_password i').removeClass( "fa-eye-slash" );
		$('#show_hide_password i').addClass( "fa-eye" );
		x.onpaste = true;
	} else {
		x.type = "password";
		$('#show_hide_password i').addClass( "fa-eye-slash" );
		$('#show_hide_password i').removeClass( "fa-eye" );
		x.onpaste = false;
	}
}

function PwHideShow2() {
	var x = document.getElementById("password2");
	if (x.type === "password") {
		x.type = "text";
		$('#show_hide_password2 i').removeClass( "fa-eye-slash" );
		$('#show_hide_password2 i').addClass( "fa-eye" );
	} else {
		x.type = "password";
		$('#show_hide_password2 i').addClass( "fa-eye-slash" );
		$('#show_hide_password2 i').removeClass( "fa-eye" );
	}
}

function PinHideShow() {
	var x = document.getElementById("pinInput");
	if (x.type === "password") {
		x.type = "text";
		$('#show_hide_pin i').removeClass( "fa-eye-slash" );
		$('#show_hide_pin i').addClass( "fa-eye" );
	} else {
		x.type = "password";
		$('#show_hide_pin i').addClass( "fa-eye-slash" );
		$('#show_hide_pin i').removeClass( "fa-eye" );
	}
}

function fnUpDateTime() {
	let currentTime = new Date();
	// let currentTime = currentTimeX.toLocaleString('en-US', { timeZone: 'Asia/Manila' });
	let currentUTCTime = currentTime.toUTCString();

	const xmonthz = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
	const xdayzname = ["Sunday","Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

	let currSeconds = currentTime.getSeconds();
	let currMin = currentTime.getMinutes();
	let currHour = currentTime.getHours(); // 24hour
	// let currAMPM = currentTime.();

	let currDay = xdayzname[currentTime.getDay()];
	let currDayNo = currentTime.getDate();
	let currMonth = currentTime.getMonth(); // Month Number
	let currMonthName = xmonthz[currentTime.getMonth()]; // Month Name
	let curYear = currentTime.getFullYear();

	const formattedTime = new Intl.DateTimeFormat('default', {
		hour: 'numeric',
		minute: 'numeric',
		second: 'numeric',
		hour12: true // Ensures 12-hour format with AM/PM
	}).format(currentTime);

	let dateBeginNow = currDay + " | " + currMonthName + " " + currDayNo + ", " + curYear + " | " + formattedTime;
	let currntDateX = currMonthName + " " + currDayNo + ", " + curYear;

	document.getElementById("nvbr-date").innerHTML = dateBeginNow;
	document.getElementById("label-datereturn").innerHTML = currntDateX;
	document.getElementById("label-daynreturn").innerHTML = currDay;
	document.getElementById("label-timereturn").innerHTML = formattedTime;
}
setInterval(fnUpDateTime, 1000); // Run updateTime() every second

/** Slick **/
$(document).ready(function() {
	$('.slick-frontbanner').slick({
		infinite: true,
		fade: true,
		slidesToShow: 1,
		slidesToScroll: 1,
		autoplay: true,
		autoplaySpeed: 5000,
		arrows: true,
		dots: true,
		cssEase: 'linear'
	});
});

$('.slider-for').slick({
	slidesToShow: 1,
	slidesToScroll: 1,
	arrows: true,
	fade: true,
	asNavFor: '.slider-nav'
});

$('.slider-nav').slick({
	slidesToShow: 3,
	slidesToScroll: 1,
	asNavFor: '.slider-for',
	dots: true,
	centerMode: true,
	focusOnSelect: true
});