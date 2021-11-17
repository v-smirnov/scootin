<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Vehicle;
use App\Domain\Entity\VehicleLocation;
use Doctrine\ORM\Exception\ORMException;

interface VehicleLocationRepositoryInterface
{
    public function findByVehicle(Vehicle $vehicle): ?VehicleLocation;

    /**
     * @throws ORMException
     */
    public function save(VehicleLocation $location);
}