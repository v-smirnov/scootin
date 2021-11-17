<?php

namespace App\Application\Dto;

class Vehicle
{
    private string $identifier;

    private string $type;

    public function __construct(string $identifier, string $type)
    {
        $this->identifier = $identifier;
        $this->type = $type;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getType(): string
    {
        return $this->type;
    }
}