<?php

namespace App\Jobs;

use App\Mail\OfferCreatedMail;
use App\Models\Offer;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOfferEmails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $offer;

    /**
     * Create a new job instance.
     */
    public function __construct(Offer $offer)
    {
        $this->offer = $offer;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Chunk users to prevent memory exhaustion
        User::chunk(100, function ($users) {
            foreach ($users as $user) {
                // Send email to each user
                // Using 'send' here because the Job itself is queued. 
                // If we used 'queue' here, it would create thousands of jobs. 
                // Sending directly inside a queued job is acceptable for moderate numbers, 
                // but for massive scale, we might want to dispatch individual jobs per user.
                // For now, simple loop is fine.
                try {
                    Mail::to($user->email)->send(new OfferCreatedMail($this->offer));
                } catch (\Exception $e) {
                    // Log error but continue
                    \Log::error("Failed to send offer email to {$user->email}: " . $e->getMessage());
                }
            }
        });
    }
}
