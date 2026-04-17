@props([
    'linkSummaries' => collect(),
])

<x-yourbase.panel
    eyebrow="Summary"
    heading="Clicks Per Link"
    subheading="How each link performed in the selected range."
>
    <div class="overflow-hidden rounded-[1.5rem] border border-zinc-200/80 dark:border-white/10">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200/80 text-sm dark:divide-white/10">
                <thead class="bg-zinc-50/80 dark:bg-white/5">
                    <tr class="text-left text-zinc-500 dark:text-zinc-400">
                        <th class="px-4 py-3 font-medium">Link</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 text-right font-medium">Clicks</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200/80 bg-white dark:divide-white/10 dark:bg-zinc-900/60">
                    @forelse ($linkSummaries as $link)
                        <tr class="text-zinc-700 dark:text-zinc-200">
                            <td class="px-4 py-3">
                                <div class="font-medium text-zinc-950 dark:text-white">{{ $link->title }}</div>
                                <div class="truncate text-xs text-zinc-500 dark:text-zinc-400">{{ $link->url }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <span
                                    @class([
                                        'inline-flex rounded-full px-2.5 py-1 text-xs font-medium',
                                        'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-300' => $link->is_active,
                                        'bg-zinc-200 text-zinc-600 dark:bg-white/10 dark:text-zinc-300' => ! $link->is_active,
                                    ])
                                >
                                    {{ $link->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right font-semibold text-zinc-950 dark:text-white">
                                {{ number_format($link->filtered_clicks_count) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-8 text-center text-sm text-zinc-500 dark:text-zinc-400">
                                No links found for this account yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-yourbase.panel>
