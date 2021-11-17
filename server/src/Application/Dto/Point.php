<?php

namespace App\Application\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class Point
{
    /**
     * @Assert\NotNull()
     * @Assert\Type("float")
     */
    private $x;

    /**
     * @Assert\NotNull()
     * @Assert\Type("float")
     */
    private $y;

    public function __construct($x, $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function getX(): float
    {
        return $this->x;
    }

    public function getY(): float
    {
        return $this->y;
    }
}