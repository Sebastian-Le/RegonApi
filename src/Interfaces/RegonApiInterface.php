<?php

declare(strict_types=1);

namespace RegonApi\Interfaces;

interface RegonApiInterface
{
    public function getCompanyDetailsFromNIP(int $nip): string;
}
