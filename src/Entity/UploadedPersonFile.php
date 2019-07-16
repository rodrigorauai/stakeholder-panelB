<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UploadedPersonFileRepository")
 */
class UploadedPersonFile extends UploadedFile
{
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Person", inversedBy="files")
     */
    private $person;

    public function __construct(string $name, string $path, Person $person, Person $uploader)
    {
        parent::__construct($path, $uploader);

        $this->name = $name;
        $this->person = $person;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPerson(): ?Person
    {
        return $this->person;
    }

    public function setPerson(?Person $person): self
    {
        $this->person = $person;

        return $this;
    }
}
