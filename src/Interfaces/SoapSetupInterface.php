<?php

declare(strict_types=1);

namespace RegonApi\Interfaces;

interface SoapSetupInterface
{
    public function setSoapClientOptions(?array $streamContextOptions = []): void;

    public function getSoapClientOptions(): array;

    public function setSoapActionHeader(string $action): void;

    public function getSoapHeaders(): array;
}
