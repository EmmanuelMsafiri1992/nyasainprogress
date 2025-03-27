@php
	$prefix ??= '';
	$suffix ??= '';
	$name ??= 'field_name';
	$type ??= 'text';
	$class = !empty($class) ? ' ' . $class : '';
	$rules ??= [];
	$hint ??= '';
	$label = $label ?? (trans()->has('messages.' . $name) ? trans('messages.' . $name) : '');
	$value ??= '';
	
	// Get group class
	$groupClass = (!empty($prefix) || !empty($suffix)) ? ' input-group' : '';
	
	// Get field name
	$fieldName = str_replace('[]', '', $name);
	$fieldName = str_replace('][', '.', $fieldName);
	$fieldName = str_replace('[', '.', $fieldName);
	$fieldName = str_replace(']', '', $fieldName);
	
	// Get field rules
	$fieldRules = $rules[$fieldName] ?? [];
	$fieldRules = is_string($fieldRules) ? explode('|', $fieldRules) : (is_array($fieldRules) ? $fieldRules : []);
	$fieldRules = collect($fieldRules)
		->reject(function ($item) {
			return (str_contains($item, ' ') || str_contains($item, '\\') || str_contains($item, 'new '));
		})->toArray();
	
	// Get the field rules as classes names
	$classes = !empty($fieldRules) ? ' ' . implode(' ', $fieldRules) : '';
	$classes = str_replace(['required', 'email'], '', $classes);
	
	// Check if the field is required
	$required = in_array('required', $fieldRules);
	
	// Get eventual field's error message
	$isInvalidClass = (isset($errors) && $errors->has($fieldName)) ? ' is-invalid' : '';
@endphp
@if ($type == 'checkbox')
	
	@include('install.helpers._' . $type, ['isInvalidClass' => $isInvalidClass])

@else
	<div class="mb-3{{ $groupClass }}">
		@if (!empty($label))
			<label class="form-label">
				{!! $label !!}
				@if ($required)
					<span class="text-danger">*</span>
				@endif
			</label>
		@endif
		
		@if ($type == 'textarea')
			@if ($errors->has($fieldName))
				<span class="invalid-feedback">
					<strong>{{ $errors->first($fieldName) }}</strong>
				</span>
			@endif
		@endif
		
		@if (!empty($prefix))
			<span class="input-group-text">
				{!! $prefix !!}
			</span>
		@endif
		
		@include('install.helpers._' . $type, ['isInvalidClass' => $isInvalidClass])
		
		@if (!empty($suffix))
			<span class="input-group-text">
				{!! $suffix !!}
			</span>
		@endif
		
		@if (!empty($hint))
			<div class="form-text">
				{!! $hint !!}
			</div>
		@endif
		
		@if ($type != 'textarea')
			@if ($errors->has($fieldName))
				<span class="invalid-feedback">
					<strong>{{ $errors->first($fieldName) }}</strong>
				</span>
			@endif
		@endif
	</div>
@endif
