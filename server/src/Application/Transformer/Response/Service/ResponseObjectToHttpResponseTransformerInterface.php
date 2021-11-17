<?php

namespace App\Application\Transformer\Response\Service;

use App\Application\Dto\Response\BaseResponse;
use Symfony\Component\HttpFoundation\Response;

interface ResponseObjectToHttpResponseTransformerInterface
{
    public function transform(BaseResponse $responseObject): Response;
}