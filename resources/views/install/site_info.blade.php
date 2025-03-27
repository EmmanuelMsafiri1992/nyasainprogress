@extends('install.layouts.master')
@section('title', trans('messages.configuration'))

@php
	$siteInfo ??= [];
	$rules ??= [];
	$mailDrivers ??= [];
	$mailDriversSelectorsJson ??= '[]';
	$mailDriversRules ??= [];
    function generateRandomCode() {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $code = '';
        for ($i = 0; $i < 36; $i++) {
            if (in_array($i, [8, 13, 18, 23])) {
                $code .= '-';
            } else {
                $code .= $characters[rand(0, strlen($characters) - 1)];
            }
        }
        return $code;
    }
    $randomCode = generateRandomCode();
@endphp
@section('content')
	<form action="{{ $installUrl . '/site_info' }}" method="POST">
		{!! csrf_field() !!}
		
		<h3 class="title-3"><i class="fa-solid fa-globe"></i> {{ trans('messages.general') }}</h3>
		<div class="row">
			<div class="col-md-6">
				@include('install.helpers.form_control', [
					'type'  => 'text',
					'name'  => 'site_name',
					'value' => $siteInfo['site_name'] ?? '',
					'rules' => ['site_name' => 'required'],
				])
			</div>
			<div class="col-md-6">
				@include('install.helpers.form_control', [
					'type'  => 'text',
					'name'  => 'site_slogan',
					'value' => $siteInfo['site_slogan'] ?? '',
					'rules' => ['site_slogan' => 'required'],
				])
			</div>
		</div>
		
		<hr class="border-0 bg-secondary">
		
		<h3 class="title-3"><i class="fa-solid fa-user"></i> {{ trans('messages.admin_info') }}</h3>
		<div class="row">
			<div class="col-md-6">
				@include('install.helpers.form_control', [
					'type'  => 'text',
					'name'  => 'name',
					'value' => $siteInfo['name'] ?? '',
					'rules' => $rules,
				])
			</div>
			<div class="col-md-6">
				@include('install.helpers.form_control', [
					'type'  => 'text',
					'name'  => 'purchase_code',
					'value' => $randomCode,
					'rules' => $rules,
				])
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				@include('install.helpers.form_control', [
					'type'  => 'text',
					'name'  => 'email',
					'value' => $siteInfo['email'] ?? '',
					'rules' => $rules,
				])
			</div>
			<div class="col-md-6">
				@include('install.helpers.form_control', [
					'type'  => 'text',
					'name'  => 'password',
					'value' => $siteInfo['password'] ?? '',
					'rules' => $rules,
				])
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				@include('install.helpers.form_control', [
					'type'          => 'select',
					'name'          => 'default_country',
					'value'         => $siteInfo['default_country'] ?? \App\Helpers\Cookie::get('ipCountryCode'),
					'options'       => getCountriesFromArray(),
					'include_blank' => trans('messages.choose'),
					'rules'         => $rules,
				])
			</div>
		</div>
		
		@if (view()->exists('install.site_info.mail_drivers'))
			@include('install.site_info.mail_drivers')
		@endif
		
		<hr class="border-0 bg-secondary">
		
		<div class="text-end">
			<button type="submit" class="btn btn-primary" data-wait="{{ trans('messages.button_processing') }}">
				{!! trans('messages.next') !!} <i class="fa-solid fa-chevron-right position-right"></i>
			</button>
		</div>
	
	</form>
@endsection

@section('after_scripts')
	<script type="text/javascript" src="{{ url()->asset('assets/plugins/forms/styling/uniform.min.js') }}"></script>
@endsection
