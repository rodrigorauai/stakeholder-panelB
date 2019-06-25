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
class Person extends AbstractEntity implements UserInterface
{
    /**
     * @var null|Company
     * @ORM\ManyToOne(targetEntity="Company", inversedBy="managers")
     * @ORM\JoinColumn(nullable=true)
     */
    private $company;

    /**
     * @var string
     * @ORM\Column(type="string", length=11, unique=true, nullable=true)
     */
    private $cpf;

    /**
     * @var string
     * @ORM\Column(type="string", length=32, unique=true, nullable=true)
     */
    private $rg;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, unique=true, nullable=true)
     */
    private $email;

    /**
     * @var Phone[]
     * @ORM\OneToMany(targetEntity="Phone", mappedBy="person")
     */
    private $phones;

    /**
     * @var Address
     * @ORM\OneToOne(targetEntity="Address", mappedBy="person")
     */
    private $address;

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
        return new Person($data->name, $data->email);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Company|null
     */
    public function getCompany(): ?Company
    {
        return $this->company;
    }

    /**
     * @param Company|null $company
     */
    public function setCompany(?Company $company)
    {
        $this->company = $company;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getCpf(): ?string
    {
        return $this->cpf;
    }

    public function setCpf(string $cpf): self
    {
        $this->cpf = $cpf;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getRg(): ?string
    {
        return $this->rg;
    }

    /**
     * @param null|string $rg
     */
    public function setRg(?string $rg)
    {
        $this->rg = $rg;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return Phone[]|Collection
     */
    public function getPhones()
    {
        return $this->phones;
    }

    /**
     * @param Phone[] $phones
     */
    public function setPhones(array $phones)
    {
        $this->phones = $phones;
    }

    /**
     * @return Address
     */
    public function getAddress(): Address
    {
        return $this->address;
    }

    /**
     * @param Address $address
     */
    public function setAddress(Address $address)
    {
        $this->address = $address;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->cpf;
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
