<?php

namespace App\Entity;

use App\Repository\HistoryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: HistoryRepository::class)]
class History
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'history')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    #[Groups('list_history')]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(length: 255)]
    #[Groups('list_history')]
    private ?string $symbol = null;

    #[ORM\Column(length: 255)]
    #[Groups('list_history')]
    private ?string $open = null;

    #[ORM\Column(length: 255)]
    #[Groups('list_history')]
    private ?string $high = null;

    #[ORM\Column(length: 255)]
    #[Groups('list_history')]
    private ?string $low = null;

    #[ORM\Column(length: 255)]
    #[Groups('list_history')]
    private ?string $close = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getSymbol(): ?string
    {
        return $this->symbol;
    }

    public function setSymbol(string $symbol): static
    {
        $this->symbol = $symbol;

        return $this;
    }

    public function getOpen(): ?string
    {
        return $this->open;
    }

    public function setOpen(string $open): static
    {
        $this->open = $open;

        return $this;
    }

    public function getHigh(): ?string
    {
        return $this->high;
    }

    public function setHigh(string $high): static
    {
        $this->high = $high;

        return $this;
    }

    public function getLow(): ?string
    {
        return $this->low;
    }

    public function setLow(string $low): static
    {
        $this->low = $low;

        return $this;
    }

    public function getClose(): ?string
    {
        return $this->close;
    }

    public function setClose(string $close): static
    {
        $this->close = $close;

        return $this;
    }
}
