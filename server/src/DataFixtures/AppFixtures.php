<?php

namespace App\DataFixtures;

use App\Domain\Entity\ApiUser;
use App\Domain\Entity\Vehicle;
use App\Domain\Entity\VehicleLocation;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use LongitudeOne\Spatial\PHP\Types\Geometry\Point;
use Ramsey\Uuid\Uuid;

class AppFixtures extends Fixture
{
    private const TEST_API_KEY = 'toobanitoocs';
    private const API_KEY_TEMPLATE = 'api_key_%d';
    private const CLIENT_USERS_AMOUNT = 10;
    private const VEHICLE_TYPE = 'scooter';
    private const VEHICLE_INITIAL_STATUS = 'available';
    private const VEHICLE_AMOUNT = 50;
    private const START_LATITUDE = 52.419687;
    private const START_LONGITUDE = 13.157065;
    private const END_LATITUDE = 52.597160;
    private const END_LONGITUDE = 13.601000;

    public function load(ObjectManager $manager): void
    {
        $this->createApiTestUser($manager);
        $this->createApiClientUsers($manager);

        for ($i = 1; $i <= self::VEHICLE_AMOUNT; $i++) {
            $vehicle = $this->createVehicle($manager);

            $this->createVehicleLocation($manager, $vehicle);
        }

        $manager->flush();
    }

    private function createApiTestUser(ObjectManager $manager): void
    {
        $manager->persist(
            (new ApiUser())
                ->setDescription('test API user')
                ->setApiKey(self::TEST_API_KEY)
                ->setCreatedAt(new DateTime())
                ->setUpdatedAt(new DateTime())
        );
    }

    private function createApiClientUsers(ObjectManager $manager): void
    {
        for ($i = 1; $i <= self::CLIENT_USERS_AMOUNT; $i++) {
            $manager->persist(
                (new ApiUser())
                    ->setDescription(sprintf('API user %d', $i))
                    ->setApiKey(sprintf(self::API_KEY_TEMPLATE, $i))
                    ->setCreatedAt(new DateTime())
                    ->setUpdatedAt(new DateTime())
            );
        }
    }

    private function createVehicle(ObjectManager $manager): Vehicle
    {

        $vehicle =
            (new Vehicle())
                ->setIdentifier(Uuid::uuid4()->toString())
                ->setType(self::VEHICLE_TYPE)
                ->setStatus(self::VEHICLE_INITIAL_STATUS)
                ->setCreatedAt(new DateTime())
                ->setUpdatedAt(new DateTime())
            ;

        $manager->persist($vehicle);

        return $vehicle;
    }

    private function createVehicleLocation(ObjectManager $manager, Vehicle $vehicle): void
    {
        $vehicleLocation =
            (new VehicleLocation())
                ->setVehicle($vehicle)
                ->setLocation($this->createRandomLocation())
                ->setCreatedAt(new DateTime())
                ->setUpdatedAt(new DateTime())
            ;

        $manager->persist($vehicleLocation);
    }

    private function createRandomLocation():Point
    {
        return
            new Point(
                round(self::START_LATITUDE + ((self::END_LATITUDE - self::START_LATITUDE) / mt_rand(2, 10)), 6),
                round(self::START_LONGITUDE + ((self::END_LONGITUDE - self::START_LONGITUDE) / mt_rand(2, 10)), 6)
            );
    }
}
