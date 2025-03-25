const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

/* mix.js('resources/js/app.js', 'public/assets/resources/js'); */

/* Sass Compilation  */
mix.sass('resources/sass/app.scss', 'public/assets/resources/css');
mix.sass('resources/sass/app.rtl.scss', 'public/assets/resources/css');
mix.sass('resources/sass/admin.scss', 'public/assets/resources/css');
mix.options({
	processCssUrls: false
});

/* CSS Concatenation */
mix.combine([
	'public/assets/resources/css/app.css',
	'public/assets/bootstrap/5.2.2/css/bootstrap.min.css',
	'public/assets/plugins/select2/css/select2.min.css',
	'public/assets/css/style.css',
	'public/assets/css/style-main.css',
	'public/assets/css/skins/default.css',
	'public/assets/css/skins/dark.css',
	'public/assets/plugins/social-media/css/colors.css',
	'public/assets/plugins/social-media/css/social-media.css',
	'public/assets/plugins/owlcarousel/assets/owl.carousel.min.css',
	'public/assets/plugins/owlcarousel/assets/owl.theme.default.min.css',
	'public/assets/css/flags/flags.min.css',
	'public/assets/plugins/sweetalert2/11.1.10/sweetalert2.min.css',
	// 'public/assets/plugins/jquery-autocomplete/1.4.11/jquery-autocomplete.css',
	'public/assets/plugins/autoComplete.js/10.2.7/css/autoComplete.css',
	'public/assets/plugins/intl-tel-input/17.0.18/css/intlTelInput.css',
	'public/assets/plugins/busy-load/0.1.2/app.min.css',
], 'public/dist/public/styles.css');

/* RTL CSS Concatenation */
mix.combine([
	'public/assets/resources/css/app.rtl.css',
	'public/assets/bootstrap/5.2.2/css/bootstrap.rtl.css',
	'public/assets/plugins/select2/css/select2.min.css',
	'public/assets/css/rtl/style.css',
	'public/assets/css/rtl/style-main.css',
	'public/assets/css/skins/default.css',
	'public/assets/css/skins/dark.css',
	'public/assets/plugins/social-media/css/colors.css',
	'public/assets/plugins/social-media/css/social-media.css',
	'public/assets/plugins/owlcarousel/assets/owl.carousel.min.css',
	'public/assets/plugins/owlcarousel/assets/owl.theme.default.min.css',
	'public/assets/css/flags/flags.min.css',
	'public/assets/plugins/sweetalert2/11.1.10/sweetalert2.min.css',
	// 'public/assets/plugins/jquery-autocomplete/1.4.11/jquery-autocomplete-rtl.css',
	'public/assets/plugins/autoComplete.js/10.2.7/css/autoComplete.css',
	'public/assets/plugins/intl-tel-input/17.0.18/css/intlTelInput.css',
	'public/assets/plugins/busy-load/0.1.2/app.min.css',
], 'public/dist/public/styles.rtl.css');

/* JS Concatenation */
mix.combine([
	'public/assets/js/vanilla.js',
	'public/assets/plugins/jquery/3.3.1/jquery.min.js',
	'public/assets/plugins/jqueryui/1.13.2/jquery-ui.min.js',
	/* 'public/assets/plugins/popper.js/2.9.3/popper.min.js', */
	/* 'public/assets/bootstrap/5.2.2/js/bootstrap.min.js', */
	/* Bundled JS files (bootstrap.bundle.js and minified bootstrap.bundle.min.js) include Popper, but not jQuery. */
	'public/assets/bootstrap/5.2.2/js/bootstrap.bundle.min.js',
	/* 'public/assets/resources/js/app.js', */
	'public/assets/plugins/jquery.fs.scroller/jquery.fs.scroller.min.js',
	'public/assets/plugins/select2/js/select2.full.min.js',
	'public/assets/plugins/social-media/js/social-share.js',
	'public/assets/plugins/jquery-parallax/1.1/jquery.parallax-1.1.js',
	'public/assets/plugins/hideMaxListItems/hideMaxListItems.js',
	'public/assets/plugins/jquery-nice-select/js/jquery.nice-select.min.js',
	'public/assets/plugins/jquery.nicescroll/dist/jquery.nicescroll.min.js',
	'public/assets/plugins/owlcarousel/owl.carousel.js',
	'public/assets/plugins/pnotify/5.2.0/dist/PNotify.js',
	'public/assets/plugins/sweetalert2/11.1.10/sweetalert2.all.min.js',
	// 'public/assets/plugins/jquery-autocomplete/1.4.11/jquery.mockjax.js',
	// 'public/assets/plugins/jquery-autocomplete/1.4.11/jquery.autocomplete.min.js',
	'public/assets/plugins/autoComplete.js/10.2.7/autoComplete.min.js',
	'public/assets/plugins/bootstrap-waitingfor/bootstrap-waitingfor.min.js',
	'public/assets/plugins/counter-up/2.0.2/dist/index.js',
	'public/assets/plugins/intl-tel-input/17.0.18/js/intlTelInput.js',
	'public/assets/plugins/busy-load/0.1.2/app.min.js',
	
	'public/assets/js/ajaxSetup.js',
	'public/assets/js/global.js',
	'public/assets/js/http.request.js',
	'public/assets/js/script.js',
	'public/assets/plugins/form-validation/form-validation.js',
	// 'public/assets/js/app/jquery-autocomplete.cities.js',
	'public/assets/js/app/autocomplete.cities.js',
	'public/assets/js/app/auth-fields.js',
	'public/assets/js/app/show.phone.js',
	'public/assets/js/app/make.favorite.js',
	'public/assets/js/app/dark-mode.js'
], 'public/dist/public/scripts.js');

