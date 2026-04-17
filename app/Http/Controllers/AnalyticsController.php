<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    /**
     * @return View<Arrayable<string, mixed>>
     */
    public function __invoke(Request $request): View
    {
        $user = Auth::user();
        $period = $this->resolvePeriod($request);
        $chartData = $user->clickChartData($period['startDate'], $period['endDate']);
        $viewTotal = $user->filteredProfileViews($period['startDate'], $period['endDate'])->count();
        $clickTotal = $user->filteredLinkClicks($period['startDate'], $period['endDate'])->count();
        $clickThroughRate = $viewTotal > 0
            ? round(($clickTotal / $viewTotal) * 100, 1)
            : 0.0;

        return view('analytics', [
            'activeLinksTotal' => $user->activeLinks()->count(),
            'availableRanges' => [
                '7d' => '7D',
                '30d' => '30D',
                '90d' => '90D',
                'all' => 'All time',
            ],
            'chartLabels' => $chartData['labels'],
            'chartValues' => $chartData['values'],
            'clickThroughRate' => $clickThroughRate,
            'clickTotal' => $clickTotal,
            'endDate' => $period['endDateInput'],
            'isCustomRange' => $period['range'] === 'custom',
            'linkSummaries' => $user->linkSummaries($period['startDate'], $period['endDate']),
            'range' => $period['range'],
            'rangeLabel' => $period['label'],
            'startDate' => $period['startDateInput'],
            'userAgentSummary' => $user->userAgentSummary($period['startDate'], $period['endDate']),
            'viewTotal' => $viewTotal,
        ]);
    }

    /**
     * @return array{
     *     range: string,
     *     label: string,
     *     startDate: ?Carbon,
     *     endDate: ?Carbon,
     *     startDateInput: string,
     *     endDateInput: string
     * }
     */
    private function resolvePeriod(Request $request): array
    {
        $validated = $request->validate([
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'range' => ['nullable', 'string'],
            'start_date' => ['nullable', 'date'],
        ]);

        $allowedRanges = [
            '7d' => 7,
            '30d' => 30,
            '90d' => 90,
            'all' => null,
        ];

        $requestedRange = (string) ($validated['range'] ?? '30d');
        $hasCustomDates = filled($validated['start_date'] ?? null) || filled($validated['end_date'] ?? null);

        if ($hasCustomDates || $requestedRange === 'custom') {
            $startDate = filled($validated['start_date'] ?? null)
                ? Carbon::parse($validated['start_date'])->startOfDay()
                : null;
            $endDate = filled($validated['end_date'] ?? null)
                ? Carbon::parse($validated['end_date'])->endOfDay()
                : now()->endOfDay();

            return [
                'range' => 'custom',
                'label' => 'Custom range',
                'startDate' => $startDate,
                'endDate' => $endDate,
                'startDateInput' => $startDate?->toDateString() ?? '',
                'endDateInput' => $endDate->toDateString(),
            ];
        }

        if (! array_key_exists($requestedRange, $allowedRanges)) {
            $requestedRange = '30d';
        }

        $days = $allowedRanges[$requestedRange];
        $startDate = $days === null ? null : now()->subDays($days - 1)->startOfDay();
        $endDate = now()->endOfDay();

        return [
            'range' => $requestedRange,
            'label' => match ($requestedRange) {
                '7d' => 'Last 7 days',
                '30d' => 'Last 30 days',
                '90d' => 'Last 90 days',
                default => 'All time',
            },
            'startDate' => $startDate,
            'endDate' => $endDate,
            'startDateInput' => $startDate?->toDateString() ?? '',
            'endDateInput' => $endDate->toDateString(),
        ];
    }
}
