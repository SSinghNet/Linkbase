@props([
    'eyebrow' => null,
    'heading' => null,
    'subheading' => null,
])

<section {{ $attributes->class('rounded-[1.75rem] border border-zinc-200/80 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-zinc-900') }}>
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div class="space-y-1">
            @if ($eyebrow)
                <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">{{ $eyebrow }}</p>
            @endif

            @if ($heading)
                <flux:heading size="lg">{{ $heading }}</flux:heading>
            @endif

            @if ($subheading)
                <flux:text class="text-zinc-600 dark:text-zinc-300">{{ $subheading }}</flux:text>
            @endif
        </div>

        @if (isset($actions))
            <div class="shrink-0">
                {{ $actions }}
            </div>
        @endif
    </div>

    {{ $slot }}
</section>
