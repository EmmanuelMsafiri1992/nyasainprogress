<?php


namespace App\Providers\AppService\ConfigTrait;

use App\Models\Permission;
use App\Models\User;
use App\Notifications\ExampleMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

trait MailConfig
{
	private function updateMailConfig(?array $settings = [], ?string $appName = null): void
	{
		if (empty($settings)) {
			return;
		}
		
		// Mail
		$driver = $settings['driver'] ?? null;
		$driver = env('MAIL_DRIVER', $driver);
		$driver = env('MAIL_MAILER', $driver);
		$fromName = config('settings.app.name', $appName ?? 'Site Name');
		
		config()->set('mail.default', $driver);
		config()->set('mail.from.name', env('MAIL_FROM_NAME', $fromName));
		
		// Default Mail Sender (from Installer)
		$mailSender = $settings['email'] ?? null;
		
		// SMTP
		if ($driver == 'smtp') {
			$host = $settings['smtp_host'] ?? null;
			$port = $settings['smtp_port'] ?? null;
			$encryption = $settings['smtp_encryption'] ?? null;
			$username = $settings['smtp_username'] ?? null;
			$password = $settings['smtp_password'] ?? null;
			$address = $settings['smtp_email_sender'] ?? ($mailSender ?? null);
			
			config()->set('mail.mailers.smtp.host', env('MAIL_HOST', $host));
			config()->set('mail.mailers.smtp.port', env('MAIL_PORT', $port));
			config()->set('mail.mailers.smtp.encryption', env('MAIL_ENCRYPTION', $encryption));
			config()->set('mail.mailers.smtp.username', env('MAIL_USERNAME', $username));
			config()->set('mail.mailers.smtp.password', env('MAIL_PASSWORD', $password));
			config()->set('mail.from.address', env('MAIL_FROM_ADDRESS', $address));
		}
		
		// Sendmail
		if ($driver == 'sendmail') {
			$path = $settings['sendmail_path'] ?? null;
			$address = $settings['sendmail_email_sender'] ?? ($mailSender ?? null);
			
			config()->set('mail.mailers.sendmail.path', env('MAIL_SENDMAIL', $path));
			config()->set('mail.from.address', env('MAIL_FROM_ADDRESS', $address));
		}
		
		// Mailgun
		if ($driver == 'mailgun') {
			$domain = $settings['mailgun_domain'] ?? null;
			$secret = $settings['mailgun_secret'] ?? null;
			$endpoint = $settings['mailgun_endpoint'] ?? ('api.mailgun.net' ?? null);
			$host = $settings['mailgun_host'] ?? null;
			$port = $settings['mailgun_port'] ?? null;
			$encryption = $settings['mailgun_encryption'] ?? null;
			$username = $settings['mailgun_username'] ?? null;
			$password = $settings['mailgun_password'] ?? null;
			$address = $settings['mailgun_email_sender'] ?? ($mailSender ?? null);
			
			config()->set('services.mailgun.domain', env('MAILGUN_DOMAIN', $domain));
			config()->set('services.mailgun.secret', env('MAILGUN_SECRET', $secret));
			config()->set('services.mailgun.endpoint', env('MAILGUN_ENDPOINT', $endpoint));
			config()->set('mail.mailers.smtp.host', env('MAIL_HOST', $host));
			config()->set('mail.mailers.smtp.port', env('MAIL_PORT', $port));
			config()->set('mail.mailers.smtp.encryption', env('MAIL_ENCRYPTION', $encryption));
			config()->set('mail.mailers.smtp.username', env('MAIL_USERNAME', $username));
			config()->set('mail.mailers.smtp.password', env('MAIL_PASSWORD', $password));
			config()->set('mail.from.address', env('MAIL_FROM_ADDRESS', $address));
		}
		
		// Postmark
		if ($driver == 'postmark') {
			$token = $settings['postmark_token'] ?? null;
			$host = $settings['postmark_host'] ?? null;
			$port = $settings['postmark_port'] ?? null;
			$encryption = $settings['postmark_encryption'] ?? null;
			$username = $settings['postmark_username'] ?? null;
			$password = $settings['postmark_password'] ?? null;
			$address = $settings['postmark_email_sender'] ?? ($mailSender ?? null);
			
			config()->set('services.postmark.token', env('POSTMARK_TOKEN', $token));
			config()->set('mail.mailers.smtp.host', env('MAIL_HOST', $host));
			config()->set('mail.mailers.smtp.port', env('MAIL_PORT', $port));
			config()->set('mail.mailers.smtp.encryption', env('MAIL_ENCRYPTION', $encryption));
			config()->set('mail.mailers.smtp.username', env('MAIL_USERNAME', $username));
			config()->set('mail.mailers.smtp.password', env('MAIL_PASSWORD', $password));
			config()->set('mail.from.address', env('MAIL_FROM_ADDRESS', $address));
		}
		
		// Amazon SES
		if ($driver == 'ses') {
			$key = $settings['ses_key'] ?? null;
			$secret = $settings['ses_secret'] ?? null;
			$region = $settings['ses_region'] ?? null;
			$token = $settings['ses_token'] ?? null;
			$host = $settings['ses_host'] ?? null;
			$port = $settings['ses_port'] ?? null;
			$encryption = $settings['ses_encryption'] ?? null;
			$username = $settings['ses_username'] ?? null;
			$password = $settings['ses_password'] ?? null;
			$address = $settings['ses_email_sender'] ?? ($mailSender ?? null);
			
			config()->set('services.ses.key', env('SES_KEY', $key));
			config()->set('services.ses.secret', env('SES_SECRET', $secret));
			config()->set('services.ses.region', env('SES_REGION', $region));
			config()->set('services.ses.token', env('SES_SESSION_TOKEN', $token));
			config()->set('mail.mailers.smtp.host', env('MAIL_HOST', $host));
			config()->set('mail.mailers.smtp.port', env('MAIL_PORT', $port));
			config()->set('mail.mailers.smtp.encryption', env('MAIL_ENCRYPTION', $encryption));
			config()->set('mail.mailers.smtp.username', env('MAIL_USERNAME', $username));
			config()->set('mail.mailers.smtp.password', env('MAIL_PASSWORD', $password));
			config()->set('mail.from.address', env('MAIL_FROM_ADDRESS', $address));
		}
		
		// Sparkpost
		if ($driver == 'sparkpost') {
			$secret = $settings['sparkpost_secret'] ?? null;
			$host = $settings['sparkpost_host'] ?? null;
			$port = $settings['sparkpost_port'] ?? null;
			$encryption = $settings['sparkpost_encryption'] ?? null;
			$username = $settings['sparkpost_username'] ?? null;
			$password = $settings['sparkpost_password'] ?? null;
			$address = $settings['sparkpost_email_sender'] ?? ($mailSender ?? null);
			
			config()->set('services.sparkpost.secret', env('SPARKPOST_SECRET', $secret));
			config()->set('mail.mailers.smtp.host', env('MAIL_HOST', $host));
			config()->set('mail.mailers.smtp.port', env('MAIL_PORT', $port));
			config()->set('mail.mailers.smtp.encryption', env('MAIL_ENCRYPTION', $encryption));
			config()->set('mail.mailers.smtp.username', env('MAIL_USERNAME', $username));
			config()->set('mail.mailers.smtp.password', env('MAIL_PASSWORD', $password));
			config()->set('mail.from.address', env('MAIL_FROM_ADDRESS', $address));
		}
		
		// Resend
		if ($driver == 'resend') {
			$apiKey = $settings['resend_api_key'] ?? null;
			$address = $settings['resend_email_sender'] ?? ($mailSender ?? null);
			
			config()->set('services.resend.key', env('RESEND_API_KEY', $apiKey));
			config()->set('mail.from.address', env('MAIL_FROM_ADDRESS', $address));
		}
		
		// MailerSend
		if ($driver == 'mailersend') {
			$apiKey = $settings['mailersend_api_key'] ?? null;
			$apiKey = env('MAILERSEND_API_KEY', $apiKey);
			$address = $settings['mailersend_email_sender'] ?? ($mailSender ?? null);
			
			config()->set('services.mailersend.api_key', $apiKey);
			config()->set('mailersend-driver.api_key', $apiKey);
			config()->set('mail.from.address', env('MAIL_FROM_ADDRESS', $address));
			
			/*
			$host = $settings['mailersend_host'] ?? 'api.mailersend.com';
			$protocol = $settings['mailersend_protocol'] ?? 'https';
			$apiPath = $settings['mailersend_api_path'] ?? 'v1';
			
			config()->set('mailersend-driver.host', env('MAILERSEND_API_HOST', $host));
			config()->set('mailersend-driver.protocol', env('MAILERSEND_API_PROTO', $protocol));
			config()->set('mailersend-driver.api_path', env('MAILERSEND_API_PATH', $apiPath));
			*/
		}
	}
	
