<?php

namespace App\Dto;
use Symfony\Component\Validator\Constraints as Assert;
class RegistrationDto
{
    public function __construct(

        #[Assert\NotBlank]
        #[Assert\Type('string')]
        public readonly string $name,

        #[Assert\NotBlank]
        #[Assert\Type('string')]
        #[Assert\Email]
        public readonly string $email,

        #[Assert\NotBlank]
        #[Assert\Type('string')]
        public readonly string $password,
    ) {
    }
}