@props([
    'label',
    'numeric' => true,
    'value',
])

<article class="rounded-[1.5rem] border border-zinc-200/80 bg-zinc-50 p-5 dark:border-white/10 dark:bg-white/5">
    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">{{ $label }}</p>
    <p class="mt-3 text-3xl font-semibold tracking-tight text-zinc-950 dark:text-white">
        {{ $numeric ? number_format($value) : $value }}
    </p>
</article>
