<?php

namespace App\Application\Transformer\Response\Validator;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

interface ValidationErrorListToHttpResponseTransformerInterface
{
    public function transform(ConstraintViolationListInterface $errorList): Response;
}