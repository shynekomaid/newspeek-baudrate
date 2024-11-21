<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServiceRepository::class)]
#[ORM\Table(name: "services")]
class Service
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "bigint")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Address::class, inversedBy: "services")]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private Address $address;

    #[ORM\Column(type: "string", length: 50)]
    private string $type;

    #[ORM\Column(type: "string", length: 255)]
    private string $value;

    #[ORM\Column(type: "datetime")]
    private \DateTime $createdAt;

    #[ORM\Column(type: "datetime")]
    private \DateTime $updatedAt;

    /**
     * Constructor.
     *
     * Initializes createdAt and updatedAt timestamps.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /**
     * Retrieves the ID of the service, or null if no ID has been set.
     *
     * @return int|null The ID of the service, or null if no ID has been set.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retrieves the address associated with this service.
     *
     * @return Address|null The address associated with the service, or null if
     *                      no address is associated.
     */
    public function getAddress(): ?Address
    {
        return $this->address;
    }

    /**
     * Sets the address associated with the service.
     *
     * @param Address|null $address The address to associate with the service,
     *                              or null if no address is to be associated.
     *
     * @return self Returns the instance of the Service entity for method chaining.
     */
    public function setAddress(?Address $address): self
    {
        $this->address = $address;
        return $this;
    }

    /**
     * Returns the type of the service. The type is a string that represents
     * the category or kind of service being provided.
     *
     * @return string The type of the service.
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Sets the type of the service. The type is a string that represents
     * the category or kind of service being provided.
     *
     * @param string $type The type to set, a string that represents the service type.
     *
     * @return self Returns the instance of the Service entity for method chaining.
     */
    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Returns the value of the service. The value is a string that represents
     * the value of the service.
     *
     * @return string The value of the service.
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Sets the value of the service. The value is a string that represents the
     * value of the service.
     *
     * @param string $value The value to set, a string that represents the value
     *                      of the service.
     *
     * @return self Returns the instance of the Service entity for method chaining.
     */
    public function setValue(string $value): self
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Retrieves the creation date and time of the service.
     *
     * @return \DateTime The creation date and time.
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * Retrieves the last update date and time of the service.
     *
     * @return \DateTime The last update date and time.
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }
}
