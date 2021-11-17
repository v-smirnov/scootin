<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Vehicle;
use App\Domain\Entity\VehicleLocation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class VehicleLocationRepository extends ServiceEntityRepository implements VehicleLocationRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VehicleLocation::class);
    }

    public function findByVehicle(Vehicle $vehicle): ?VehicleLocation
    {
        $queryBuilder = $this->createQueryBuilder('vl');

        $queryBuilder
            ->andWhere('vl.vehicle = :vehicle')
            ->setParameter('vehicle', $vehicle);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    public function save(VehicleLocation $location)
    {
        $this->getEntityManager()->persist($location);
        $this->getEntityManager()->flush();
    }
}