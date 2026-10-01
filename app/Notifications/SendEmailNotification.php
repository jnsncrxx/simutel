<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendEmailNotification extends Notification
{
    use Queueable;

    private $details;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details=$details;
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
        $message = (new MailMessage)
            ->greeting($this->details['greeting'])
            ->line($this->details['firstline'])
            ->line($this->details['body'])
            ->line($this->details['lastline']);

        if(isset($this->details['button'])){
            $message->action($this->details['button'], $this->details['url']);
        }

        if(isset($this->details['confirmation'])){
            foreach ($this->details['confirmation'] as $key => $value) {
                $message->attachData($value, "Confirmation #".$key.".pdf");
            }
        }
        
        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
