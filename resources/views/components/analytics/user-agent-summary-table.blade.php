@props([
    'userAgentSummary' => collect(),
])

<x-yourbase.panel
    eyebrow="Audience"
    heading="User Agent Summary"
    subheading="A combined view of where profile views and clicks came from."
>
    <div class="overflow-hidden rounded-[1.5rem] border border-zinc-200/80 dark:border-white/10">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200/80 text-sm dark:divide-white/10">
                <thead class="bg-zinc-50/80 dark:bg-white/5">
                    <tr class="text-left text-zinc-500 dark:text-zinc-400">
                        <th class="px-4 py-3 font-medium">User Agent</th>
                        <th class="px-4 py-3 text-right font-medium">Views</th>
                        <th class="px-4 py-3 text-right font-medium">Clicks</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200/80 bg-white dark:divide-white/10 dark:bg-zinc-900/60">
                    @forelse ($userAgentSummary as $agent)
                        <tr class="text-zinc-700 dark:text-zinc-200">
                            <td class="px-4 py-3 font-medium text-zinc-950 dark:text-white">{{ $agent['label'] }}</td>
                            <td class="px-4 py-3 text-right">{{ number_format($agent['views']) }}</td>
                            <td class="px-4 py-3 text-right">{{ number_format($agent['clicks']) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-8 text-center text-sm text-zinc-500 dark:text-zinc-400">
                                No user agent data is available for this filter yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-yourbase.panel>
