<?php


namespace App\Observers\Traits\Setting;

use App\Providers\AppService\ConfigTrait\MailConfig;

trait MailTrait
{
	use MailConfig;
	
	/**
	 * Updating
	 *
	 * @param $setting
	 * @param $original
	 * @return bool
	 */
	public function mailUpdating($setting, $original)
	{
		// Test the mail driver config
		$driverTest = $setting->value['driver_test'] ?? '0';
		$driverTest = ($driverTest == '1');
		$mailTo = $setting->value['email_always_to'] ?? null;
		
		$errorMessage = $this->testMailConfig($driverTest, $mailTo, $setting->value, true);
		if (!empty($errorMessage)) {
			notification($errorMessage, 'error');
			
			return false;
		}
	}
}
