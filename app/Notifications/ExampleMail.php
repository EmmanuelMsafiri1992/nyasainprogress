<?php


namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/*
 * Note: Implementing "Illuminate\Contracts\Queue\ShouldQueue"
 * allows Laravel to save mail sending as Queue in the database
 */

class ExampleMail extends Notification
{
	use Queueable;
	
	public function __construct()
	{
	}
	
	public function via(object $notifiable): array
	{
		if (isDemoDomain()) {
			return [];
		}
		
		return ['mail'];
	}
	
	public function toMail(object $notifiable): MailMessage
	{
		return (new MailMessage)
			->subject(trans('mail.email_example_title', ['appName' => config('app.name')]))
			->greeting(trans('mail.email_example_content_1'))
			->line(trans('mail.email_example_content_2', ['appName' => config('app.name')]))
			->salutation(trans('mail.footer_salutation', ['appName' => config('app.name')]));
	}
}