/* ADMIN PANEL */

/* CSS Concatenation for Admin Panel */
mix.combine([
	'public/assets/resources/css/admin.css',
	'public/assets/plugins/pace/1.0.2/pace.min.css',
	'public/assets/plugins/pnotify/5.2.0/dist/PNotify.css',
	'public/assets/plugins/pnotify/5.2.0/dist/Material.css',
	'public/assets/plugins/pnotify/5.2.0/dist/Custom.css',
	'public/assets/plugins/pnotify/5.2.0/modules/confirm/PNotifyConfirm.css',
	'public/assets/plugins/sweetalert2/11.1.10/sweetalert2.min.css',
	'public/assets/plugins/intl-tel-input/17.0.18/css/intlTelInput.css',
	'public/assets/plugins/busy-load/0.1.2/app.min.css',
	
	'public/assets/admin/css/style.css',
	'public/assets/admin/css/style-main.css',
	'public/assets/plugins/social-media/css/colors.css',
	'public/assets/plugins/social-media/css/social-media.css'
], 'public/dist/admin/styles.css');

/* JS Concatenation for Admin Panel */
mix.combine([
	'public/assets/js/vanilla.js',
	'public/assets/plugins/jquery/3.3.1/jquery.min.js',
	'public/assets/plugins/popper.js/2.9.3/popper.min.js',
	'public/assets/bootstrap/5.2.2/js/bootstrap.min.js',
	'public/assets/admin/js/app.js',
	'public/assets/plugins/perfect-scrollbar/0.7.1/perfect-scrollbar.jquery.min.js',
	'public/assets/plugins/sparkline/sparkline.js',
	'public/assets/admin/js/waves.js',
	'public/assets/admin/js/sidebarmenu.js',
	'public/assets/admin/js/feather.min.js',
	'public/assets/admin/js/custom.js',
	'public/assets/plugins/pnotify/5.2.0/dist/PNotify.js',
	'public/assets/plugins/pnotify/5.2.0/modules/font-awesome5/PNotifyFontAwesome5.js',
	'public/assets/plugins/pnotify/5.2.0/modules/font-awesome5-fix/PNotifyFontAwesome5Fix.js',
	'public/assets/plugins/pnotify/5.2.0/modules/confirm/PNotifyConfirm.js',
	'public/assets/plugins/sweetalert2/11.1.10/sweetalert2.all.min.js',
	'public/assets/plugins/pace/1.0.2/pace.min.js',
	'public/assets/plugins/intl-tel-input/17.0.18/js/intlTelInput.min.js',
	'public/assets/plugins/busy-load/0.1.2/app.min.js',
	
	'public/assets/js/ajaxSetup.js',
	'public/assets/js/global.js',
	'public/assets/js/http.request.js',
	'public/assets/js/app/auth-fields.js',
], 'public/dist/admin/scripts.js');

/* Disable Compilation Notification */
/* mix.disableNotifications(); */

/* Cache Busting */
mix.version();
