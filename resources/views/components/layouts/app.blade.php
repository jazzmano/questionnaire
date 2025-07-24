<x-layouts.app.plain :title="$title ?? null">
    <div class="absolute top-4 left-4">
        <img src="{{ asset('images/dicalist_logo.png') }}" alt="Dicalist Logo" class="h-32 w-auto" />
    </div>
    <flux:main>
        {{ $slot }}
    </flux:main>
</x-layouts.app.plain>
