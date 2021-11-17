<?php

namespace App\Domain\Entity;

use App\Infrastructure\Repository\VehicleLocationRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use LongitudeOne\Spatial\PHP\Types\Geometry\Point;

/**
 * @ORM\Entity(repositoryClass=VehicleLocationRepository::class)
 */
class VehicleLocation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\OneToOne(targetEntity=Vehicle::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private Vehicle $vehicle;

    /**
     * @ORM\Column(type="point")
     */
    private Point $location;

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
     * @return VehicleLocation
     */
    public function setId(int $id): VehicleLocation
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Vehicle
     */
    public function getVehicle(): Vehicle
    {
        return $this->vehicle;
    }

    /**
     * @param Vehicle $vehicle
     * @return VehicleLocation
     */
    public function setVehicle(Vehicle $vehicle): VehicleLocation
    {
        $this->vehicle = $vehicle;
        return $this;
    }

    /**
     * @return Point
     */
    public function getLocation(): Point
    {
        return $this->location;
    }

    /**
     * @param Point $location
     * @return VehicleLocation
     */
    public function setLocation(Point $location): VehicleLocation
    {
        $this->location = $location;
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
     * @return VehicleLocation
     */
    public function setCreatedAt(DateTimeInterface $createdAt): VehicleLocation
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
     * @return VehicleLocation
     */
    public function setUpdatedAt(DateTimeInterface $updatedAt): VehicleLocation
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}