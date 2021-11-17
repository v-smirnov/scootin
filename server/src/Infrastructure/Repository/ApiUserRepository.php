<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\ApiUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ApiUserRepository extends ServiceEntityRepository implements ApiUserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApiUser::class);
    }

    public function findByApiKey(string $apiKey): ?ApiUser
    {
        $queryBuilder = $this->createQueryBuilder('u');

        $queryBuilder
            ->andWhere('u.apiKey = :apiKey')
            ->setParameter('apiKey', $apiKey);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
}