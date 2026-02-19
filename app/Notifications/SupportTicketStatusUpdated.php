<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SupportTicketStatusUpdated extends Notification
{
    use Queueable;

    public $ticket;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
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
            'type' => 'support_ticket',
            'ticket_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_id,
            'status' => $this->ticket->status,
            'message' => 'Status for Ticket #' . $this->ticket->ticket_id . ' has been updated to ' . ucfirst($this->ticket->status),
        ];
    }
}
