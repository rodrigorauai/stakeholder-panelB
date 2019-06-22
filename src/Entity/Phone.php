<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Exception;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhoneEntityRepository")
 */
class Phone
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Person
     * @ORM\ManyToOne(targetEntity="App\Entity\Person", inversedBy="phones")
     */
    private $person;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $number;

    /**
     * Phone constructor.
     * @param $country
     * @param $number
     */
    public function __construct($country, $number)
    {
        $this->country = $country;
        $this->number = $number;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Person
     */
    public function getPerson(): Person
    {
        return $this->person;
    }

    /**
     * @param Person $person
     * @throws Exception
     */
    public function setPerson(Person $person)
    {
        if ($this->person) {
            throw new Exception('This phone already belongs to a person');
        }

        $this->person = $person;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }
}
