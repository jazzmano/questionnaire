<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">

        {{-- Optional mobile header (remove if not needed) --}}
        {{--
        <flux:header class="lg:hidden">
            <flux:spacer />
        </flux:header>
        --}}

        {{-- Main content --}}
        {{ $slot }}

        @fluxScripts
    </body>
</html>
