<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DigitizedContractRepository")
 */
class UploadedDigitizedContractFile extends UploadedFile
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Contract", inversedBy="digitizedContracts")
     */
    private $contract;

    public function __construct(string $path, Contract $contract, Person $uploader)
    {
        parent::__construct($path, $uploader);

        $this->contract = $contract;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContract(): ?Contract
    {
        return $this->contract;
    }

    public function setContract(?Contract $contract): self
    {
        $this->contract = $contract;

        return $this;
    }
}
