<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UploadedFileRepository")
 * @ORM\InheritanceType(value="SINGLE_TABLE")
 */
class UploadedFile
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
    private $path;

    /**
     * @var Person
     * @ORM\ManyToOne(targetEntity="Person")
     * @ORM\JoinColumn(nullable=false)
     */
    private $uploader;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $uploadTimestamp;

    public function __construct(string $path, Person $uploader)
    {
        $this->setPath($path);
        $this->uploader = $uploader;
        $this->uploadTimestamp = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return Person
     */
    public function getUploader(): Person
    {
        return $this->uploader;
    }

    /**
     * @return DateTime
     */
    public function getUploadTimestamp(): DateTime
    {
        return $this->uploadTimestamp;
    }
}
