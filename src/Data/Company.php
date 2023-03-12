<?php

declare(strict_types=1);

namespace RegonApi\Data;

class Company
{
    public string $name;
    public string $streetNumber;
    public string $postalCode;
    public string $city;
    public string $nip;
    public string $status;

    public function __construct($name, $streetNumber, $postalCode, $city, $nip, $status)
    {
        $this->name = $name;
        $this->streetNumber = $streetNumber;
        $this->postalCode = $postalCode;
        $this->city = $city;
        $this->nip = $nip;
        $this->status = $status;
    }
}
