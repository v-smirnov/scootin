<?php

namespace App\Application\Dto\Request;

use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateLocationRequest
{
    /**
     * @Assert\NotBlank()
     * @Assert\Type("string")
     */
    private $vehicleIdentifier;

    /**
     * @Assert\NotNull()
     * @Assert\Type("float")
     */
    private $latitude;

    /**
     * @Assert\NotNull()
     * @Assert\Type("float")
     */
    private $longitude;

    /**
     * @Assert\NotBlank()
     * @Assert\DateTime
     */
    private $receivedAt;

    public function __construct($vehicleIdentifier, $latitude, $longitude, $receivedAt)
    {
        $this->vehicleIdentifier = $vehicleIdentifier;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->receivedAt = $receivedAt;
    }

    public function getVehicleIdentifier(): string
    {
        return $this->vehicleIdentifier;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function getReceivedAt(): DateTime
    {
        return new DateTime($this->receivedAt);
    }

    public static function createFromHttpRequest(Request $request): UpdateLocationRequest
    {
        $content = json_decode($request->getContent(), true);

        $latitude = $content !== null && array_key_exists('latitude', $content) ? $content['latitude'] : null;
        $longitude = $content !== null && array_key_exists('longitude', $content) ? $content['longitude'] : null;
        $receivedAt = $content !== null && array_key_exists('received_at', $content) ? $content['received_at'] : "";

        return new static($request->get('identifier'), $latitude, $longitude, $receivedAt);
    }
}