<?php
// api/src/Entity/Company.php
namespace App\Entity;

use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;




#[ORM\Entity]
//#[Post(security: "is_granted('ROLE_SUPER_ADMIN')")]
#[ApiResource]
//#[ApiResource(
//    operations: [
//        new Get(
//            security: "is_granted(ROLE_USER)", // available for all roles
//            securityMessage: 'Sorry, but you are not the owner.'
//        ),
//        new Post(
//            security: "is_granted('ROLE_SUPER_ADMIN')", // available for SUPERADMIN and COMPANY ADMIN
//            securityMessage: 'Sorry, but you are not the owner.',),
//        new GetCollection()
//    ]
//)]

class Company
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 100, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 5, max: 100)]
    public string $name;


    /** @var User[] */
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'company', cascade: ['persist', 'remove'])]
    public iterable $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
}
