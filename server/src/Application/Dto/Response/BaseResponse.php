<?php

namespace App\Application\Dto\Response;

use Symfony\Component\Serializer\Annotation\Ignore;

class BaseResponse
{
    /**
     * @Ignore()
     */
    protected int $statusCode;

    public function __construct(int $statusCode)
    {
        $this->statusCode = $statusCode;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}