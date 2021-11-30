<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailMessage extends Mailable
{
    use Queueable, SerializesModels;


    private $nomorPendaftaran = '';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($nomorPendaftaran)
    {
        $this->nomorPendaftaran = $nomorPendaftaran;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Berhasil Registrasi')
        ->view('frontend.email')->with('nomorPendaftaran', $this->nomorPendaftaran);
    }
}
