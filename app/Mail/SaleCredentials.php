<?php

namespace App\Mail;

use App\Models\Sale;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SaleCredentials extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Sale $sale,
        public string $password,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Sales Account Credentials',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.sale-credentials',
            with: [
                'sale' => $this->sale,
                'password' => $this->password,
            ],
        );
    }
}
