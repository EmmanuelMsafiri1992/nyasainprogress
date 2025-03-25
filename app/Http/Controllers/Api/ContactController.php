<?php
/*
 * JobClass - Job Board Web Application
 * Copyright (c) BeDigit. All Rights Reserved
 *
 * Website: https://laraclassifier.com/jobclass
 * Author: BeDigit | https://bedigit.com
 *
 * LICENSE
 * -------
 * This software is furnished under a license and may be used and copied
 * only in accordance with the terms of such license and with the inclusion
 * of the above copyright notice. If you Purchased from CodeCanyon,
 * Please read the full License from here - https://codecanyon.net/licenses/standard
 */

namespace App\Http\Controllers\Api;

use App\Helpers\Arr;
use App\Http\Requests\Front\ContactRequest;
use App\Http\Requests\Front\ReportRequest;
use App\Http\Requests\Front\SendPostByEmailRequest;
use App\Http\Resources\PostResource;
use App\Models\Permission;
use App\Models\Post;
use App\Models\User;
use App\Notifications\FormSent;
use App\Notifications\PostSentByEmail;
use App\Notifications\ReportSent;
use Illuminate\Support\Facades\Notification;

/**
 * @group Contact
 */
class ContactController extends BaseController
{
	/**
	 * Send Form
	 *
	 * Send a message to the site owner.
	 *
	 * @bodyParam country_code string required The user's country code. Example: US
	 * @bodyParam country_name string required The user's country name. Example: United Sates
	 * @bodyParam first_name string required The user's first name. Example: John
	 * @bodyParam last_name string required The user's last name. Example: Doe
	 * @bodyParam email string required The user's email address. Example: john.doe@domain.tld
	 * @bodyParam message string required The message to send. Example: Nesciunt porro possimus maiores voluptatibus accusamus velit qui aspernatur.
	 * @bodyParam captcha_key string Key generated by the CAPTCHA endpoint calling (Required when the CAPTCHA verification is enabled from the Admin panel).
	 *
	 * @param \App\Http\Requests\Front\ContactRequest $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function sendForm(ContactRequest $request): \Illuminate\Http\JsonResponse
	{
		// Store Contact Input
		$contactForm = $request->all();
		$contactForm = Arr::toObject($contactForm);
		
		// Send Contact Email
		try {
			if (config('settings.app.email')) {
				Notification::route('mail', config('settings.app.email'))->notify(new FormSent($contactForm));
			} else {
				$admins = User::permission(Permission::getStaffPermissions())->get();
				if ($admins->count() > 0) {
					Notification::send($admins, new FormSent($contactForm));
				}
			}
			
			$data = [
				'success' => true,
				'message' => t('message_sent_to_moderators_thanks'),
				'result'  => $contactForm,
			];
			
			return apiResponse()->json($data);
		} catch (\Throwable $e) {
			return apiResponse()->error($e->getMessage());
		}
	}
	
	/**
	 * Report post
	 *
	 * Report abuse or issues
	 *
	 * @bodyParam report_type_id int required The report type ID. Example: 2
	 * @bodyParam email string required The user's email address. Example: john.doe@domain.tld
	 * @bodyParam message string required The message to send. Example: Et sunt voluptatibus ducimus id assumenda sint.
	 * @bodyParam captcha_key string Key generated by the CAPTCHA endpoint calling (Required when the CAPTCHA verification is enabled from the Admin panel).
	 *
	 * @urlParam id int required The post ID.
	 *
	 * @param $postId
	 * @param ReportRequest $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function sendReport($postId, ReportRequest $request): \Illuminate\Http\JsonResponse
	{
		// Get Post
		$post = Post::findOrFail($postId);
		
		// Store Report Input
		$report = $request->all();
		$report = Arr::toObject($report);
		
		// Send Abuse Report to admin
		try {
			if (config('settings.app.email')) {
				Notification::route('mail', config('settings.app.email'))->notify(new ReportSent($post, $report));
			} else {
				$admins = User::permission(Permission::getStaffPermissions())->get();
				if ($admins->count() > 0) {
					Notification::send($admins, new ReportSent($post, $report));
				}
			}
			
			$data = [
				'success' => true,
				'message' => t('report_has_sent_successfully_to_us'),
				'result'  => $report,
				'extra'   => [
					'post' => (new PostResource($post))->toArray($request),
				],
			];
			
			return apiResponse()->json($data);
		} catch (\Throwable $e) {
			return apiResponse()->error($e->getMessage());
		}
	}
	
	/**
	 * Send Post by Email
	 *
	 * @bodyParam sender_email string required The sender's email address. Example: john.doe@domain.tld
	 * @bodyParam recipient_email string required The recipient's email address. Example: foo@domain.tld
	 *
	 * @param $postId
	 * @param \App\Http\Requests\Front\SendPostByEmailRequest $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function sendPostByEmail($postId, SendPostByEmailRequest $request): \Illuminate\Http\JsonResponse
	{
		// Get Post
		$post = Post::findOrFail($postId);
		
		// Store Data Input
		$mailData = [
			'sender_email'    => $request->input('sender_email'),
			'recipient_email' => $request->input('recipient_email'),
			'message'         => $request->input('message'),
		];
		$mailData = Arr::toObject($mailData);
		
		// Send the Post by email
		try {
			Notification::route('mail', $mailData->recipient_email)->notify(new PostSentByEmail($post, $mailData));
			
			$data = [
				'success' => true,
				'message' => t('Your message has sent successfully'),
				'result'  => $mailData,
				'extra'   => [
					'post' => (new PostResource($post))->toArray($request),
				],
			];
			
			return apiResponse()->json($data);
		} catch (\Throwable $e) {
			return apiResponse()->error($e->getMessage());
		}
	}
}
