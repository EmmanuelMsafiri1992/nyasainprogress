<?php


namespace App\Models\Setting;

/*
 * settings.social_auth.option
 */

class SocialAuthSetting
{
	public static function getValues($value, $disk)
	{
		return $value;
	}
	
	public static function setValues($value, $setting)
	{
		return $value;
	}
	
	public static function getFields($diskName)
	{
		$baseUrl = config('app.url');
		
		$facebookInfo = trans('admin.facebook_oauth_info', ['baseUrl' => $baseUrl]);
		$linkedinInfo = trans('admin.linkedin_oauth_info', ['baseUrl' => $baseUrl]);
		$twitterOauth2Info = trans('admin.twitter_oauth_2_info', ['baseUrl' => $baseUrl]);
		$twitterOauth1Info = trans('admin.twitter_oauth_1_info', ['baseUrl' => $baseUrl]);
		$googleInfo = trans('admin.google_oauth_info', ['baseUrl' => $baseUrl]);
		
		if (config('plugins.domainmapping.installed')) {
			$facebookInfo .= trans('admin.facebook_oauth_domainmapping');
			$linkedinInfo .= trans('admin.linkedin_oauth_domainmapping');
			$twitterOauth2Info .= trans('admin.twitter_oauth_2_domainmapping');
			$twitterOauth1Info .= trans('admin.twitter_oauth_1_domainmapping');
			$googleInfo .= trans('admin.google_oauth_domainmapping');
		}
		
		$twitterOauth2Info .= trans('admin.twitter_oauth_2_note');
		$twitterOauth1Info .= trans('admin.twitter_oauth_1_note');
		
		$facebookInfo = trans('admin.card_light_inverse', ['content' => $facebookInfo]);
		$linkedinInfo = trans('admin.card_light_inverse', ['content' => $linkedinInfo]);
		$twitterOauth2Info = trans('admin.card_light_inverse', ['content' => $twitterOauth2Info]);
		$twitterOauth1Info = trans('admin.card_light_inverse', ['content' => $twitterOauth1Info]);
		$googleInfo = trans('admin.card_light_inverse', ['content' => $googleInfo]);
		
		$fields = [
			[
				'name'  => 'social_login_activation',
				'label' => trans('admin.social_login_activation_label'),
				'type'  => 'checkbox_switch',
				'hint'  => trans('admin.social_login_activation_hint'),
			],
			
			// facebook
			[
				'name'  => 'facebook_title',
				'type'  => 'custom_html',
				'value' => trans('admin.facebook_title'),
			],
			[
				'name'  => 'facebook_oauth_info',
				'type'  => 'custom_html',
				'value' => $facebookInfo,
			],
			[
				'name'              => 'facebook_client_id',
				'label'             => trans('admin.facebook_client_id_label'),
				'type'              => 'text',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'facebook_client_secret',
				'label'             => trans('admin.facebook_client_secret_label'),
				'type'              => 'text',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			
			// linkedin
			[
				'name'  => 'linkedin_title',
				'type'  => 'custom_html',
				'value' => trans('admin.linkedin_title'),
			],
			[
				'name'  => 'linkedin_oauth_info',
				'type'  => 'custom_html',
				'value' => $linkedinInfo,
			],
			[
				'name'              => 'linkedin_client_id',
				'label'             => trans('admin.linkedin_client_id_label'),
				'type'              => 'text',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'linkedin_client_secret',
				'label'             => trans('admin.linkedin_client_secret_label'),
				'type'              => 'text',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			
			// twitter (OAuth 2.0)
			[
				'name'  => 'twitter_oauth_2_title',
				'type'  => 'custom_html',
				'value' => trans('admin.twitter_oauth_2_title'),
			],
			[
				'name'  => 'twitter_oauth_2_info',
				'type'  => 'custom_html',
				'value' => $twitterOauth2Info,
			],
			[
				'name'              => 'twitter_oauth_2_client_id',
				'label'             => trans('admin.twitter_oauth_2_client_id_label'),
				'type'              => 'text',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'twitter_oauth_2_client_secret',
				'label'             => trans('admin.twitter_oauth_2_client_secret_label'),
				'type'              => 'text',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			
			// twitter (OAuth 1.0)
			[
				'name'  => 'twitter_oauth_1_title',
				'type'  => 'custom_html',
				'value' => trans('admin.twitter_oauth_1_title'),
			],
			[
				'name'  => 'twitter_oauth_1_info',
				'type'  => 'custom_html',
				'value' => $twitterOauth1Info,
			],
			[
				'name'              => 'twitter_client_id',
				'label'             => trans('admin.twitter_client_id_label'),
				'type'              => 'text',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'twitter_client_secret',
				'label'             => trans('admin.twitter_client_secret_label'),
				'type'              => 'text',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			
			// google
			[
				'name'  => 'google_title',
				'type'  => 'custom_html',
				'value' => trans('admin.google_title'),
			],
			[
				'name'  => 'google_oauth_info',
				'type'  => 'custom_html',
				'value' => $googleInfo,
			],
			[
				'name'              => 'google_client_id',
				'label'             => trans('admin.google_client_id_label'),
				'type'              => 'text',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			[
				'name'              => 'google_client_secret',
				'label'             => trans('admin.google_client_secret_label'),
				'type'              => 'text',
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
		];
		
		return $fields;
	}
}
