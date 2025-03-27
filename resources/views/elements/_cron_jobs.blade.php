<h3 class="title-3"><i class="fa-regular fa-clock"></i> {{ trans('messages.setting_up_cron_jobs') }}</h3>

<div class="alert {{ isAdminPanel() ? 'bg-light-info' : 'alert-info' }}">
    {!! trans('messages.cron_jobs_guide') !!}
</div>

@if (empty($phpBinaryPath))
    <div class="alert alert-warning">
        Cannot find PHP_BIN_PATH in your server. Please find it and replace all {PHP_BIN_PATH} text below with that one.
        <br>e.g. /usr/bin/php{{ $requiredPhpVersion ?? '8.2' }}, /usr/bin/php, /usr/lib/php.
    </div>
    @php
        $phpBinaryPath = '<span class="text-danger">{PHP_BIN_PATH}</span>';
    @endphp
@endif

@php
    $basePath = $basePath ?? base_path();
	$basePath = str($basePath)->finish('/')->toString();
@endphp
<div class="alert alert-light">
    <code>* * * * * {!! $phpBinaryPath !!} {{ $basePath }}artisan schedule:run >> /dev/null 2>&amp;1</code>
</div>
