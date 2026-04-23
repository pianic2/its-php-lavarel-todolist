<?php

namespace App\Notifications;

use App\Models\TaskList;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ListShared extends Notification
{
    use Queueable;

    public function __construct(protected TaskList $list, protected ?User $sender = null)
    {
    }

    /**
     * Get the notification's delivery channels.
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
        $senderName = $this->sender?->name ?? 'Un utente';

        return (new MailMessage)
                    ->subject(sprintf('%s ha condiviso una lista con te', $senderName))
                    ->greeting(sprintf('Ciao %s,', $notifiable->name ?? ''))
                    ->line(sprintf('%s ha condiviso la lista "%s" con te.', $senderName, $this->list->name))
                    ->action('Apri lista', url(route('lists.show', $this->list)))
                    ->line('Se non vuoi più avere accesso a questa lista, contatta il proprietario.');
    }
}
