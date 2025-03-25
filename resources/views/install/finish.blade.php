@extends('install.layouts.master')
@section('title', trans('messages.finish_title'))

@php
	$itemName = config('larapen.core.item.name');
	$itemTitle = config('larapen.core.item.title');
	$itemUrl = config('larapen.core.item.url');
	$itemLinkLabel = str($itemUrl)->remove('https://')->rtrim('/')->toString();
	
	$adminLoginUrl = admin_url('login');
	$homePageUrl = url('/');
	
	$supportUrl = 'https://support.laraclassifier.com/';
@endphp
@section('content')
	
	<h3 class="title-3 text-success">
		<i class="fa-regular fa-circle-check"></i> {!! trans('messages.finish_success', ['itemName' => $itemName, 'itemTitle' => $itemTitle]) !!}
	</h3>
	<div class="row">
		<div class="col-md-12">
			
			<ul class="list list-check mt-4">
				<li>
					{!! trans('messages.finish_env_file_hint') !!}
				</li>
				<li>
					{!! trans('messages.finish_site_hint', ['adminLoginUrl' => $adminLoginUrl, 'homePageUrl' => $homePageUrl]) !!}
				</li>
				<li>
					{!! trans('messages.finish_help_hint', ['supportUrl' => $supportUrl]) !!}
				</li>
			</ul>
			<p class="mt-4">
				{!! trans('messages.finish_thanks', ['itemName' => $itemName, 'itemUrl' => $itemUrl, 'itemLinkLabel' => $itemLinkLabel]) !!}
			</p>
			
		</div>
	</div>

@endsection

@section('after_scripts')
@endsection
