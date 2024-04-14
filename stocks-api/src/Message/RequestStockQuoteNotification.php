<?php

namespace App\Message;

class RequestStockQuoteNotification
{
    /**
     * @param string $email
     * @param array $content
     */
    public function __construct(
        private readonly string $email,
        private readonly array $content
    ) {
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return array
     */
    public function getContent(): array
    {
        return $this->content;
    }
}