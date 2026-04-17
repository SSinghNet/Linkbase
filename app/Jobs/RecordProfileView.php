<?php

namespace App\Jobs;

use App\Models\ProfileView;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RecordProfileView implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $backoff = 5;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public readonly User $user,
        public readonly string $ipAddress,
        public readonly string $userAgent,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        ProfileView::create([
            'user_id' => $this->user->id,
            'ip_address' => $this->ipAddress,
            'user_agent' => $this->userAgent,
            'viewed_at' => now(),
        ]);
    }
}
