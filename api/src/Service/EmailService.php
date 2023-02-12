<?php

namespace App\Service;

use App\Interfaces\EmailServiceInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class EmailService implements EmailServiceInterface
{
    public function __construct(
        private readonly MailerInterface $mailer
    )
    {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function send(Address $from, string $to, string $subject, string $template, array $context = [])
    {
        $email = (new TemplatedEmail())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->htmlTemplate($template)
            ->context($context);

        $this->mailer->send($email);
    }
}
