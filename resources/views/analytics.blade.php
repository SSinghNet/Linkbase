<x-layouts::app :title="__('Analytics')">
    <div class="min-h-screen">
        <div class="mx-auto flex w-full max-w-7xl flex-col gap-6">
            <x-yourbase.panel
                eyebrow="Performance"
                heading="Analytics"
                subheading="Track profile views, clicks, and top-performing links across the selected range."
            >
                <x-slot:actions>
                    <x-analytics.range-filters
                        :available-ranges="$availableRanges"
                        :end-date="$endDate"
                        :is-custom-range="$isCustomRange"
                        :range="$range"
                        :start-date="$startDate"
                    />
                </x-slot:actions>

                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    <x-analytics.stat-card label="View Total" :value="$viewTotal" />
                    <x-analytics.stat-card label="Click Total" :value="$clickTotal" />
                    <x-analytics.stat-card label="Click Through Rate" :value="number_format($clickThroughRate, 1).'%'"
                        :numeric="false" />
                    <x-analytics.stat-card label="Active Links Total" :value="$activeLinksTotal" />
                </div>
            </x-yourbase.panel>

            <x-analytics.click-chart :chart-labels="$chartLabels" :chart-values="$chartValues" :range-label="$rangeLabel" />

            <div class="grid gap-6 xl:grid-cols-[minmax(0,1.5fr)_minmax(0,1fr)]">
                <x-analytics.link-summary-table :link-summaries="$linkSummaries" />
                <x-analytics.user-agent-summary-table :user-agent-summary="$userAgentSummary" />
            </div>
        </div>
    </div>
</x-layouts::app>
