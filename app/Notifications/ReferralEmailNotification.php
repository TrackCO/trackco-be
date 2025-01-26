<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;

class ReferralEmailNotification extends Notification
{
    use Queueable;
    protected string $referrerName;
    protected string $referralLink;
    protected array $recipient;

    /**
     * Create a new notification instance.
     *
     * @param string $referrerName
     * @param string $referralLink
     */
    public function __construct(array $recipient, string $referrerName, string $referralLink)
    {
        $this->referrerName = $referrerName;
        $this->referralLink = $referralLink;
        $this->recipient = $recipient;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $notifiable->email = $notifiable->routes['mail'];
        return (new MailMessage)
            ->subject(Lang::get('You Have Been Referred to Join'))
            ->view('emails.referral_email', [
                'referrerName' => $this->referrerName,
                'referralLink' => $this->referralLink,
                'notifiable' => $notifiable,
                'recipient' => $this->recipient,
            ]);
    }


}
