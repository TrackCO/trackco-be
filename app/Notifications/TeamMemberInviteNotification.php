<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class TeamMemberInviteNotification extends Notification implements ShouldQueue
{
    use Queueable;
    protected $user;
    protected $token;
    protected $companyName;
    /**
     * Create a new notification instance.
     *
     * @param $user
     * @param $token
     * @param $companyName
     */
    public function __construct($user, $token, $companyName)
    {
        $this->user = $user;
        $this->token = $token;
        $this->companyName = $companyName;
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
        $frontendUrl = config('app.frontend_url');
        $activationUrl = "{$frontendUrl}/auth/activate-account?token={$this->token}&email={$this->user->email}&type=business";
        return (new MailMessage)
            ->subject(Lang::get('Invitation to Join the Team'))
            ->view(
                'emails.team_invite',
                [
                    'user' => $this->user,
                    'url' => $activationUrl,
                    'company' => $this->companyName,
                    'notifiable' => $notifiable
                ]
            );
    }

}
