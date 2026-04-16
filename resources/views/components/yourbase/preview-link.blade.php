@props([
    'buttonStyle' => 'solid',
    'icon' => 'fa-solid fa-link',
    'title' => '',
])

<div
    {{ $attributes->class('flex items-center justify-between rounded-2xl px-4 py-3 text-sm') }}
    style="
        border: 1px solid color-mix(in srgb, var(--yb-accent) 30%, transparent);
        background: {{ $buttonStyle === 'outline' ? 'transparent' : 'color-mix(in srgb, var(--yb-accent) '.($buttonStyle === 'soft' ? '16%' : '100%').', var(--yb-surface))' }};
        color: {{ $buttonStyle === 'solid' ? 'var(--yb-background)' : 'var(--yb-text)' }};
    "
>
    <span class="flex min-w-0 items-center gap-2 truncate">
        <span class="flex size-4 shrink-0 items-center justify-center">
            <i class="{{ $icon }}"></i>
        </span>
        <span class="truncate">{{ $title }}</span>
    </span>

    <span class="opacity-70">{{ __('Visit') }}</span>
</div>
