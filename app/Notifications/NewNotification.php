<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewNotification extends Notification
{
    use Queueable;

    public function __construct()
    {
       
    }

    public function via($notifiable)
    {
        return ['mail','database'];
    }

    public function toMail($notifiable)
    {

        $msg = new MailMessage ;

       $msg
                    ->subject('new register')
                    ->greeting('hello ' )
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
                    return $msg ;

    }



















    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
