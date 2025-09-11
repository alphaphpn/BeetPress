/** Disable form submissions if there are invalid fields **/
window.onscroll = function() {scrollFunction()};

(function() {
	'use strict';
	window.addEventListener('load', function() {
		// Get the forms we want to add validation styles to
		var forms = document.getElementsByClassName('needs-validation');
		// Loop over them and prevent submission
		var validation = Array.prototype.filter.call(forms, function(form) {
			form.addEventListener('submit', function(event) {
				if (form.checkValidity() === false) {
					event.preventDefault();
					event.stopPropagation();
				}
				form.classList.add('was-validated');
			}, false);
		});
	}, false);
})();

jQuery(function ($) {
	$(".sidebar-dropdown > a").click(function() {
		$(".sidebar-submenu").slideUp(200);
		if ($(this).parent().hasClass("active")) {
			$(".sidebar-dropdown").removeClass("active");
			$(this).parent().removeClass("active");
		} else {
			$(".sidebar-dropdown").removeClass("active");
			$(this).next(".sidebar-submenu").slideDown(200);
			$(this).parent().addClass("active");
		}
	});

	$("#close-sidebar").click(function() {
		$(".page-wrapper").removeClass("toggled");
	});

	$("#show-sidebar").click(function() {
		$(".page-wrapper").addClass("toggled");
	});
});

$(document).ready(function() {
	// color picker start
	$('#idxcolor_1').colorpicker();
	$('#idxcolor_1').focusout(function() {
		vclr_1 = $('#idxcolor_1').val();
		$('#idxcolor_1').css('background-color',vclr_1);
	});

	$('#idxcolor_2').colorpicker();
	$('#idxcolor_2').focusout(function() {
		vclr_2 = $('#idxcolor_2').val();
		$('#idxcolor_2').css('background-color',vclr_2);
	});

	$('#idxcolor_3').colorpicker();
	$('#idxcolor_3').focusout(function() {
		vclr_3 = $('#idxcolor_3').val();
		$('#idxcolor_3').css('background-color',vclr_3);
	});

	$('#idxcolor_4').colorpicker();
	$('#idxcolor_4').focusout(function() {
		vclr_4 = $('#idxcolor_4').val();
		$('#idxcolor_4').css('background-color',vclr_4);
	});

	$('#idxcolor_5').colorpicker();
	$('#idxcolor_5').focusout(function() {
		vclr_5 = $('#idxcolor_5').val();
		$('#idxcolor_5').css('background-color',vclr_5);
	});

	$('#idxcolor_6').colorpicker();
	$('#idxcolor_6').focusout(function() {
		vclr_6 = $('#idxcolor_6').val();
		$('#idxcolor_6').css('background-color',vclr_6);
	});

	$('#idxcolor_7').colorpicker();
	$('#idxcolor_7').focusout(function() {
		vclr_7 = $('#idxcolor_7').val();
		$('#idxcolor_7').css('background-color',vclr_7);
	});

	$('#idxcolor_8').colorpicker();
	$('#idxcolor_8').focusout(function() {
		vclr_8 = $('#idxcolor_8').val();
		$('#idxcolor_8').css('background-color',vclr_8);
	});

	$('#idxcolor_9').colorpicker();
	$('#idxcolor_9').focusout(function() {
		vclr_9 = $('#idxcolor_9').val();
		$('#idxcolor_9').css('background-color',vclr_9);
	});

	$('#idxcolor_10').colorpicker();
	$('#idxcolor_10').focusout(function() {
		vclr_10 = $('#idxcolor_10').val();
		$('#idxcolor_10').css('background-color',vclr_10);
	});

	// color picker end
});