<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>{{ config('app.name') }}</title>

    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body>
<div id="app" style="min-height: 100vh"></div>

{{-- Global configuration object --}}
<script>
    window.config = @json($config);
</script>

{{-- Load the application scripts --}}
<script src="{{ mix('js/app.js') }}"></script>
</body>
</html>