<?php


namespace App\Notifications;

use App\Helpers\Date;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserNotification extends Notification implements ShouldQueue
{
	use Queueable;
	
	protected $user;
	protected string $todayDateFormatted;
	protected string $todayTimeFormatted;
	
	public function __construct($user)
	{
		$this->user = $user;
		
		// Get timezone
		$tz = Date::getAppTimeZone();
		
		// Get today date & time
		$this->todayDateFormatted = Date::format(now($tz));
		$this->todayTimeFormatted = now($tz)->format('H:i');
	}
	
	public function via($notifiable)
	{
		return ['mail'];
	}
	
	public function toMail($notifiable)
	{
		return (new MailMessage)
			->subject(trans('mail.user_notification_title'))
			->greeting(trans('mail.user_notification_content_1'))
			->line(trans('mail.user_notification_content_2', ['name' => $this->user->name]))
			->line(trans('mail.user_notification_content_3', [
				'now'       => $this->todayDateFormatted,
				'time'      => $this->todayTimeFormatted,
				'authField' => $this->user->auth_field ?? '-',
				'email'     => !empty($this->user->email) ? $this->user->email : '-',
				'phone'     => !empty($this->user->phone) ? $this->user->phone : '-',
			]))
			->salutation(trans('mail.footer_salutation', ['appName' => config('app.name')]));
	}
}
