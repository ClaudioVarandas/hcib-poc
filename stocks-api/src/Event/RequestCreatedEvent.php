<?php

namespace App\Event;

use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\EventDispatcher\Event;

class RequestCreatedEvent extends Event
{
    public const NAME = 'request.created';

    public function __construct(
        private readonly UserInterface $user,
        private readonly string $type,
        private readonly array $data
    )
    {
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }
}