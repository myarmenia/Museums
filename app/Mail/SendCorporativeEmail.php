<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Storage;


class SendCorporativeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public array $data;
    public string $email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $email)
    {
        $this->data = $data;
        $this->email = $email;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Send Corporative Email',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'layouts.mail.ownerSendCorporativeEmail',
        );
    }

    public function build()
    {
        return $this->with([
            'data' => $this->data,
        ])
            ->to($this->email);
    }
}
