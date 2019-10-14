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

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UploadedCompanyFile", mappedBy="company")
     */
    private $files;

    public function __construct(string $name, string $cnpj)
    {
        parent::__construct($name);

        $this->cnpj = $cnpj;

        $this->managers = new ArrayCollection();
        $this->files = new ArrayCollection();
    }

    public static function fromDataObject(CompanyData $data)
    {
        $company = new Company(
            $data->name,
            preg_replace('/[^\d]/', '', $data->cnpj)
        );

        $company->addManager($data->manager);

        $company->setTradeRepresentative($data->tradeRepresentative);

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

        $this->tradeRepresentative = $data->tradeRepresentative;
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

    /**
     * @return Collection|UploadedCompanyFile[]
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function addFile(UploadedCompanyFile $file): self
    {
        if (!$this->files->contains($file)) {
            $this->files[] = $file;
            $file->setCompany($this);
        }

        return $this;
    }

    public function removeFile(UploadedCompanyFile $file): self
    {
        if ($this->files->contains($file)) {
            $this->files->removeElement($file);
            // set the owning side to null (unless already changed)
            if ($file->getCompany() === $this) {
                $file->setCompany(null);
            }
        }

        return $this;
    }
}
