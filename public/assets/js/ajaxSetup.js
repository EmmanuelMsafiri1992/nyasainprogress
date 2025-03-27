

$(document).ready(function () {
	
	let xhrOptions = {
		headers: {
			'X-Requested-With': 'XMLHttpRequest',
		},
		async: true,
		cache: true,
		xhrFields: {withCredentials: true},
		crossDomain: true
	};
	
	/* Ajax's calls should always have the CSRF token attached to them; otherwise they won't work */
	let token = $('meta[name="csrf-token"]').attr('content');
	if (token) {
		xhrOptions.headers['X-CSRF-TOKEN'] = token;
	}
	
	$.ajaxSetup(xhrOptions);
	
});
