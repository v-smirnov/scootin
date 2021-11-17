<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Vehicle;
use App\Domain\Entity\VehicleLocation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use LongitudeOne\Spatial\PHP\Types\Geometry\Point;

class VehicleRepository extends ServiceEntityRepository implements VehicleRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vehicle::class);
    }

    public function findByIdentifier(string $identifier): ?Vehicle
    {
        $queryBuilder = $this->createQueryBuilder('v');

        $queryBuilder
            ->andWhere('v.identifier = :identifier')
            ->setParameter('identifier', $identifier);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    public function findByTypeAndStatusInArea(string $type, string $status, Point $startPoint, Point $endPoint): array
    {
        $queryBuilder = $this->createQueryBuilder('v');

        $queryBuilder
            ->where('v.type = :type')
            ->andWhere('v.status = :status')
            ->andWhere('st_within(vl.location, st_geomfromtext(:area)) = true')
            ->innerJoin(VehicleLocation::class, 'vl', Join::WITH, 'vl.vehicle = v.id')
            ->setParameter('type', $type)
            ->setParameter('status', $status)
            ->setParameter(
                'area',
                sprintf(
                    "polygon((%f %f, %f %f, %f %f, %f %f, %f %f))",
                    $startPoint->getX(), $startPoint->getY(),
                    $endPoint->getX(), $startPoint->getY(),
                    $endPoint->getX(), $endPoint->getY(),
                    $startPoint->getX(), $endPoint->getY(),
                    $startPoint->getX(), $startPoint->getY(),
                )
            )
        ;

        return $queryBuilder->getQuery()->getResult();
    }

    public function save(Vehicle $vehicle): void
    {
        $this->getEntityManager()->persist($vehicle);
        $this->getEntityManager()->flush();
    }
}