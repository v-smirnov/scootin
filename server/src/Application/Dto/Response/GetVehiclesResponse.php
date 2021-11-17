<?php

namespace App\Application\Dto\Response;


use App\Application\Dto\Vehicle;
use Symfony\Component\HttpFoundation\Response;

class GetVehiclesResponse extends BaseResponse
{
    private array $vehicles;

    public function __construct(array $vehicles)
    {
        parent::__construct(Response::HTTP_OK);

        $this->vehicles = $vehicles;
    }

    /**
     * @return Vehicle[]
     */
    public function getVehicles(): array
    {
        return $this->vehicles;
    }
}