<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#ffc107">
    <meta name="secret-value" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ asset('storage/img/favicon.ico') }}">
    <link
        href="{{ !app()->environment('local') ? asset(mix('builds/producer/css/app.css')) : asset('builds/producer/css/app.css') }}"
        rel="stylesheet">

    <script src="{{ asset(mix('builds/vendor/manifest.js')) }}"></script>
    <script src="{{ asset(mix('builds/vendor/vendor.js')) }}"></script>
    <script
        src="{{ !app()->environment('local') ? asset(mix('builds/producer/js/app.js')) : asset('builds/producer/js/app.js') }}"
        defer></script>
</head>
<body>
<noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TW336KP" height="0" width="0"
            style="display:none;visibility:hidden"></iframe>
</noscript>

<div id="court-producing" style='height: 100%;'>
    <app-layout>
        <template slot="producer-content">
            @yield('app-producer')
        </template>
    </app-layout>
</div>
@includeWhen('local' === config('app.env') && config('app.strict'), 'dev.duplicate_query_marker')
</body>
</html>
