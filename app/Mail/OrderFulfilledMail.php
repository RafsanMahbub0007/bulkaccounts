<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderFulfilledMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order,
        public string $filePath
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Order #' . $this->order->order_number . ' is Ready!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-fulfilled',
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromPath(storage_path('app/public/' . $this->filePath))
                ->as('order_' . $this->order->order_number . '.xlsx')
                ->withMime('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'),
        ];
    }
}
