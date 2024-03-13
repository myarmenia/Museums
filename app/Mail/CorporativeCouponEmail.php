<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CorporativeCouponEmail extends Mailable
{
    use Queueable, SerializesModels;

    public string $coupon;
    public string $email;

    public function __construct($email, $coupon)
    {
        $this->coupon = $coupon;
        $this->email = $email;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Corporative Coupon Email',
        );
    }

    public function content()
    {
        return new Content(
            view: 'layouts.mail.sendCoupon',
        );
    }
    public function attachments()
    {
        return [];
    }
    public function build()
    {
        return $this->with([
            'coupon' => $this->coupon,
        ])
            ->to($this->email);
    }
}
