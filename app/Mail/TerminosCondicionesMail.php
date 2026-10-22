<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Correo de bienvenida que recibe un usuario recién registrado. NO incluye sus
 * datos de acceso: solo un enlace a la página de términos y condiciones. Ahí, al
 * aceptarlos, el usuario ve su correo, rol y contraseña.
 */
class TerminosCondicionesMail extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;
    public $urlAceptar;

    public function __construct(User $usuario, string $urlAceptar)
    {
        $this->usuario = $usuario;
        $this->urlAceptar = $urlAceptar;
    }

    public function build()
    {
        return $this->subject('Bienvenido a Librería JILS — revisa y acepta los términos y condiciones')
            ->view('emails.terminos_condiciones');
    }
}
