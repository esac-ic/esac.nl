<?php

namespace App\Notifications;

use App\AgendaItem;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AgendaSubscribed extends Notification
{
    use Queueable;

    private $agendaItem;

    /**
     * Create a new notification instance.
     *
     * @param AgendaItem $agendaItem
     */
    public function __construct(AgendaItem $agendaItem)
    {
        $this->agendaItem = $agendaItem;
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
        $url = route('agenda.detail', $this->agendaItem);
        $title = $this->agendaItem->agendaItemTitle->text();

        return (new MailMessage)
                    ->subject('Agenda item registration')
                    ->line('You just registered to '.$title.'.')
                    ->action('View agenda item', $url)
                    ->line('Thank you for signing up!');
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
