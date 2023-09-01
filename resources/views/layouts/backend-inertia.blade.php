<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('jw-styles/juzaweb/images/favicon.ico') }}"/>

    @viteReactRefresh

    @php
        $component = explode('::', $page['component'])[1];
    @endphp

    @vite(
        ["resources/js/app.tsx", "vendor/juzaweb/modules/resources/css/app.scss", "vendor/juzaweb/modules/resources/js/pages/{$component}.tsx"],
        'jw-styles/juzaweb/build'
    )

    @do_action('juzaweb_header')

    @php
        $__inertiaSsrDispatched = true;
        $__inertiaSsrResponse = null;
    @endphp

    @inertiaHead
</head>
<body class="juzaweb__menuLeft--dark juzaweb__menuLeft--unfixed juzaweb__menuLeft--shadow">
<div id="admin-overlay">
    <div class="cv-spinner">
        <span class="spinner"></span>
    </div>
</div>

@inertia


<form action="{{ route('logout') }}" method="post" class="form-logout box-hidden">
    @csrf
</form>

@do_action('juzaweb_footer')

</body>
</html>
