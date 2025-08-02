<x-layouts.app.plain :title="$title ?? null">
    <div class="absolute top-4 left-4 z-50">
        <img src="{{ asset('images/dicalogo.png') }}" alt="Dicalist Logo"
            class="h-12 w-auto sm:h-16 md:h-20 lg:h-24 object-contain" />
    </div>
    <flux:main>
        {{ $slot }}
    </flux:main>
</x-layouts.app.plain>
