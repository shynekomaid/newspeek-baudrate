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

    /**
     * Retrieves the ID of the address.
     *
     * @return int|null The ID of the address, or null if not set.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retrieves the user associated with this address.
     *
     * @return User The user associated with this address.
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * Sets the user associated with this address.
     *
     * @param User $user The user associated with this address.
     *
     * @return self Returns the instance of the Address entity for method chaining.
     */
    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Retrieves the address for the user.
     *
     * @return string The address of the user as a string.
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * Sets the address.
     *
     * @param string $address The address to set, a string that represents the
     *                        address of the user.
     *
     * @return self Returns the instance of the Address entity for method chaining.
     */
    public function setAddress(string $address): self
    {
        $this->address = $address;
        return $this;
    }

    /**
     * Retrieves the status of the address.
     *
     * @return string The current status of the address.
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Sets the status for the address.
     *
     * @param string $status The status to set, a string that represents the
     *                       current status of the address.
     *
     * @return self Returns the instance of the Address entity for method chaining.
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Returns the tariff for the address. The tariff is a string that represents
     * the tariff that the user is currently using.
     *
     * @return string The tariff for the address.
     */
    public function getTariff(): string
    {
        return $this->tariff;
    }

    /**
     * Sets the tariff for the address. The tariff is a string that represents
     * the tariff that the user is currently using.
     *
     * @param string $tariff The tariff to set, a string that represents the
     *                       tariff. The tariff must be one of the following
     *                       values: 'month', 'year'.
     *
     * @return self Returns the instance of the Address entity for method
     *              chaining.
     */
    public function setTariff(string $tariff): self
    {
        $this->tariff = $tariff;
        return $this;
    }

    /**
     * Retrieves the balance for the address.
     *
     * @return string The balance as a string representing a number.
     */
    public function getBalance(): float
    {
        return (float) $this->balance;
    }

    /**
     * Sets the balance for the address.
     *
     * @param string $balance The balance to set, a string that represents a number.
     *
     * @return self Returns the instance of the Address entity for method chaining.
     */
    public function setBalance(string $balance): self
    {
        $this->balance = $balance;
        return $this;
    }

    /**
     * Retrieves the creation date and time of the address.
     *
     * @return \DateTime The creation date and time.
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * Retrieves the last update date and time of the address.
     *
     * @return \DateTime The last update date and time.
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * Retrieves the collection of services associated with the address.
     *
     * @return Collection The collection of service entities associated with the address.
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    /**
     * Adds a service to the address if it is not already present.
     *
     * @param Service $service The service to add.
     *
     * @return self Returns the instance of the Address entity for method chaining.
     */
    public function addService(Service $service): self
    {
        if (!$this->services->contains($service)) {
            $this->services[] = $service;
            $service->setAddress($this);
        }
        return $this;
    }

    /**
     * Removes a service from the collection of services associated with the address.
     *
     * If the service is found in the collection, it is removed from the collection
     * and the service's address is set to null. If the service is not found,
     * the method does nothing.
     *
     * @param Service $service The service to remove from the collection.
     *
     * @return self Returns the instance of the Address entity for method chaining.
     */
    public function removeService(Service $service): self
    {
        if ($this->services->contains($service)) {
            $this->services->removeElement($service);
            if ($service->getAddress() === $this) {
                $service->setAddress(null);
            }
        }
        return $this;
    }
}
