<?php

namespace App\Entity;

use App\Repository\PaymentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentRepository::class)]
class Payment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $PaymentId = null;

    #[ORM\Column(length: 255)]
    private ?string $payerId = null;

    #[ORM\Column(length: 255)]
    private ?string $payerEmail = null;

    #[ORM\Column]
    private ?float $amount = null;

    #[ORM\Column(length: 255)]
    private ?string $currency = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $parchasedAt = null;

    #[ORM\Column(length: 255)]
    private ?string $paymentStatut = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPaymentId(): ?string
    {
        return $this->PaymentId;
    }

    public function setPaymentId(string $PaymentId): static
    {
        $this->PaymentId = $PaymentId;

        return $this;
    }

    public function getPayerId(): ?string
    {
        return $this->payerId;
    }

    public function setPayerId(string $payerId): static
    {
        $this->payerId = $payerId;

        return $this;
    }

    public function getPayerEmail(): ?string
    {
        return $this->payerEmail;
    }

    public function setPayerEmail(string $payerEmail): static
    {
        $this->payerEmail = $payerEmail;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): static
    {
        $this->currency = $currency;

        return $this;
    }

    public function getParchasedAt(): ?\DateTimeInterface
    {
        return $this->parchasedAt;
    }

    public function setParchasedAt(\DateTimeInterface $parchasedAt): static
    {
        $this->parchasedAt = $parchasedAt;

        return $this;
    }

    public function getPaymentStatut(): ?string
    {
        return $this->paymentStatut;
    }

    public function setPaymentStatut(string $paymentStatut): static
    {
        $this->paymentStatut = $paymentStatut;

        return $this;
    }
}
