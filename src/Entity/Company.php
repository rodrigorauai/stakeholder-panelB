<?php

namespace App\Entity;

use App\Form\CompanyData;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CompanyRepository")
 */
class Company extends Entity
{
    /**
     * @ORM\Column(type="string", length=14)
     */
    private $cnpj;

    /**
     * @var Person[]|Collection
     * @ORM\ManyToMany(targetEntity="Person", inversedBy="companies", cascade={"persist"})
     */
    private $managers;

    public function __construct(string $name, string $cnpj)
    {
        parent::__construct($name);

        $this->cnpj = $cnpj;

        $this->managers = new ArrayCollection();
    }

    public static function fromDataObject(CompanyData $data)
    {
        $company = new Company(
            $data->name,
            preg_replace('/[^\d]/', '', $data->cnpj)
        );

        $company->addManager($data->manager);

        return $company;
    }

    public function updateFromDataObject(CompanyData $data)
    {
        $this->setName($data->name);
        $this->setCnpj($data->cnpj);

        if (false === $this->getManagers()->contains($data->manager)) {
            $this->getManagers()->remove(0);
            $this->addManager($data->manager);
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCnpj(): ?string
    {
        return $this->cnpj;
    }

    public function setCnpj(string $cnpj)
    {
        $this->cnpj = $cnpj;
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

    public function getDocumentNumber(): string
    {
        return $this->cnpj;
    }
}
