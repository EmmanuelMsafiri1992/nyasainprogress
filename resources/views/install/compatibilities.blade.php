@extends('install.layouts.master')

@section('title', trans('messages.requirement'))

@section('content')
	
	@if (!$checkComponents)
		<h3 class="title-3"><i class="fa-solid fa-list"></i> {{ trans('messages.requirements') }}</h3>
		<div class="row">
			<div class="col-md-12">
				<ul class="installation">
					@foreach ($components as $key => $item)
						@continue($item['isOk'])
						<li>
							@if ($item['isOk'])
								<i class="fa-solid fa-check text-success"></i>
							@else
								<i class="fa-solid fa-xmark text-danger"></i>
							@endif
							<h5 class="title-5 fw-bold">
								{{ $item['name'] }}
							</h5>
							<p>
								{!! ($item['isOk']) ? $item['success'] : $item['warning'] !!}
							</p>
						</li>
					@endforeach
				</ul>
			</div>
		</div>
	@endif
	
	<h3 class="title-3"><i class="fa-regular fa-folder"></i> {{ trans('messages.permissions') }}</h3>
	<div class="row">
		<div class="col-md-12">
			<ul class="installation">
				@foreach ($permissions as $key => $item)
					<li>
						@if ($item['isOk'])
							<i class="fa-solid fa-check text-success"></i>
						@else
							<i class="fa-solid fa-xmark text-danger"></i>
						@endif
						<h5 class="title-5 fw-bold">
							{{ $item['name'] }}
						</h5>
						<p>
							{!! ($item['isOk']) ? $item['success'] : $item['warning'] !!}
						</p>
					</li>
				@endforeach
			</ul>
		</div>
	</div>
	
	<div class="text-end">
		@if ($checkComponents && $checkPermissions)
			<a href="{{ $installUrl . '/site_info' }}" class="btn btn-primary">
				{!! trans('messages.next') !!} <i class="fa-solid fa-chevron-right position-right"></i>
			</a>
		@else
			<a href="{{ $installUrl . '/system_compatibility' }}" class="btn btn-primary">
				<i class="fa-solid fa-rotate-right position-right"></i> {!! trans('messages.try_again') !!}
			</a>
		@endif
	</div>

@endsection

@section('after_scripts')
	<script type="text/javascript" src="{{ url()->asset('assets/plugins/forms/styling/uniform.min.js') }}"></script>
@endsection
