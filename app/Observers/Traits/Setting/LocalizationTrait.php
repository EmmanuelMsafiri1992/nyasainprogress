<?php


namespace App\Observers\Traits\Setting;

use App\Providers\AppService\ConfigTrait\LocalizationConfig;

trait LocalizationTrait
{
	use LocalizationConfig;
	
	/**
	 * Updating
	 *
	 * @param $setting
	 * @param $original
	 * @return false|void
	 */
	public function localizationUpdating($setting, $original)
	{
		$geolocationActive = $setting->value['geoip_activation'] ?? false;
		if ($geolocationActive) {
			if (!empty($setting->value['default_country_code'])) {
				$message = trans('admin.activating_geolocation_validator');
				notification($message, 'error');
				
				return false;
			}
		} else {
			if (empty($setting->value['default_country_code'])) {
				$message = trans('admin.disabling_geolocation_validator');
				notification($message, 'warning');
			}
		}
		
		// Test the GeoIP driver config
		$geoipDriverTest = $setting->value['geoip_driver_test'] ?? '0';
		$geoipDriverTest = ($geoipDriverTest == '1');
		
		$errorMessage = $this->testGeoIPConfig($geoipDriverTest, $setting->value);
		if (!empty($errorMessage)) {
			notification($errorMessage, 'error');
			
			return false;
		}
	}
	
	/**
	 * Saved
	 *
	 * @param $setting
	 */
	public function localizationSaved($setting)
	{
		$this->saveTheDefaultCountryCodeInSession($setting);
	}
	
	/**
	 * If the Default Country is changed,
	 * Then clear the 'country_code' from the sessions,
	 * And save the new value in session.
	 *
	 * @param $setting
	 */
	private function saveTheDefaultCountryCodeInSession($setting): void
	{
		if (isset($setting->value['default_country_code'])) {
			session()->forget('countryCode');
			session()->put('countryCode', $setting->value['default_country_code']);
		}
	}
}
