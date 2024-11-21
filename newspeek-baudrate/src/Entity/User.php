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

    /**
     * Retrieves the ID of the user.
     *
     * @return int|null The ID of the user, or null if not set.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retrieves the username for the user.
     *
     * @return string The username for the user.
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Sets the username for the user.
     *
     * @param string $username The username to set.
     *
     * @return self Returns the instance of the User entity for method chaining.
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    /**
     * Gets the password for the user.
     *
     * @return string The user's password.
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

    /**
     * Returns the phone number for the user.
     *
     * @return string The phone number for the user.
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * Sets the phone number for the user.
     *
     * @param string $phone The phone number to set.
     *
     * @return self Returns the instance of the User entity for method chaining.
     */
    public function setPhone(string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * Returns the email for the user. The email is a string that represents
     * the email of the user.
     *
     * @return string The email for the user.
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Sets the email for the user.
     *
     * @param string $email The email for the user.
     *
     * @return $this The user object.
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Returns the language for the user. The language is a string that represents
     * the locale that the user will see when they log in.
     *
     * @return string The language for the user.
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * Sets the language for the user. The language is a string that represents
     * the locale that the user will see when they log in.
     *
     * @param string $language
     * @return $this
     */
    public function setLanguage(string $language): self
    {
        $this->language = $language;
        return $this;
    }

    /**
     * Returns the theme for the user. The theme is a string that represents the
     * visual theme that the user will see when they log in. The theme must be
     * one of the following values: 'light', 'dark', 'system'.
     *
     * @return string The theme for the user.
     */
    public function getTheme(): string
    {
        return $this->theme;
    }

    /**
     * Sets the theme for the user. The theme is a string that represents the
     * visual theme that the user will see when they log in. The theme must be
     * one of the following values: 'light', 'dark', 'system'.
     *
     * @param string $theme The theme to set, one of 'light', 'dark', or 'system'.
     *
     * @return self Returns the instance of the User entity for method chaining.
     */
    public function setTheme(string $theme): self
    {
        if (!in_array($theme, ['light', 'dark', 'system'])) {
            throw new \InvalidArgumentException("Invalid theme value: $theme");
        }
        $this->theme = $theme;
        return $this;
    }

    /**
     * Returns the device ID of the user, or null if no device ID is available.
     *
     * @return string|null The device ID of the user, or null if no device ID is available.
     */
    public function getDeviceId(): ?string
    {
        return $this->deviceId;
    }

    /**
     * Sets the device ID for the user.
     *
     * @param string|null $deviceId The device ID to set, or null if no device ID is available.
     *
     * @return self Returns the instance of the User entity for method chaining.
     */
    public function setDeviceId(?string $deviceId): self
    {
        $this->deviceId = $deviceId;
        return $this;
    }

    /**
     * Retrieves the creation date and time of the user.
     *
     * @return \DateTime The creation date and time.
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }


    /**
     * Retrieves the last update date and time of the user.
     *
     * @return \DateTime The last update date and time.
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * Retrieves the collection of addresses associated with the user.
     *
     * @return Collection The collection of address entities.
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    /**
     * Sets the collection of addresses associated with the user.
     *
     * @param Collection $addresses The collection of address entities to set.
     *
     * @return self Returns the instance of the User entity for method chaining.
     */
    public function setAddresses(Collection $addresses): self
    {
        $this->addresses = $addresses;
        return $this;
    }
}
