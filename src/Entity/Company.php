<?php

namespace App\Entity;

use App\Form\CompanyData;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CompanyRepository")
 */
class Company
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
    private $name;

    /**
     * @ORM\Column(type="string", length=14)
     */
    private $cnpj;

    /**
     * @var Person[]|Collection
     * @ORM\OneToMany(targetEntity="Person", mappedBy="company")
     */
    private $managers;

    public function __construct(string $name, string $cnpj)
    {
        $this->name = $name;
        $this->cnpj = $cnpj;

        $this->managers = new ArrayCollection();
    }

    public static function fromDataObject(CompanyData $data)
    {
        return new Company(
            $data->name,
            preg_replace('/[^\d]/', '', $data->cnpj)
        );
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCnpj(): ?string
    {
        return $this->cnpj;
    }

    public function setCnpj(string $cnpj): self
    {
        $this->cnpj = $cnpj;

        return $this;
    }

    public function addManager(Person $manager)
    {
        $this->managers->add($manager);
    }

    /**
     * @return Person[]|ArrayCollection|Collection
     */
    public function getManagers()
    {
        return $this->managers;
    }
}
