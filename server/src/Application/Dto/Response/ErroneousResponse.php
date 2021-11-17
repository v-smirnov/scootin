<?php

namespace App\Application\Dto\Response;

use Symfony\Component\HttpFoundation\Response;

class ErroneousResponse extends BaseResponse
{
    private string $error;

    public function __construct(string $error)
    {
        parent::__construct(Response::HTTP_BAD_REQUEST);

        $this->error = $error;
    }

    public function getError(): string
    {
        return $this->error;
    }
}