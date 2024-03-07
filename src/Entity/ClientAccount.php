<?php

namespace App\Entity;

use App\Repository\ClientAccountRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientAccountRepository::class)]
class ClientAccount
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 3)]
    private string $currency;

    #[ORM\Column(type: 'float')]
    private float $balance;

    #[ORM\ManyToOne(targetEntity: Client::class)]
    private Client $client;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;
        return $this;
    }

    public function setBalance(float $balance): self
    {
        $this->balance = $balance;
        return $this;
    }

    public function setClient(Client $client): self
    {
        $this->client = $client;
        return $this;
    }
}
