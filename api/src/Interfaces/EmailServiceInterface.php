<?php

namespace App\Interfaces;

use Symfony\Component\Mime\Address;

interface EmailServiceInterface
{
    public function send(Address $from, string $to, string $subject, string $template);
}
