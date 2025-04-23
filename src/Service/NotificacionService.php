<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class NotificacionService
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function enviarCorreo(string $to, string $asunto, string $mensaje): void
    {
        $email = (new Email())
            ->from('no-reply@tudominio.com')
            ->to($to)
            ->subject($asunto)
            ->text($mensaje)
            ->html("<p>$mensaje</p>");

        $this->mailer->send($email);
    }
}
