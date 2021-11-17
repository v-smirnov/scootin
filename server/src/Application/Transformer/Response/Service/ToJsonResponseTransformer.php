<?php

namespace App\Application\Transformer\Response\Service;

use App\Application\Dto\Response\BaseResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class ToJsonResponseTransformer implements ResponseObjectToHttpResponseTransformerInterface
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function transform(BaseResponse $responseObject): Response
    {
        return
            new Response(
                $this->serializer->serialize($responseObject, 'json'),
                $responseObject->getStatusCode(),
                ['Content-Type' => 'application/json']
            );
    }
}