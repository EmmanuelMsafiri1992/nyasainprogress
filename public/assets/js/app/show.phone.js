

$(document).ready(function () {
	
	$('.phoneBlock').click(function (e) {
		e.preventDefault(); /* prevents submit or reload */
		
		showPhone();
		
		return false;
	});
	
});

/**
 * Show the Contact's phone
 * @returns {boolean}
 */
function showPhone() {
	let postId = $('#postId').val();
	
	if (postId === 0 || postId === '0' || postId === '') {
		return false;
	}
	
	let phoneBlockEl = $('.phoneBlock');
	let iconEl = phoneBlockEl.find('i');
	
	let resultCanBeCached = true;
	let url = siteUrl + '/ajax/post/phone';
	
	let ajax = $.ajax({
		method: 'POST',
		url: url,
		data: {
			'post_id': postId,
			'_token': $('input[name=_token]').val()
		},
		cache: resultCanBeCached,
		beforeSend: function() {
			/* Change the button indicator */
			if (iconEl) {
				iconEl.removeClass('fa-solid fa-mobile-screen-button');
				iconEl.addClass('spinner-border spinner-border-sm').css({'vertical-align': 'middle'});
				iconEl.attr({'role': 'status', 'aria-hidden': 'true'});
			}
		}
	});
	ajax.done(function (xhr) {
		if (typeof xhr.phone == 'undefined') {
			return false;
		}
		
		phoneBlockEl.html('<i class="fa-solid fa-mobile-screen-button"></i> ' + xhr.phone);
		phoneBlockEl.attr('href', xhr.link);
		phoneBlockEl.tooltip('dispose'); /* Disable Tooltip */
		
		if (resultCanBeCached) {
			$('#postId').val(0);
		}
	});
	ajax.fail(function(xhr) {
		let message = getErrorMessageFromXhr(xhr);
		if (message !== null) {
			jsAlert(message, 'error');
		}
	});
}
