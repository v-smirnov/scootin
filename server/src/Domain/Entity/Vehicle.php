<?php

namespace App\Domain\Entity;

use App\Infrastructure\Repository\VehicleRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VehicleRepository::class)
 */
class Vehicle
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
    private string $identifier;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private string $type;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private string $status;

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
     * @return Vehicle
     */
    public function setId(int $id): Vehicle
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     * @return Vehicle
     */
    public function setIdentifier(string $identifier): Vehicle
    {
        $this->identifier = $identifier;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Vehicle
     */
    public function setType(string $type): Vehicle
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return Vehicle
     */
    public function setStatus(string $status): Vehicle
    {
        $this->status = $status;
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
     * @return Vehicle
     */
    public function setCreatedAt(DateTimeInterface $createdAt): Vehicle
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
     * @return Vehicle
     */
    public function setUpdatedAt(DateTimeInterface $updatedAt): Vehicle
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}