	/**
	 * @param bool $isTestEnabled
	 * @param string|null $mailTo
	 * @param array|null $settings
	 * @param bool $fallbackMailToAdminUsers
	 * @return string|null
	 */
	private function testMailConfig(bool $isTestEnabled, ?string $mailTo, ?array $settings = [], bool $fallbackMailToAdminUsers = false): ?string
	{
		if (!$isTestEnabled) {
			return null;
		}
		
		// Apply updated config
		$this->updateMailConfig($settings);
		
		// Get the test recipient
		$mailTo = !empty($mailTo) ? $mailTo : config('settings.app.email');
		
		/*
		 * Send Example Email
		 *
		 * With the sendmail driver, in local environment,
		 * this test email cannot be found if you have not familiar with the sendmail configuration
		 */
		try {
			if (!empty($mailTo)) {
				Notification::route('mail', $mailTo)->notify(new ExampleMail());
			} else {
				if ($fallbackMailToAdminUsers) {
					$admins = User::permission(Permission::getStaffPermissions())->get();
					if ($admins->count() > 0) {
						Notification::send($admins, new ExampleMail());
					}
				} else {
					return 'No email address defined to receive the test email.';
				}
			}
		} catch (\Throwable $e) {
			$message = $e->getMessage();
			if (empty($message)) {
				$message = 'Error in the mail sending parameters.';
				$message .= ' Please contact your mail sending server\'s provider for more information.';
			}
			
			return $message;
		}
		
		return null;
	}
	
