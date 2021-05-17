<?php

namespace Apiato\Core\Abstracts\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailLaravelNotification;

/**
 * Class VerifyEmail.
 */
abstract class VerifyEmail extends VerifyEmailLaravelNotification
{
    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return config('notification.channels');
    }
}
