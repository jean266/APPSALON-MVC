<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email
{
    public $email;
    public $nombre;
    public $token;

    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion() {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = 'cdc89f31a40ce7';
        $mail->Password = 'e7248609b9c569';

        $mail->setFrom('Cuentas@appsalon.com');
        $mail->addAddress('Cuentas@appsalon.com', '<AppSalon.com>');
        $mail->Subject = "Confirma tu Cuenta";

        // Set HTML
        $mail->isHTML();
        $mail->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p>Hola <strong>" . $this->nombre . "</strong> Has tu cuenta en App Salon, solo debes confirmarla precionando el siguiente enlace.</p>";
        $contenido .= "<p>Presiona aquí: <a href='http://localhost:3000//confirmar-cuenta?token=" . $this->token . "' >Confirmar Cuenta</a></p>";
        $contenido .= "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje</p>";
        $contenido .= "</html>";
        $mail->Body = $contenido;

        // Enviar el mail
        $mail->send();
    }

    public function enviarInstrucciones() {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = 'cdc89f31a40ce7';
        $mail->Password = 'e7248609b9c569';

        $mail->setFrom('Cuentas@appsolon.com');
        $mail->addAddress('Cuentas@appsolon.com', '<AppSalon.com>');
        $mail->Subject = "Reestablece tu Cuenta";

        // Set HTML
        $mail->isHTML();
        $mail->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p>Hola <strong>" . $this->nombre . "</strong> has solicitado reestablecer tu password, has click en el siguiete enlace.</p>";
        $contenido .= "<p>Presiona aquí: <a href='https://desolate-waters-33280.herokuapp.com/recuperar?token=" . $this->token . "' >Reestablecer Password</a></p>";
        $contenido .= "<p>Si tu no solicitaste reestablecer tu contraseña, puedes ignorar el mensaje</p>";
        $contenido .= "</html>";
        $mail->Body = $contenido;

        // Enviar el mail
        $mail->send();
    }
}
