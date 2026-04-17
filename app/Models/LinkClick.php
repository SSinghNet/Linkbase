<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

#[Fillable(['link_id', 'ip_address', 'user_agent', 'country', 'clicked_at'])]
class LinkClick extends Model
{
    public const CREATED_AT = null;

    public const UPDATED_AT = null;

    protected function casts(): array
    {
        return [
            'clicked_at' => 'datetime',
        ];
    }

    public function link(): BelongsTo
    {
        return $this->belongsTo(Link::class);
    }

    public function scopeWithinPeriod(Builder $query, ?CarbonInterface $startDate, ?CarbonInterface $endDate): Builder
    {
        return $query
            ->when($startDate, fn (Builder $query) => $query->where('clicked_at', '>=', $startDate))
            ->when($endDate, fn (Builder $query) => $query->where('clicked_at', '<=', $endDate));
    }

    public function scopeGroupedByDate(Builder $query): Builder
    {
        return $query
            ->selectRaw('DATE(clicked_at) as clicked_on, COUNT(*) as total')
            ->groupBy('clicked_on')
            ->orderBy('clicked_on');
    }

    public function scopeGroupedByUserAgent(Builder $query): Builder
    {
        return $query
            ->selectRaw("COALESCE(NULLIF(user_agent, ''), 'Unknown') as user_agent, COUNT(*) as total")
            ->groupBy('user_agent');
    }

    public static function summarizeUserAgent(string $userAgent): string
    {
        if ($userAgent === 'Unknown') {
            return 'Unknown';
        }

        return match (true) {
            Str::contains($userAgent, ['Googlebot', 'bingbot', 'DuckDuckBot', 'facebookexternalhit', 'Slackbot']) => 'Bot / Crawler',
            Str::contains($userAgent, ['Edg/']) => 'Microsoft Edge',
            Str::contains($userAgent, ['OPR/', 'Opera']) => 'Opera',
            Str::contains($userAgent, ['Firefox/']) => 'Firefox',
            Str::contains($userAgent, ['Chrome/']) && ! Str::contains($userAgent, ['Edg/', 'OPR/']) => 'Chrome',
            Str::contains($userAgent, ['Safari/']) && ! Str::contains($userAgent, ['Chrome/', 'Chromium/']) => 'Safari',
            Str::contains($userAgent, ['iPhone', 'iPad', 'iOS']) => 'iPhone / iPad',
            Str::contains($userAgent, ['Android']) => 'Android',
            Str::contains($userAgent, ['Windows']) => 'Windows',
            Str::contains($userAgent, ['Macintosh', 'Mac OS X']) => 'macOS',
            Str::contains($userAgent, ['Linux']) => 'Linux',
            default => Str::limit($userAgent, 48),
        };
    }
}
