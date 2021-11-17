<?php

namespace App\Application\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class Area
{
    /**
     * @Assert\Valid()
     */
    private Point $startPoint;

    /**
     * @Assert\Valid()
     */
    private Point $endPoint;

    public function __construct(Point $startPoint, Point $endPoint)
    {
        $this->startPoint = $startPoint;
        $this->endPoint = $endPoint;
    }

    public function getStartPoint(): Point
    {
        return $this->startPoint;
    }

    public function getEndPoint(): Point
    {
        return $this->endPoint;
    }
}