<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }


    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('重置密码')
                    ->line('您正在申请重置密码')
                    ->action('点击重置', url('password/reset', $this->token))
                    ->line('如果您没有申请重置密码，请忽略此信息');
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
