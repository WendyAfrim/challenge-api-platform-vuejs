<?php

namespace App\Exception;

use Exception;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;

class EmailNotVerifiedException extends Exception
{
    private string $messageKey;
    private array $messageData = [];

    public function __construct()
    {
        $message = 'Pour continuer, veuillez confirmer votre adresse email en cliquant sur le lien envoyé lors de votre inscription.';
        $messageData = [];
        parent::__construct($message);

        $this->setSafeMessage($message, $messageData);
    }

    public function setSafeMessage(string $messageKey, array $messageData = [])
    {
        $this->messageKey = $messageKey;
        $this->messageData = $messageData;
    }

    public function getMessageKey(): string
    {
        return $this->messageKey;
    }

    public function getMessageData(): array
    {
        return $this->messageData;
    }

    public function __serialize(): array
    {
        return [parent::__serialize(), $this->messageKey, $this->messageData];
    }

    public function __unserialize(array $data): void
    {
        [$parentData, $this->messageKey, $this->messageData] = $data;
        parent::__unserialize($parentData);
    }
}
