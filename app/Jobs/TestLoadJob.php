<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\TestLoadJobNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class TestLoadJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $index)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = User::factory()->create();

        $user->notify(new TestLoadJobNotification($this->index));
    }
}
