<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StaffCredentialsNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public string $temporaryPassword,
        public string $role,
        public string $loginUrl
    ) {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Staff Credentials - Memorial Platform')
            ->greeting("Hello {$notifiable->name},")
            ->line("An administrator has provisioned a staff account for you on the Memorial Platform with the role: **{$this->role}**.")
            ->line('You can access your account using the following temporary credentials:')
            ->line("**Login Email:** {$notifiable->email}")
            ->line("**Temporary Password:** `{$this->temporaryPassword}`")
            ->action('Access Admin Portal', $this->loginUrl)
            ->line('For security purposes, please log in and update your password immediately from your profile settings.')
            ->salutation('Regards, The Platform Administration Team');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'role' => $this->role,
            'email' => $notifiable->email,
        ];
    }
}
