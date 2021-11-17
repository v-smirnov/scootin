<?php

namespace App\Domain\Service;

use App\Application\Dto\Request\UpdateLocationRequest;
use App\Application\Dto\Response\BaseResponse;

interface VehicleLocationServiceInterface
{
    public function updateLocation(UpdateLocationRequest $requestDto): BaseResponse;
}