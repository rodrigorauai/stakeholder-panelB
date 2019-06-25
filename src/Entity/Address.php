<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AddressEntityRepository")
 */
class Address
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Entity
     * @ORM\OneToOne(targetEntity="Entity", inversedBy="address")
     */
    private $entity;

    /**
     * @ORM\Column(type="string", length=32)
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/^\d{8}$/", message="CEP inválido.")
     */
    private $postalCode;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(max="255", maxMessage="O logradouro não pode ter mais de 255 caracteres.")
     */
    private $street;

    /**
     * @ORM\Column(type="string", length=15)
     * @Assert\NotNull()
     * @Assert\Length(max="15", maxMessage="O número não pode ter mais de 15 caracteres")
     */
    private $number;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(max="255", maxMessage="O nome do bairro não pode ter mais de 255 caracteres.")
     */
    private $district;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(max="255", maxMessage="A nome da cidade não pode ter mais de 255 caracteres.")
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Choice(choices={"AC", "AL", "AP", "AM", "BA", "CE", "DF", "ES", "GO", "MA", "MT", "MS", "MG", "PA",
     *                         "PB", "PR", "PE", "PI", "RJ", "RN", "RS", "RO", "RR", "SC", "SP", "SE", "TO"},
     *                message="Estado inválido")
     */
    private $state;

    /**
     *
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(max="255", maxMessage="O nome do país não pode ter mais de 255 caracteres")
     * @Assert\Choice(choices={"Brazil"})
     */
    private $country;

    /**
     * Address constructor.
     * @param $postalCode
     * @param $street
     * @param $number
     * @param $district
     * @param $city
     * @param $state
     * @param $country
     */
    public function __construct($postalCode, $street, $number, $district, $city, $state, $country)
    {
        $this->postalCode = $postalCode;
        $this->street = $street;
        $this->number = $number;
        $this->district = $district;
        $this->city = $city;
        $this->state = $state;
        $this->country = $country;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param Person $entity
     * @throws Exception
     */
    public function setEntity(Person $entity)
    {
        if ($this->entity) {
            throw new Exception('This address already belongs to a person');
        }

        $this->entity = $entity;
    }

    public function getEntity(): ?Entity
    {
        return $this->entity;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

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

    public function getDistrict(): ?string
    {
        return $this->district;
    }

    public function setDistrict(string $district): self
    {
        $this->district = $district;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
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
}
