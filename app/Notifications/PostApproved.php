<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\post;

class PostApproved extends Notification
{
    use Queueable;

    protected $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Post Approved')
                    ->greeting('Hello, ' . $notifiable->name)
                    ->line('Your post has been approved: ' . $this->post->content)
                    ->action('View Post', url('/' . $this->post->id))
                    ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return [
            'post_id' => $this->post->id,
            'post_content' => $this->post->content,
        ];
    }
}
