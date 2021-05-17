<?php

namespace Apiato\Core\Abstracts\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordLaravelNotification;

/**
 * Class ResetPassword.
 */
abstract class ResetPassword extends ResetPasswordLaravelNotification
{
    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return config('notification.channels');
    }
}
