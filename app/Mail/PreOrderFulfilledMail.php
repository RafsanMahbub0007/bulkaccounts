<?php

namespace App\Mail;

use App\Models\PreOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class PreOrderFulfilledMail extends Mailable
{
    use Queueable, SerializesModels;

    public $preOrder;

    /**
     * Create a new message instance.
     */
    public function __construct(PreOrder $preOrder)
    {
        $this->preOrder = $preOrder;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Pre-Order is Ready! #' . $this->preOrder->order_number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.pre-order-fulfilled',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        // Attach the file if it exists
        if ($this->preOrder->download_file && Storage::disk('public')->exists($this->preOrder->download_file)) {
             return [
                \Illuminate\Mail\Mailables\Attachment::fromStorageDisk('public', $this->preOrder->download_file)
                    ->as('accounts-' . $this->preOrder->order_number . '.xlsx')
                    ->withMime('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'),
            ];
        }
        return [];
    }
}
