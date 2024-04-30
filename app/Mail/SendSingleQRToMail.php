<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;


class SendSingleQRToMail extends Mailable
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

    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
      return new Envelope(
        subject: 'Հայաստանի թանգարաններ։ ՏՈՄՍ',
      );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
      return new Content(
        view: 'layouts.mail.send-single-qr-ticket',
      );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
      // dd($this->data);
        $qr_images = [];
        $museum_name = '';

        if ($this->data->type == 'united') {

          $united_museums = $this->data->purchased_item->purchase_united_tickets->pluck("museum.translationsForAdmin.name");

          if (count($united_museums) > 0) {
            foreach ($united_museums as $name) {
              $museum_name .= $name . ", ";
            }
          }

          $museum_name = substr($museum_name, 0, -2);
          $qr_images[$museum_name] = Storage::disk('local')->path($this->data->path);

        } else {

          $museum_name = $this->data->museum->translation('en')->name;
          $qr_images[$museum_name] = Storage::disk('local')->path($this->data->path);
        }



      return $qr_images;

    }

    public function build()
    {


      $mail = $this->with([
        'result' => $this->data,
      ])->to($this->email);


      foreach ($this->attachments() as $name => $path) {
        $mail->attach($path, [
          'as' => $name,
        ]);
      }

      return $mail;

    }
}
