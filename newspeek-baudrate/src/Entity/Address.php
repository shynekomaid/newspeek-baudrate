<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Service;


#[ORM\Entity(repositoryClass: AddressRepository::class)]
#[ORM\Table(name: "addresses")]
class Address
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "bigint")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "addresses")]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private User $user;

    #[ORM\Column(type: "text")]
    private string $address;

    #[ORM\Column(type: "string", length: 20)]
    private string $status;

    #[ORM\Column(type: "string", length: 255)]
    private string $tariff;

    #[ORM\Column(type: "decimal", precision: 10, scale: 2)]
    private string $balance;

    #[ORM\Column(type: "datetime")]
    private \DateTime $createdAt;

    #[ORM\Column(type: "datetime")]
    private \DateTime $updatedAt;

    #[ORM\OneToMany(mappedBy: "address", targetEntity: Service::class, cascade: ["persist", "remove"])]
    private Collection $services;


    /**
     * Constructor.
     *
     * Initializes services collection and sets createdAt and updatedAt timestamps.
     */
    public function __construct()
    {
        $this->services = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    // getters and setters
}
