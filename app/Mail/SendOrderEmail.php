<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Storage;


class SendOrderEmail extends Mailable
{
    use Queueable, SerializesModels;

    public array $data;

    /**
     * Create a new message instance.
     *
     * @return void
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
            subject: 'Send Order Email',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'layouts.mail.sendorder',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        if(isset($this->data['file']) && file_exists(public_path(Storage::url($this->data['file'])))){
            return [
                public_path(Storage::url($this->data['file']))
            ];
        }
        return [];
    }

    public function build()
    {
        return $this->with([
                        'data' => $this->data,
                    ])
                    // ->attach(public_path(Storage::url($this->data['file'])))
                    // ->when(isset($this->data['file']) && file_exists(public_path(Storage::url($this->data['file']))),
                    // function ($message) {
                    //     $message->attach(public_path(Storage::url($this->data['file'])));
                    // })
                    ->to(config('project.webex_email'));
    }
}
