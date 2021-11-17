<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\ApiUser;
use Doctrine\ORM\NonUniqueResultException;

interface ApiUserRepositoryInterface
{
    /**
     * @param string $apiKey
     * @return ApiUser|null
     * @throws NonUniqueResultException
     */
    public function findByApiKey(string $apiKey): ?ApiUser;
}