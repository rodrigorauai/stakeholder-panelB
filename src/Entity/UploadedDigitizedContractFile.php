<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DigitizedContractRepository")
 */
class UploadedDigitizedContractFile extends UploadedFile
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Contract", inversedBy="digitizedContracts")
     */
    private $contract;

    public function __construct(string $path, Contract $contract, Person $uploader)
    {
        parent::__construct($path, $uploader);

        $this->contract = $contract;
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