	/**
	 * Send Mails Always To
	 *
	 * @return void
	 */
	private function setupMailsAlwaysTo(): void
	{
		if (request()->isMethod('put') && request()->has('email_always_to')) {
			$isDriverTestEnabled = (request()->input('driver_test', '0') == '1');
			$emailAlwaysTo = request()->input('email_always_to');
		} else {
			$isDriverTestEnabled = (config('settings.mail.driver_test', '0') == '1');
			$emailAlwaysTo = config('settings.mail.email_always_to');
		}
		
		$isAlwaysToEnabled = ($isDriverTestEnabled && !empty($emailAlwaysTo) && isValidEmail($emailAlwaysTo));
		if ($isAlwaysToEnabled) {
			Mail::alwaysTo($emailAlwaysTo);
		}
	}
	
	/**
	 * Get un-selected mail drivers parameters to avoid to store them in session
	 *
	 * @param array|null $mailDriversRules
	 * @param string|null $mailDriver
	 * @return array
	 */
	private function getUnSelectedMailDriversParameters(?array $mailDriversRules, ?string $mailDriver = null): array
	{
		$exceptInput = [];
		$mailUnnecessaryInput = $mailDriversRules;
		if (isset($mailUnnecessaryInput[$mailDriver])) {
			unset($mailUnnecessaryInput[$mailDriver]);
		}
		if (!empty($mailUnnecessaryInput)) {
			foreach ($mailUnnecessaryInput as $iRules) {
				$exceptInput = array_merge($exceptInput, array_keys($iRules));
			}
		}
		
		return $exceptInput;
	}
}
