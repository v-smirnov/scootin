<?php

namespace App\Domain\Service;

use App\Application\Dto\Request\GetVehiclesRequest;
use App\Application\Dto\Request\UpdateStatusRequest;
use App\Application\Dto\Response\BaseResponse;
use App\Application\Dto\Response\EmptySuccessfulResponse;
use App\Application\Dto\Response\ErroneousResponse;
use App\Application\Dto\Response\ExceptionalResponse;
use App\Application\Dto\Response\GetVehiclesResponse;
use App\Application\Dto\Vehicle as VehicleDto;
use App\Domain\Entity\Vehicle;
use App\Infrastructure\Repository\VehicleRepositoryInterface;
use Doctrine\ORM\Exception\ORMException;
use LongitudeOne\Spatial\PHP\Types\Geometry\Point;

class VehicleService implements VehicleServiceInterface
{
    private VehicleRepositoryInterface $vehicleRepository;


    public function __construct(VehicleRepositoryInterface $vehicleRepository)
    {
        $this->vehicleRepository = $vehicleRepository;
    }

    /**
     * In production scenario it is necessary to lock vehicle entity to avoid concurrency issues, but
     * for the sake of simplicity don't do it here
     */
    public function updateVehicleStatus(UpdateStatusRequest $requestDto): BaseResponse
    {
        $vehicle = $this->vehicleRepository->findByIdentifier($requestDto->getVehicleIdentifier());

        if ($vehicle === null) {
            return new ErroneousResponse(sprintf("Vehicle %s not found", $requestDto->getVehicleIdentifier()));
        }

        if (!$this->isTransitionToNewStatusPossible($vehicle, $requestDto->getStatus())) {
            return new ErroneousResponse(sprintf("Change vehicle %s status is not possible", $requestDto->getVehicleIdentifier()));
        }

        try {
            $this->vehicleRepository->save(
                $vehicle
                    ->setStatus($requestDto->getStatus())
                    ->setUpdatedAt($requestDto->getUpdatedAt())
            );
        } catch (ORMException $e) {
            new ExceptionalResponse('Vehicle status update attempt failed due to exception');
        }

        return new EmptySuccessfulResponse();
    }

    public function getVehicles(GetVehiclesRequest $requestDto): BaseResponse
    {
        $area = $requestDto->getArea();

        $vehicles =
            $this->vehicleRepository->findByTypeAndStatusInArea(
                $requestDto->getType(),
                $requestDto->getStatus(),
                new Point($area->getStartPoint()->getX(), $area->getStartPoint()->getY()),
                new Point($area->getEndPoint()->getX(), $area->getEndPoint()->getY()),
            );


        return
            new GetVehiclesResponse(
                array_map(
                    function (Vehicle $entity) {
                        return new VehicleDto($entity->getIdentifier(), $entity->getType());
                    },
                    $vehicles
                )
            );
    }

    /**
     * As better option here can be used state machine, but for the sake of simplicity and lack of time will use here
     * simple conditional operator
     */
    private function isTransitionToNewStatusPossible(Vehicle $vehicle, string $statusTo): bool
    {
        // As we use just 2 statuses (available and occupied) this check will be enough
        return $vehicle->getStatus() != $statusTo;
    }
}