<?php

namespace App\Application\Dto\Request;

use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateStatusRequest
{
    /**
     * @Assert\NotBlank()
     * @Assert\Type("string")
     */
    private $vehicleIdentifier;

    /**
     * @Assert\NotBlank()
     * @Assert\Type("string")
     * @Assert\Choice({"available", "occupied", "unavailable", "reserved"})
     */
    private $status;

    /**
     * @Assert\NotBlank()
     * @Assert\DateTime
     */
    private $updatedAt;

    public function __construct($vehicleIdentifier, $status, $updatedAt)
    {
        $this->vehicleIdentifier = $vehicleIdentifier;
        $this->status = $status;
        $this->updatedAt = $updatedAt;
    }

    public function getVehicleIdentifier():string
    {
        return $this->vehicleIdentifier;
    }

    public function getStatus():string
    {
        return $this->status;
    }

    public function getUpdatedAt():DateTime
    {
        return new DateTime($this->updatedAt);
    }

    public static function createFromHttpRequest(Request $request): UpdateStatusRequest
    {
        $content = json_decode($request->getContent(), true);

        $updatedAt = $content !== null && array_key_exists('updated_at', $content) ? $content['updated_at'] : "";

        return new static($request->get('identifier'), $request->get('status'), $updatedAt);
    }
}