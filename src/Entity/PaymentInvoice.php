<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PaymentInvoiceRepository")
 */
class PaymentInvoice
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @ORM\Column(type="smallint")
     */
    private $status;

    const STATUS_APPROVED = 1;

    const STATUS_WAITING = 0;

    const STATUS_REPROVED = -1;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Person", inversedBy="sentInvoices")
     * @ORM\JoinColumn(nullable=false)
     */
    private $submittor;

    /**
     * @ORM\Column(type="datetimetz_immutable")
     */
    private $dateSent;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Person", inversedBy="revisedInvoices")
     */
    private $revisor;

    /**
     * @ORM\Column(type="datetimetz_immutable", nullable=true)
     */
    private $dateRevised;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Payment", inversedBy="invoices")
     * @ORM\JoinColumn(nullable=false)
     */
    private $payment;

    public function __construct()
    {
        $this->dateSent = new DateTimeImmutable();
        $this->status = self::STATUS_WAITING;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getRevisor(): ?Person
    {
        return $this->revisor;
    }

    public function setRevisor(?Person $revisor): self
    {
        $this->revisor = $revisor;

        return $this;
    }

    public function getDateSent(): ?DateTimeImmutable
    {
        return $this->dateSent;
    }

    public function setDateSent(DateTimeImmutable $dateSent): self
    {
        $this->dateSent = $dateSent;

        return $this;
    }

    public function getDateRevised(): ?DateTimeImmutable
    {
        return $this->dateRevised;
    }

    public function setDateRevised(?DateTimeImmutable $dateRevised): self
    {
        $this->dateRevised = $dateRevised;

        return $this;
    }

    public function getPayment(): ?Payment
    {
        return $this->payment;
    }

    public function setPayment(?Payment $payment): self
    {
        $this->payment = $payment;

        return $this;
    }

    public function getSubmittor(): ?Person
    {
        return $this->submittor;
    }

    public function setSubmittor(?Person $submittor): self
    {
        $this->submittor = $submittor;

        return $this;
    }
}
