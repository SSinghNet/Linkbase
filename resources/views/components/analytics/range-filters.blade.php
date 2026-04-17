@props([
    'availableRanges' => [],
    'endDate' => '',
    'isCustomRange' => false,
    'range' => '30d',
    'startDate' => '',
])

<div class="flex flex-col items-stretch gap-3 lg:items-end">
    <div class="flex flex-wrap items-center gap-2">
        @foreach ($availableRanges as $availableRange => $label)
            <a
                href="{{ route('analytics', ['range' => $availableRange]) }}"
                @class([
                    'rounded-full border px-3 py-1.5 text-sm font-medium transition',
                    'border-zinc-900 bg-zinc-900 text-white dark:border-white dark:bg-white dark:text-zinc-900' => $range === $availableRange,
                    'border-zinc-200 bg-white text-zinc-600 hover:border-zinc-300 hover:text-zinc-900 dark:border-white/10 dark:bg-white/5 dark:text-zinc-300 dark:hover:border-white/20 dark:hover:text-white' => $range !== $availableRange,
                ])
            >
                {{ $label }}
            </a>
        @endforeach

        <span
            @class([
                'rounded-full border px-3 py-1.5 text-sm font-medium',
                'border-teal-600 bg-teal-600 text-white dark:border-teal-400 dark:bg-teal-400 dark:text-zinc-900' => $isCustomRange,
                'border-zinc-200 bg-white text-zinc-400 dark:border-white/10 dark:bg-white/5 dark:text-zinc-500' => ! $isCustomRange,
            ])
        >
            Custom
        </span>
    </div>

    <form method="GET" action="{{ route('analytics') }}" class="flex flex-col gap-2 sm:flex-row sm:flex-wrap sm:items-end">
        <input type="hidden" name="range" value="custom" />

        <label class="flex flex-col gap-1 text-sm text-zinc-600 dark:text-zinc-300">
            <span>Start date</span>
            <input
                type="date"
                name="start_date"
                value="{{ $startDate }}"
                class="rounded-xl border border-zinc-200 bg-white px-3 py-2 text-zinc-900 shadow-sm outline-none transition focus:border-zinc-400 dark:border-white/10 dark:bg-white/5 dark:text-white"
            />
        </label>

        <label class="flex flex-col gap-1 text-sm text-zinc-600 dark:text-zinc-300">
            <span>End date</span>
            <input
                type="date"
                name="end_date"
                value="{{ $endDate }}"
                class="rounded-xl border border-zinc-200 bg-white px-3 py-2 text-zinc-900 shadow-sm outline-none transition focus:border-zinc-400 dark:border-white/10 dark:bg-white/5 dark:text-white"
            />
        </label>

        <button
            type="submit"
            class="rounded-xl bg-zinc-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-zinc-700 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200"
        >
            Apply
        </button>
    </form>
</div>
