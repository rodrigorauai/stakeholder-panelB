<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UploadedCompanyFileRepository")
 */
class UploadedCompanyFile extends UploadedFile
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="files")
     */
    private $company;

    public function __construct(string $name, string $path, Company $company, Person $uploader)
    {
        parent::__construct($path, $uploader);

        $this->name = $name;
        $this->company = $company;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }
}
