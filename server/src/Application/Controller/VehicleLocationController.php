<?php

namespace App\Application\Controller;

use App\Application\Dto\Request\UpdateLocationRequest;
use App\Application\Transformer\Response\Service\ResponseObjectToHttpResponseTransformerInterface;
use App\Application\Transformer\Response\Validator\ValidationErrorListToHttpResponseTransformerInterface;
use App\Domain\Service\VehicleLocationServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class VehicleLocationController extends AbstractController
{
    private ValidatorInterface $validator;

    private ValidationErrorListToHttpResponseTransformerInterface $validationErrorListToHttpResponseTransformer;

    private VehicleLocationServiceInterface $service;

    private ResponseObjectToHttpResponseTransformerInterface $responseObjectToHttpResponseTransformer;

    public function __construct(
        ValidatorInterface                                    $validator,
        ValidationErrorListToHttpResponseTransformerInterface $validationErrorListToHttpResponseTransformer,
        VehicleLocationServiceInterface                       $service,
        ResponseObjectToHttpResponseTransformerInterface      $responseObjectToHttpResponseTransformer
    ) {
        $this->validator = $validator;
        $this->validationErrorListToHttpResponseTransformer = $validationErrorListToHttpResponseTransformer;
        $this->service = $service;
        $this->responseObjectToHttpResponseTransformer = $responseObjectToHttpResponseTransformer;
    }

    /**
     * @Route("/api/vehicle/{identifier}/location", name="api_update_vehicle_location", methods={"PUT"})
     */
    public function updateLocation(Request $request): Response
    {
        $requestDto = UpdateLocationRequest::createFromHttpRequest($request);

        $errors = $this->validator->validate($requestDto);

        if ($errors->count() > 0) {
            return $this->validationErrorListToHttpResponseTransformer->transform($errors);
        }

        $responseDto = $this->service->updateLocation($requestDto);

        return $this->responseObjectToHttpResponseTransformer->transform($responseDto);
    }
}