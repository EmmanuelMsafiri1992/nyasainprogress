@php
	$name ??= 'field_name';
	$classes ??= '';
	$class ??= '';
	$isInvalidClass ??= '';
	
	$disabled ??= false;
	$value ??= '';
	
	$checkedAttr = (old($name, $value) == '1') ? ' checked' : '';
	$disabledAttr = (isset($disabled) && $disabled) ? ' disabled="disabled"' : '';
@endphp
<div class="mb-3 form-check" style="margin-top: 30px;">
	<input type="checkbox"
		id="{{ $name }}"
		name="{{ $name }}"
		value="1"
		class="form-check-input{{ $isInvalidClass.$classes.$class }}"
		data-on-text="On"
		data-off-text="Off"
		data-on-color="success"
		data-off-color="default"
		{!! $checkedAttr.$disabledAttr !!}
	>
	<label class="form-check-label">
		@if (!empty($label))
			{!! $label !!}
		@endif
	</label>
	
	@if (isset($hint) && !empty($hint))
		<div class="form-text">{!! $hint !!}</div>
	@endif
</div>
