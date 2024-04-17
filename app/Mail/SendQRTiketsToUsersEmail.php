<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendQRTiketsToUsersEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $email;
    public $logoPath;

  /**
   * Create a new message instance.
   */
    public function __construct($data, $email)
    {
        $this->data = $data;
        $this->email = $email;
        $this->logoPath = public_path('assets/img/logos/logo.png');

    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Send QR Tikets To Users Email',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'layouts.mail.send-qr-ticket',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        // return ['logo' => storage_path('/assets/img/logos/logo.png')];
        return [];
    }


    public function build()
    {

      // $logoPath = public_path('assets/img/logos/logo.png');
          return $this->with([
            'data' => $this->data,
          ])->to($this->email);

    }
}
