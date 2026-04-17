<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\CarbonInterface;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

#[Fillable(['name', 'email', 'password', 'username', 'bio', 'avatar', 'theme'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'theme' => 'array',
        ];
    }

    public function links(): HasMany
    {
        return $this->hasMany(Link::class);
    }

    public function linkClicks(): HasManyThrough
    {
        return $this->hasManyThrough(
            LinkClick::class,
            Link::class,
        );
    }

    public function activeLinks(): HasMany
    {
        return $this->hasMany(Link::class)
            ->where('is_active', true)
            ->orderBy('order');
    }

    public function activeLinkClicks(): HasManyThrough
    {
        return $this->hasManyThrough(
            LinkClick::class,
            Link::class,
        )->where('links.is_active', true);
    }

    public function profileViews(): HasMany
    {
        return $this->hasMany(ProfileView::class);
    }

    public function filteredProfileViews(?CarbonInterface $startDate, ?CarbonInterface $endDate): HasMany
    {
        return $this->profileViews()->withinPeriod($startDate, $endDate);
    }

    public function filteredLinkClicks(?CarbonInterface $startDate, ?CarbonInterface $endDate): HasManyThrough
    {
        return $this->linkClicks()->withinPeriod($startDate, $endDate);
    }

    /**
     * @return array{labels: list<string>, values: list<int>}
     */
    public function clickChartData(?CarbonInterface $startDate, ?CarbonInterface $endDate): array
    {
        $clicksByDate = $this->filteredLinkClicks($startDate, $endDate)
            ->groupedByDate()
            ->get()
            ->pluck('total', 'clicked_on');

        $firstClickDate = $startDate === null
            ? $this->filteredLinkClicks($startDate, $endDate)->oldest('clicked_at')->value('clicked_at')
            : null;

        $chartStart = $startDate
            ?? ($firstClickDate ? Carbon::parse($firstClickDate)->startOfDay() : now()->startOfDay());
        $chartEnd = ($endDate ?? now())->copy()->startOfDay();
        $labels = [];
        $values = [];
        $date = $chartStart->copy();

        while ($date->lte($chartEnd)) {
            $labels[] = $date->format('M j');
            $values[] = (int) ($clicksByDate[$date->toDateString()] ?? 0);
            $date = $date->copy()->addDay();
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    /**
     * @return Collection<int, Link>
     */
    public function linkSummaries(?CarbonInterface $startDate, ?CarbonInterface $endDate): Collection
    {
        return $this->links()
            ->select(['id', 'title', 'url', 'is_active'])
            ->withClicksCountWithinPeriod($startDate, $endDate)
            ->orderByDesc('filtered_clicks_count')
            ->orderBy('title')
            ->get();
    }

    /**
     * @return SupportCollection<int, array{label: string, views: int, clicks: int, total: int}>
     */
    public function userAgentSummary(?CarbonInterface $startDate, ?CarbonInterface $endDate): SupportCollection
    {
        $viewAgentCounts = $this->filteredProfileViews($startDate, $endDate)
            ->groupedByUserAgent()
            ->pluck('total', 'user_agent');

        $clickAgentCounts = $this->filteredLinkClicks($startDate, $endDate)
            ->groupedByUserAgent()
            ->pluck('total', 'user_agent');

        return collect($viewAgentCounts->keys())
            ->merge($clickAgentCounts->keys())
            ->unique()
            ->map(function (string $userAgent) use ($viewAgentCounts, $clickAgentCounts): array {
                $views = (int) ($viewAgentCounts[$userAgent] ?? 0);
                $clicks = (int) ($clickAgentCounts[$userAgent] ?? 0);

                return [
                    'label' => LinkClick::summarizeUserAgent($userAgent),
                    'views' => $views,
                    'clicks' => $clicks,
                    'total' => $views + $clicks,
                ];
            })
            ->groupBy('label')
            ->map(fn (SupportCollection $rows, string $label): array => [
                'label' => $label,
                'views' => $rows->sum('views'),
                'clicks' => $rows->sum('clicks'),
                'total' => $rows->sum('total'),
            ])
            ->sortByDesc('total')
            ->values();
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }
}
