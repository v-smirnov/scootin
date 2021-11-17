<?php

namespace App\Domain\Service;

use App\Application\Dto\Request\UpdateLocationRequest;
use App\Application\Dto\Response\BaseResponse;
use App\Application\Dto\Response\EmptySuccessfulResponse;
use App\Application\Dto\Response\ErroneousResponse;
use App\Application\Dto\Response\ExceptionalResponse;
use App\Domain\Entity\Vehicle;
use App\Domain\Entity\VehicleLocation;
use App\Infrastructure\Repository\VehicleLocationRepositoryInterface;
use App\Infrastructure\Repository\VehicleRepositoryInterface;
use DateTimeImmutable;
use Doctrine\ORM\Exception\ORMException;
use LongitudeOne\Spatial\PHP\Types\Geometry\Point;

class VehicleLocationService implements VehicleLocationServiceInterface
{
    private VehicleRepositoryInterface $vehicleRepository;

    private VehicleLocationRepositoryInterface $vehicleLocationRepository;

    public function __construct(
        VehicleRepositoryInterface $vehicleRepository,
        VehicleLocationRepositoryInterface $vehicleLocationRepository
    ) {
        $this->vehicleRepository = $vehicleRepository;
        $this->vehicleLocationRepository = $vehicleLocationRepository;
    }

    public function updateLocation(UpdateLocationRequest $requestDto): BaseResponse
    {
        $vehicle = $this->vehicleRepository->findByIdentifier($requestDto->getVehicleIdentifier());

        if ($vehicle === null) {
            return new ErroneousResponse(sprintf("Vehicle %s not found", $requestDto->getVehicleIdentifier()));
        }

        $vehicleLocation = $this->vehicleLocationRepository->findByVehicle($vehicle);

        if ($vehicleLocation === null) {
            $vehicleLocation = $this->createLocationEntity($vehicle);
        }

        $vehicleLocation
            ->setLocation(new Point($requestDto->getLatitude(), $requestDto->getLongitude()))
            ->setUpdatedAt($requestDto->getReceivedAt());

        try {
            $this->vehicleLocationRepository->save($vehicleLocation);
        } catch (ORMException $e) {
            return new ExceptionalResponse('Vehicle location update attempt failed due to exception');
        }

        return new EmptySuccessfulResponse();
    }

    private function createLocationEntity(Vehicle $vehicle): VehicleLocation
    {
        return
            (new VehicleLocation())
                ->setVehicle($vehicle)
                ->setCreatedAt(new DateTimeImmutable())
            ;
    }
}