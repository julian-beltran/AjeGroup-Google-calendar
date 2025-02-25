<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendAgendaMail extends Mailable
{
    use Queueable, SerializesModels;

    //Declaracion de los campos del mail: ***********************************
    public $id_corporativo;
    public $id_espacio;
    public $espacio_nombre;
    public $id_user_invitado;
    public $user_name;
    public $email_user;
    public $fecha_hora_meet;
    public $id_area;
    public $meet_link;

    /**
     * Create a new message instance.
     */
    public function __construct($dataMail)
    {
        //Recogimiento de los datos preparados para el mail: *************
        $this->id_corporativo = $dataMail['id_corporativo'];
        $this->id_espacio = $dataMail['id_espacio'];
        $this->espacio_nombre = $dataMail['espacio_nombre'];
        $this->id_user_invitado = $dataMail['id_user_invitado'];
        $this->user_name = $dataMail['user_name'];
        $this->email_user = $dataMail['email_user'];
        $this->fecha_hora_meet = $dataMail['fecha_hora_meet'];
        $this->id_area = $dataMail['id_area'];
        $this->meet_link = $dataMail['meet_link'];
    }

    /**
     * @return $this
     */
    public function build()
    {
        // $subject = 'Nueva agenda programada';

        return $this->markdown('usuarios_vistas.mail.send_agenda_mail');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Send Agenda Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            // view: 'view.name',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
