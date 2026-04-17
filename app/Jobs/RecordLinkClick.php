<?php

namespace App\Jobs;

use App\Models\Link;
use App\Models\LinkClick;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RecordLinkClick implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $backoff = 5;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public readonly Link $link,
        public readonly string $ipAddress,
        public readonly string $userAgent,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        LinkClick::create([
            'link_id' => $this->link->id,
            'ip_address' => $this->ipAddress,
            'user_agent' => $this->userAgent,
            'clicked_at' => now(),
        ]);
    }
}
