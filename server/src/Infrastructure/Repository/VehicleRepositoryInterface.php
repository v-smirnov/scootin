<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Vehicle;
use Doctrine\ORM\Exception\ORMException;
use LongitudeOne\Spatial\PHP\Types\Geometry\Point;

interface VehicleRepositoryInterface
{
    public function findByIdentifier(string $identifier): ?Vehicle;

    /**
     * @return Vehicle[]
     */
    public function findByTypeAndStatusInArea(string $type, string $status, Point $startPoint, Point $endPoint): array;

    /**
     * @throws ORMException
     */
    public function save(Vehicle $vehicle): void;
}