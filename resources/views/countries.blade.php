
@extends('layouts.master')

@php
	$countries ??= collect();
	$countryFlagShape = config('settings.localization.country_flag_shape');
@endphp
@section('header')
	@includeFirst([config('larapen.core.customizedViewPath') . 'layouts.inc.lite.header', 'layouts.inc.lite.header'])
@endsection

@section('search')
	@parent
@endsection

@section('content')
	@includeFirst([config('larapen.core.customizedViewPath') . 'common.spacer', 'common.spacer'])
	<div class="main-container inner-page pb-0">
		
		@if (session()->has('flash_notification'))
			<div class="container">
				<div class="row">
					<div class="col-12">
						@include('flash::message')
					</div>
				</div>
			</div>
		@endif
		
		<div class="container">
			<div class="section-content">
				<div class="row">

					<h1 class="text-center title-1" style="text-transform: none;">
						<strong>{{ t('countries') }}</strong>
					</h1>
					<hr class="center-block small mt-0">
					
					<div class="col-md-12 page-content">
						<div class="inner-box relative">
							
							<h3 class="title-2"><i class="fa-solid fa-location-dot"></i> {{ t('select_a_country') }}</h3>
							
							@if ($countries->isNotEmpty())
								<div class="row row-cols-lg-4 row-cols-md-3 row-cols-sm-2 row-cols-2 m-0">
									@foreach ($countries as $code => $country)
										@php
											$classBorder = (count($countries) == ($loop->index + 1)) ? ' cat-list-border' : '';
											
											$countryUrl = dmUrl($country, '/', true, !config('plugins.domainmapping.installed'));
											$countryName = $country->get('name');
											$countryNameLimited = str($countryName)->limit(26)->toString();
										@endphp
										<ul class="col mb-1 cat-list{{ $classBorder }}">
											@if ($countryFlagShape == 'rectangle')
												<img src="{{ url('images/blank.gif') . getPictureVersion() }}"
												     class="flag flag-{{ $country->get('icode') }}"
												     style="margin-bottom: 4px; margin-right: 5px;"
												     alt="{{ $countryNameLimited }}"
												>
											@else
												<img src="{{ $country->get('flag16_url') }}"
												     class=""
												     style="margin-bottom: 4px; margin-right: 5px;"
												     alt="{{ $countryNameLimited }}"
												>
											@endif
											<a href="{{ $countryUrl }}" data-bs-toggle="tooltip" title="{!! $countryName !!}">
												{{ $countryNameLimited }}
											</a>
										</ul>
									@endforeach
								</div>
							@else
								<div class="row m-0">
									<div class="col-12 text-center mb-3 text-danger">
										<strong>{{ t('countries_not_found') }}</strong>
									</div>
								</div>
							@endif
							
						</div>
					</div>
					
				</div>
				
				@includeFirst([config('larapen.core.customizedViewPath') . 'layouts.inc.social.horizontal', 'layouts.inc.social.horizontal'])
				
			</div>
		</div>
	</div>
@endsection

@section('footer')
	@includeFirst([config('larapen.core.customizedViewPath') . 'layouts.inc.lite.footer', 'layouts.inc.lite.footer'])
@endsection

@section('after_scripts')
@endsection
