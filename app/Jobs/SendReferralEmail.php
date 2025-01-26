<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ReferralEmailNotification;

class SendReferralEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array  $user;
    protected string $referrerName;
    protected string $referralLink;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $referrerName, $referralLink)
    {
        $this->user = $user;
        $this->referrerName = $referrerName;
        $this->referralLink = $referralLink;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Notification::route('mail', $this->user['email'])
                ->notify(new ReferralEmailNotification($this->user, $this->referrerName, $this->referralLink));
        } catch (\Exception $e) {
            \Log::error("Mail Error:: " . $e->getMessage());
        }
    }
}
