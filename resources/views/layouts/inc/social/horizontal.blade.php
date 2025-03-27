@php
	$socialSharesAreEnabled = (
			config('settings.social_share.facebook')
			|| config('settings.social_share.twitter')
			|| config('settings.social_share.linkedin')
			|| config('settings.social_share.whatsapp')
			|| config('settings.social_share.telegram')
			|| config('settings.social_share.snapchat')
			|| (config('settings.social_share.messenger') && config('settings.social_share.facebook_app_id'))
			|| config('settings.social_share.pinterest')
			|| config('settings.social_share.vk')
			|| config('settings.social_share.tumblr')
		);
@endphp
@if ($socialSharesAreEnabled)
	<div class="social-media social-share text-center mt-4 mb-4 ms-0 me-0">
		<span class="text-secondary text-opacity-25" data-bs-toggle="tooltip" title="{{ t('share_on_social_media') }}">
			<i class="fa-solid fa-share"></i>
		</span>
		@if (config('settings.social_share.facebook'))
			<a class="facebook" title="{{ t('share_on', ['media' => 'Facebook']) }}">
				<i class="fa-brands fa-square-facebook"></i>
			</a>
		@endif
		@if (config('settings.social_share.twitter'))
			<a class="x-twitter" title="{{ t('share_on', ['media' => 'X (Twitter)']) }}">
				<i class="fa-brands fa-square-x-twitter"></i>
			</a>
		@endif
		@if (config('settings.social_share.linkedin'))
			<a class="linkedin" title="{{ t('share_on', ['media' => 'LinkedIn']) }}">
				<i class="fa-brands fa-linkedin"></i>
			</a>
		@endif
		@if (config('settings.social_share.whatsapp'))
			<a class="whatsapp" title="{{ t('share_on', ['media' => 'WhatsApp']) }}">
				<i class="fa-brands fa-square-whatsapp"></i>
			</a>
		@endif
		@if (config('settings.social_share.telegram'))
			<a class="telegram" title="{{ t('share_on', ['media' => 'Telegram']) }}">
				<i class="fa-brands fa-telegram"></i>
			</a>
		@endif
		@if (config('settings.social_share.snapchat'))
			<a class="snapchat" title="{{ t('share_on', ['media' => 'Snapchat']) }}">
				<i class="fa-brands fa-square-snapchat"></i>
			</a>
		@endif
		@if (config('settings.social_share.messenger') && config('settings.social_share.facebook_app_id'))
			<a class="messenger"
			   data-fb-app-id="{{ config('settings.social_share.facebook_app_id') }}"
			   title="{{ t('share_on', ['media' => 'Facebook Messenger']) }}"
			>
				<i class="fa-brands fa-facebook-messenger"></i>
			</a>
		@endif
		@if (config('settings.social_share.pinterest'))
			<a class="pinterest" title="{{ t('share_on', ['media' => 'Pinterest']) }}">
				<i class="fa-brands fa-square-pinterest"></i>
			</a>
		@endif
		@if (config('settings.social_share.vk'))
			<a class="vk" title="{{ t('share_on', ['media' => 'VK (VKontakte)']) }}">
				<i class="fa-brands fa-vk"></i>
			</a>
		@endif
		@if (config('settings.social_share.tumblr'))
			<a class="tumblr" title="{{ t('share_on', ['media' => 'Tumblr']) }}">
				<i class="fa-brands fa-square-tumblr"></i>
			</a>
		@endif
	</div>
@endif
