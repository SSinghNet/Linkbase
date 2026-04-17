@props([
    'chartLabels' => [],
    'chartValues' => [],
    'rangeLabel' => 'Selected range',
])

@php
    use Illuminate\Support\Js;
@endphp

<x-yourbase.panel eyebrow="Trend" heading="Clicks Over Time" :subheading="'Daily clicks for '.$rangeLabel.'.'">
    <div class="rounded-[1.5rem] border border-zinc-200/80 bg-white p-4 dark:border-white/10 dark:bg-zinc-950/40">
        <div class="h-80">
            <canvas id="analytics-clicks-chart"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.9/dist/chart.umd.min.js"></script>
    <script>
        (() => {
            const canvas = document.getElementById('analytics-clicks-chart');

            if (!canvas || typeof Chart === 'undefined') {
                return;
            }

            const labels = {{ Js::from($chartLabels) }};
            const values = {{ Js::from($chartValues) }};
            const textColor = document.documentElement.classList.contains('dark') ? '#e4e4e7' : '#18181b';
            const gridColor = document.documentElement.classList.contains('dark') ? 'rgba(255,255,255,0.08)' : 'rgba(24,24,27,0.08)';

            new Chart(canvas, {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                        label: 'Clicks',
                        data: values,
                        borderColor: '#0f766e',
                        backgroundColor: 'rgba(15, 118, 110, 0.14)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.35,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                    plugins: {
                        legend: {
                            display: false,
                        },
                    },
                    scales: {
                        x: {
                            ticks: {
                                color: textColor,
                            },
                            grid: {
                                color: gridColor,
                            },
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: textColor,
                                precision: 0,
                            },
                            grid: {
                                color: gridColor,
                            },
                        },
                    },
                },
            });
        })();
    </script>
</x-yourbase.panel>
