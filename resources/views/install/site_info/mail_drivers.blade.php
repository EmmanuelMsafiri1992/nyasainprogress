@php
	$siteInfo ??= [];
	$rules ??= [];
	$mailDrivers ??= [];
	$mailDriversSelectorsJson ??= '[]';
	$mailDriversRules ??= [];
@endphp
<hr class="border-0 bg-secondary">

<h3 class="title-3">
	<i class="fa-solid fa-envelope"></i> {{ trans('messages.system_email_configuration') }}
</h3>

<div class="row row-cols-2">
	<div class="col">
		@include('install.helpers.form_control', [
			'type'    => 'select',
			'name'    => 'driver',
			'label'   => trans('messages.mail_driver'),
			'value'   => $siteInfo['driver'] ?? '',
			'options' => $mailDrivers,
			'rules'   => $rules,
		])
	</div>
	<div class="col">
		@include('install.helpers.form_control', [
			'type'    => 'checkbox',
			'name'    => 'driver_test',
			'label'   => trans('messages.driver_test_label'),
			'value'   => $siteInfo['driver_test'] ?? '0',
			'hint'    => trans('admin.mail_driver_test_hint'),
			'rules'   => $rules,
		])
	</div>
</div>

@if (array_key_exists('sendmail', $mailDrivers))
	@if (view()->exists('install.site_info.mail_drivers.sendmail'))
		@include('install.site_info.mail_drivers.sendmail')
	@endif
@endif
@if (array_key_exists('smtp', $mailDrivers))
	@if (view()->exists('install.site_info.mail_drivers.smtp'))
		@include('install.site_info.mail_drivers.smtp')
	@endif
@endif
@if (array_key_exists('mailgun', $mailDrivers))
	@if (view()->exists('install.site_info.mail_drivers.mailgun'))
		@include('install.site_info.mail_drivers.mailgun')
	@endif
@endif
@if (array_key_exists('postmark', $mailDrivers))
	@if (view()->exists('install.site_info.mail_drivers.postmark'))
		@include('install.site_info.mail_drivers.postmark')
	@endif
@endif
@if (array_key_exists('ses', $mailDrivers))
	@if (view()->exists('install.site_info.mail_drivers.ses'))
		@include('install.site_info.mail_drivers.ses')
	@endif
@endif
@if (array_key_exists('sparkpost', $mailDrivers))
	@if (view()->exists('install.site_info.mail_drivers.sparkpost'))
		@include('install.site_info.mail_drivers.sparkpost')
	@endif
@endif
@if (array_key_exists('resend', $mailDrivers))
	@if (view()->exists('install.site_info.mail_drivers.resend'))
		@include('install.site_info.mail_drivers.resend')
	@endif
@endif
@if (array_key_exists('mailersend', $mailDrivers))
	@if (view()->exists('install.site_info.mail_drivers.mailersend'))
		@include('install.site_info.mail_drivers.mailersend')
	@endif
@endif

@section('after_scripts')
	@parent
	<script>
		let mailDriversSelectors = {!! $mailDriversSelectorsJson !!};
		let mailDriversSelectorsList = Object.values(mailDriversSelectors);
		
		onDocumentReady((event) => {
			const driverElSelector = 'select[name="driver"]';
			const driverTestElSelector = 'input[name="driver_test"]';
			
			/* Driver Selection (select2) */
			let driverEl = document.querySelector(driverElSelector);
			let driverTestEl = document.querySelector(driverTestElSelector);
			getDriverFields(driverEl, driverTestEl);
			/* Vanilla JS is not used since the select2 plugin is built with jQuery */
			$(driverElSelector).on("change", (event) => {
				getDriverFields(event.target, driverTestEl);
			});
			
			/* Driver Test Checking (checkbox) */
			driverTestEl.addEventListener("change", (event) => {
				getDriverFields(driverEl, event.target);
			});
		}, true);
		
		function getDriverFields(driverEl, driverTestEl) {
			/* Hide all drivers fields */
			setElementsVisibility("hide", mailDriversSelectorsList);
			
			/* Show the selected driver fields */
			let driverElValue = driverEl.value;
			let selectedDriverFieldElSelector = mailDriversSelectors[driverElValue] ?? "";
			
			if (driverElValue === "sendmail") {
				/* Show the 'sendmail' driver fields only when the driver validation is enabled */
				/* That allows to use default sendmail parameters if validation is not required */
				if (isElDefined(driverTestEl) && driverTestEl.checked) {
					setElementsVisibility("show", selectedDriverFieldElSelector);
				}
			} else {
				setElementsVisibility("show", selectedDriverFieldElSelector);
			}
		}
	</script>
@endsection
