<?php

namespace App\Domain\Service;

use App\Application\Dto\Request\GetVehiclesRequest;
use App\Application\Dto\Request\UpdateStatusRequest;
use App\Application\Dto\Response\BaseResponse;

interface VehicleServiceInterface
{
    public function updateVehicleStatus(UpdateStatusRequest $requestDto): BaseResponse;

    public function getVehicles(GetVehiclesRequest $requestDto): BaseResponse;
}