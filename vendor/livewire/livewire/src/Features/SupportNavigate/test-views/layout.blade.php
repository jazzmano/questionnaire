<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/css/app.css')
    <script src="/test-navigate-asset.js?v=123"></script>
    @fluxAppearance
</head>
<body>
    {{ $slot }}

    @stack('scripts')
    @fluxScripts
</body>
</html>


