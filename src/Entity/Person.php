<?php

namespace App\Entity;

use App\Form\PersonData;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PersonRepository")
 * @ORM\InheritanceType(value="SINGLE_TABLE")
 */
class Person extends Entity implements UserInterface
{
    /**
     * @var null|Company[]
     * @ORM\ManyToMany(targetEntity="Company", mappedBy="managers")
     */
    private $companies;

    /**
     * @var null|string
     * @ORM\Column(type="string", length=11, unique=true, nullable=true)
     */
    private $cpf;

    /**
     * @var null|string
     * @ORM\Column(type="string", length=32, unique=true, nullable=true)
     */
    private $rg;

    /**
     * @var null|string
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
     */
    private $email;

    /**
     * @var null|string
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @var Withdraw[]|Collection
     * @ORM\OneToMany(targetEntity="Withdraw", mappedBy="requester")
     */
    private $withdrawRequests;

    /**
     * Person constructor.
     * @param string $name
     * @param string $email
     */
    public function __construct(string $name, string $email)
    {
        parent::__construct($name);

        $this->email = $email;
    }

    public static function fromDataObject(PersonData $data)
    {
        $person = new Person($data->name, $data->email);

        if ($data->cpf) {
            $person->setCpf($data->cpf);
        }

        if ($data->rg) {
            $person->setRg($data->rg);
        }

        if ($data->phone) {
            $person->setPhone($data->phone);
        }

        if ($data->tradeRepresentative) {
            $person->setTradeRepresentative($data->tradeRepresentative);
        }

        return $person;
    }

    public function updateFromDataObject(PersonData $data)
    {
        $this->setName($data->name);
        $this->setCpf($data->cpf);
        $this->setRg($data->rg);
        $this->setEmail($data->email);
        $this->setPhone($data->phone);
        $this->setTradeRepresentative($data->tradeRepresentative);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Company[]|null
     */
    public function getCompanies()
    {
        return $this->companies;
    }

    public function setCompanies(?Company $companies)
    {
        $this->companies = $companies;
    }

    public function getCpf(): ?string
    {
        return $this->cpf;
    }

    public function setCpf(?string $cpf): self
    {
        $this->cpf = $cpf;

        return $this;
    }

    public function getRg(): ?string
    {
        return $this->rg;
    }

    public function setRg(?string $rg)
    {
        $this->rg = $rg;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email)
    {
        $this->email = $email;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone)
    {
        $this->phone = $phone;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getDocumentNumber(): string
    {
        return $this->cpf;
    }

    /**
     * @return Withdraw[]|Collection
     */
    public function getWithdrawRequests()
    {
        return $this->withdrawRequests;
    }
}
