<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CredencialesUsuarioMail extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;
    public $passwordPlano;
    public $rolNombre;

    public function __construct(User $usuario, string $passwordPlano, string $rolNombre)
    {
        $this->usuario = $usuario;
        $this->passwordPlano = $passwordPlano;
        $this->rolNombre = $rolNombre;
    }

    public function build()
    {
        return $this->subject('Bienvenido a Librería JILS — tus datos de acceso')
            ->view('emails.credenciales_usuario');
    }
}
