<?php


namespace App\Notifications;

use App\Helpers\Arr;
use App\Helpers\UrlGen;
use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class PostSentByEmail extends Notification implements ShouldQueue
{
	use Queueable;
	
	protected Post $post;
	protected array $mailData;
	
	public function __construct(Post $post, $mailData)
	{
		$this->post = $post;
		$this->mailData = is_array($mailData) ? $mailData : [];
	}
	
	public function via($notifiable)
	{
		if (isDemoDomain()) {
			return [];
		}
		
		return ['mail'];
	}
	
	public function toMail($notifiable)
	{
		$postUrl = UrlGen::post($this->post);
		$senderEmail = data_get($this->mailData, 'sender_email');
		
		return (new MailMessage)
			->replyTo($senderEmail, $senderEmail)
			->subject(trans('mail.post_sent_by_email_title', [
				'appName'     => config('app.name'),
				'countryCode' => $this->post->country_code
			]))
			->line(trans('mail.post_sent_by_email_content_1', ['senderEmail' => $senderEmail]))
			->line(trans('mail.post_sent_by_email_content_2'))
			->line(trans('mail.Job URL') . ':  <a href="' . $postUrl . '">' . $postUrl . '</a>')
			->salutation(trans('mail.footer_salutation', ['appName' => config('app.name')]));
	}
}
