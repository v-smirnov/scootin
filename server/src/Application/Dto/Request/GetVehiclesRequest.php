<?php

namespace App\Application\Dto\Request;

use App\Application\Dto\Area;
use App\Application\Dto\Point;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class GetVehiclesRequest
{
    /**
     * @Assert\NotBlank()
     * @Assert\Type("string")
     * @Assert\Choice({"scooter"})
     */
    private $type;

    /**
     * @Assert\NotBlank()
     * @Assert\Type("string")
     * @Assert\Choice({"available", "occupied", "unavailable", "reserved"})
     */
    private $status;

    /**
     * @Assert\Valid()
     */
    private $area;

    public function __construct($type, $status, $area)
    {
        $this->type = $type;
        $this->status = $status;
        $this->area = $area;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getArea(): Area
    {
        return $this->area;
    }

    public static function createFromHttpRequest(Request $request): GetVehiclesRequest
    {
        $content = json_decode($request->getContent(), true);

        $startLt = $content !== null && array_key_exists('start_latitude', $content) ? $content['start_latitude'] : null;
        $startLg = $content !== null && array_key_exists('start_longitude', $content) ? $content['start_longitude'] : null;
        $endLt = $content !== null && array_key_exists('end_latitude', $content) ? $content['end_latitude'] : null;
        $endLg = $content !== null && array_key_exists('end_longitude', $content) ? $content['end_longitude'] : null;

        return
            new static(
                $request->get('type'),
                $request->get('status'),
                new Area(new Point($startLt, $startLg), new Point($endLt, $endLg))
            );
    }
}