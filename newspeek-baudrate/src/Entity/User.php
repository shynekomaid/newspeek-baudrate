<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: "users")]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "bigint")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 255, unique: true)]
    private string $username;

    #[ORM\Column(type: "string", length: 255)]
    private string $password;

    #[ORM\Column(type: "string", length: 20)]
    private string $phone;

    #[ORM\Column(type: "string", length: 255, unique: true)]
    private string $email;

    #[ORM\Column(type: "string", length: 5, options: ["default" => "uk"])]
    private string $language = 'uk';

    #[ORM\Column(type: "string", length: 20, options: ["default" => "light"])]
    private string $theme = 'light';

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $deviceId = null;

    #[ORM\Column(type: "datetime")]
    private \DateTime $createdAt;

    #[ORM\Column(type: "datetime")]
    private \DateTime $updatedAt;

    #[ORM\OneToMany(mappedBy: "user", targetEntity: Address::class, cascade: ["persist", "remove"])]
    private Collection $addresses;

    /**
     * @param Address[]|Collection $addresses
     */
    public function __construct()
    {
        $this->addresses = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    // getters and setters
}
