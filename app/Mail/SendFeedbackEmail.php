<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendFeedbackEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Обратной связь',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'layouts.mail.sendFeedback',
        );
    }

    public function build()
    {
        return $this->with([
                        'data' => $this->data,
                    ])
                    ->to(config('project.webex_email'));
    }
}


