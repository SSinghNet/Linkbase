<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['user_id', 'title', 'url', 'icon', 'order', 'is_active'])]
class Link extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'order' => 'integer',
        ];
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function clicks(): HasMany
    {
        return $this->hasMany(LinkClick::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function scopeWithClicksCountWithinPeriod(Builder $query, ?CarbonInterface $startDate, ?CarbonInterface $endDate): Builder
    {
        return $query->withCount([
            'clicks as filtered_clicks_count' => fn (Builder $query) => $query->withinPeriod($startDate, $endDate),
        ]);
    }

    // Helpers
    public function clickCount(int $days = 30): int
    {
        return $this->clicks()
            ->where('clicked_at', '>=', now()->subDays($days))
            ->count();
    }
}
