<?php

namespace App\Tests\Domain\Service;

use App\Application\Dto\Request\UpdateLocationRequest;
use App\Application\Dto\Response\EmptySuccessfulResponse;
use App\Application\Dto\Response\ErroneousResponse;
use App\Domain\Entity\Vehicle;
use App\Domain\Entity\VehicleLocation;
use App\Domain\Service\VehicleLocationService;
use App\Domain\Service\VehicleLocationServiceInterface;
use App\Infrastructure\Repository\VehicleLocationRepositoryInterface;
use App\Infrastructure\Repository\VehicleRepositoryInterface;
use LongitudeOne\Spatial\PHP\Types\Geometry\Point;
use PHPUnit\Framework\TestCase;

class VehicleLocationServiceTest extends TestCase
{
    private const VEHICLE_IDENTIFIER = 'id_1';
    private const REQUEST_LATITUDE = 52.419687;
    private const REQUEST_LONGITUDE = 13.157065;
    private const RECEIVED_AT = '2021-11-14 10:00:00';

    /**
     * @dataProvider updateLocationDataProvider
     */
    public function testUpdateLocation($vehicleRepository, $vehicleLocationRepository, $expectedResponse): void
    {
        $service = $this->createService($vehicleRepository, $vehicleLocationRepository);

        $response = $service->updateLocation($this->createRequest(),);

        self::assertEquals($expectedResponse, $response);
    }

    public function updateLocationDataProvider(): array
    {
        return [
            'Successful scenario, no previous location was found' => [
                $this->createVehicleRepositoryMock($this->createVehicleEntity()),
                $this->createVehicleLocationRepositoryMock(null),
                new EmptySuccessfulResponse(),
            ],
            'Successful scenario, update existing location' => [
                $this->createVehicleRepositoryMock($this->createVehicleEntity()),
                $this->createVehicleLocationRepositoryMock($this->createVehicleLocationEntity()),
                new EmptySuccessfulResponse(),
            ],
            'Unsuccessful scenario, vehicle not found' => [
                $this->createVehicleRepositoryMock(null),
                $this->createMock(VehicleLocationRepositoryInterface::class),
                new ErroneousResponse(sprintf("Vehicle %s not found", self::VEHICLE_IDENTIFIER)),
            ],
        ];
    }

    private function createService(
        VehicleRepositoryInterface $vehicleRepository,
        VehicleLocationRepositoryInterface $vehicleLocationRepository
    ): VehicleLocationServiceInterface
    {
        return new VehicleLocationService($vehicleRepository, $vehicleLocationRepository);
    }

    private function createVehicleRepositoryMock(?Vehicle $vehicle): VehicleRepositoryInterface
    {
        $mock = $this->createMock(VehicleRepositoryInterface::class);

        $mock
            ->expects(self::once())
            ->method('findByIdentifier')
            ->with(self::VEHICLE_IDENTIFIER)
            ->willReturn($vehicle)
        ;

        return $mock;
    }

    private function createVehicleLocationRepositoryMock(
        ?VehicleLocation $vehicleLocation
    ): VehicleLocationRepositoryInterface {
        $mock = $this->createMock(VehicleLocationRepositoryInterface::class);

        $mock
            ->expects(self::once())
            ->method('findByVehicle')
            ->with($this->createVehicleEntity())
            ->willReturn($vehicleLocation)
        ;

        $mock
            ->expects(self::once())
            ->method('save')
            ->with(self::callback(
                function (VehicleLocation $entity) {
                    self::assertEquals(
                        new Point(self::REQUEST_LATITUDE, self::REQUEST_LONGITUDE),
                        $entity->getLocation()
                    );

                    return true;
                }
            ))
        ;

        return $mock;
    }

    private function createRequest(): UpdateLocationRequest
    {
        return
            new UpdateLocationRequest(
                self::VEHICLE_IDENTIFIER,
                self::REQUEST_LATITUDE,
                self::REQUEST_LONGITUDE,
                self::RECEIVED_AT
            );
    }

    private function createVehicleEntity(): Vehicle
    {
        return
            (new Vehicle())
                ->setIdentifier(self::VEHICLE_IDENTIFIER)
            ;
    }

    private function createVehicleLocationEntity(): VehicleLocation
    {
        return
            (new VehicleLocation())
                ->setVehicle($this->createVehicleEntity())
            ;
    }
}