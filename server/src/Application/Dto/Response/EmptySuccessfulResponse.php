<?php

namespace App\Application\Dto\Response;

use Symfony\Component\HttpFoundation\Response;

class EmptySuccessfulResponse extends BaseResponse
{
    public function __construct()
    {
        parent::__construct(Response::HTTP_OK);
    }
}