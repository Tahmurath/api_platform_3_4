<?php
// api/src/Entity/User.php

namespace App\Entity;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use App\State\UserPasswordHasher;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new Get(), //@todo user & admin only can see their company, supre admin can see all
        new GetCollection(),

        //@todo #[Post(security: "is_granted('ROLE_SUPER_ADMIN','ROLE_COMPANY_ADMIN')")]
        new Post(processor: UserPasswordHasher::class, validationContext: ['groups' => ['Default', 'user:create']]),

        //new Put(processor: UserPasswordHasher::class),
        //new Patch(processor: UserPasswordHasher::class),
        //@todo #[Post(security: "is_granted('ROLE_SUPER_ADMIN')")]
        new Delete(),
    ],
    normalizationContext: ['groups' => ['user:read']],
    denormalizationContext: ['groups' => ['user:create', 'user:update']],
)]
//#[ApiResource(
//    operations: [
//        new Get(
//            security: "is_granted('USER_VIEW', object)", // Will use the voter for VIEW
//            securityMessage: 'Sorry, but you are not the owner.'
//        ),
//        new GetCollection(
//            security: "is_granted('USER_VIEW', object)", // Will use the voter for VIEW
//            securityMessage: 'Sorry, but you are not the owner.'
//        ),
//        new Post(
//            security: "is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_COMPANY_ADMIN')", // available for SUPERADMIN and COMPANY ADMIN
//            securityMessage: 'Sorry, but you are not the owner.',
//            validationContext: ['groups' => ['Default', 'user:create']], // Will use the voter for VIEW
//            processor: UserPasswordHasher::class
//        ),
//        new Put(processor: UserPasswordHasher::class),
//        new Patch(processor: UserPasswordHasher::class),
//        new Delete(
//            security: "is_granted('ROLE_SUPER_ADMIN')", // available for SUPERADMIN only
//            securityMessage: 'Sorry, but you are not the owner.'
//        ),
//    ],
//    normalizationContext: ['groups' => ['user:read']],
//    denormalizationContext: ['groups' => ['user:create', 'user:update']],
//)]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity('email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[Groups(['user:read'])]
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Email]
    #[Groups(['user:read', 'user:create', 'user:update'])]
    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    #[Assert\NotBlank(groups: ['user:create'])]
    #[Groups(['user:create', 'user:update'])]
    private ?string $password = null;

    #[Assert\NotBlank(groups: ['user:create'])]
    #[Groups(['user:create', 'user:update'])]
    private ?string $plainPassword = null;


    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank]
    #[Assert\Choice(choices: ['ROLE_USER', 'ROLE_COMPANY_ADMIN', 'ROLE_SUPER_ADMIN'])]
    #[Groups(['user:create'])]
    public string $role;

    #[ORM\Column(type: 'string', length: 100)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 100)]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z\s]*$/',
        message: 'Name should contain only letters and spaces.'
    )]
    #[Assert\Regex(
        pattern: '/[A-Z]/',
        message: 'Name must contain at least one uppercase letter.'
    )]
    #[Groups(['user:read', 'user:create', 'user:update'])]
    private string $name;


    #[ORM\ManyToOne(targetEntity: Company::class)]
    #[ORM\JoinColumn(nullable: false)]
//    #[ApiProperty(writable: true)]
    #[Groups(['user:read', 'user:create', 'user:update'])]
    private ?Company $company = null;

    public function __construct(
        string $name,
        string $role,
        ?Company $company = null
    ) {
        $this->name = $name;
        $this->role = $role;
        $this->company = $company;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return [$this->role];
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }
}
