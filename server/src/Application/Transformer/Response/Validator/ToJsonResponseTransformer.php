<?php

namespace App\Application\Transformer\Response\Validator;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ToJsonResponseTransformer implements ValidationErrorListToHttpResponseTransformerInterface
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function transform(ConstraintViolationListInterface $errorList): Response
    {
        return
            new Response(
                $this->serializer->serialize(['validationErrorList' => $this->getErrorMessageList($errorList)], 'json'),
                Response::HTTP_BAD_REQUEST,
                ['Content-Type' => 'application/json']
            );
    }

    /**
     * @param ConstraintViolationListInterface $errorList
     *
     * @return string[]
     */
    private function getErrorMessageList(ConstraintViolationListInterface $errorList): array
    {
        $errorMessageList = [];

        foreach ($errorList as $error) {
            $errorMessageList[] = sprintf('%s: %s', $error->getPropertyPath(), $error->getMessage());
        }

        return $errorMessageList;
    }
}