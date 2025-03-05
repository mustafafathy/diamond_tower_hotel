<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactUsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('New Contact Message')
            ->view('emails.contactus')
            ->with([
                'name' => $this->data['name'],
                'phone' => $this->data['phone'],
                'email' => $this->data['email'],
                'messageContent' => $this->data['message'],
            ]);
    }
}
