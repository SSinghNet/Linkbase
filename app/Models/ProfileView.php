<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'ip_address', 'user_agent', 'country', 'viewed_at'])]
class ProfileView extends Model
{
    public const CREATED_AT = null;

    public const UPDATED_AT = null;

    protected function casts(): array
    {
        return [
            'viewed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeWithinPeriod(Builder $query, ?CarbonInterface $startDate, ?CarbonInterface $endDate): Builder
    {
        return $query
            ->when($startDate, fn (Builder $query) => $query->where('viewed_at', '>=', $startDate))
            ->when($endDate, fn (Builder $query) => $query->where('viewed_at', '<=', $endDate));
    }

    public function scopeGroupedByUserAgent(Builder $query): Builder
    {
        return $query
            ->selectRaw("COALESCE(NULLIF(user_agent, ''), 'Unknown') as user_agent, COUNT(*) as total")
            ->groupBy('user_agent');
    }
}
