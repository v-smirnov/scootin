<?php

namespace App\Domain\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

use Doctrine\ORM\Mapping as ORM;
use App\Infrastructure\Repository\ApiUserRepository;
use DateTimeInterface;

/**
 * @ORM\Entity(repositoryClass=ApiUserRepository::class)
 */
class ApiUser implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private string $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=false, name="api_key")
     */
    private string $apiKey;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     */
    private DateTimeInterface $createdAt;

    /**
     * @ORM\Column(type="datetime", name="updated_at")
     */
    private DateTimeInterface $updatedAt;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return ApiUser
     */
    public function setId(int $id): ApiUser
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return ApiUser
     */
    public function setDescription(string $description): ApiUser
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     * @return ApiUser
     */
    public function setApiKey(string $apiKey): ApiUser
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeInterface $createdAt
     * @return ApiUser
     */
    public function setCreatedAt(DateTimeInterface $createdAt): ApiUser
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return DateTimeInterface
     */
    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTimeInterface $updatedAt
     * @return ApiUser
     */
    public function setUpdatedAt(DateTimeInterface $updatedAt): ApiUser
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getRoles()
    {
        return [];
    }

    public function getPassword()
    {
        return '';
    }

    public function getSalt()
    {
        return '';
    }

    public function eraseCredentials()
    {
        return null;
    }

    public function getUsername()
    {
        return $this->description;
    }
}