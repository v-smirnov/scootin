<?php

namespace App\Application\Controller;

use App\Application\Dto\Request\GetVehiclesRequest;
use App\Application\Dto\Request\UpdateStatusRequest;
use App\Application\Transformer\Response\Service\ResponseObjectToHttpResponseTransformerInterface;
use App\Application\Transformer\Response\Validator\ValidationErrorListToHttpResponseTransformerInterface;
use App\Domain\Service\VehicleServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class VehicleController extends AbstractController
{
    private ValidatorInterface $validator;

    private ValidationErrorListToHttpResponseTransformerInterface $validationErrorListToHttpResponseTransformer;

    private VehicleServiceInterface $service;

    private ResponseObjectToHttpResponseTransformerInterface $responseObjectToHttpResponseTransformer;

    public function __construct(
        ValidatorInterface $validator,
        ValidationErrorListToHttpResponseTransformerInterface $validationErrorListToHttpResponseTransformer,
        VehicleServiceInterface $service,
        ResponseObjectToHttpResponseTransformerInterface $responseObjectToHttpResponseTransformer
    ) {
        $this->validator = $validator;
        $this->validationErrorListToHttpResponseTransformer = $validationErrorListToHttpResponseTransformer;
        $this->service = $service;
        $this->responseObjectToHttpResponseTransformer = $responseObjectToHttpResponseTransformer;
    }

    /**
     * @Route("/api/vehicle/{identifier}/status/{status}", name="api_update_vehicle_status", methods={"PUT"})
     */
    public function updateStatus(Request $request): Response
    {
        $requestDto = UpdateStatusRequest::createFromHttpRequest($request);

        $errors = $this->validator->validate($requestDto);

        if ($errors->count() > 0) {
            return $this->validationErrorListToHttpResponseTransformer->transform($errors);
        }

        $responseDto = $this->service->updateVehicleStatus($requestDto);

        return $this->responseObjectToHttpResponseTransformer->transform($responseDto);
    }

    /**
     * @Route("/api/vehicles/{type}/status/{status}", name="api_get_vehicles", methods={"GET"})
     *
     * In some cases while getting list of objects it makes sense to limit amount of returned data,
     * for example, if in given area we have 100k vehicles, better not to return them all in once, but just some part.
     * I decided not to implement such functionality here to decrease development time.
     */
    public function getList(Request $request): Response
    {
        $requestDto = GetVehiclesRequest::createFromHttpRequest($request);

        $errors = $this->validator->validate($requestDto);

        if ($errors->count() > 0) {
            return $this->validationErrorListToHttpResponseTransformer->transform($errors);
        }

        $responseDto = $this->service->getVehicles($requestDto);

        return $this->responseObjectToHttpResponseTransformer->transform($responseDto);
    }
}