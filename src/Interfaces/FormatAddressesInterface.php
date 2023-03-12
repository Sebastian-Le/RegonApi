<?php

declare(strict_types=1);

namespace RegonApi\Interfaces;

use stdClass;

interface FormatAddressesInterface
{
    public static function format(stdClass $recivedData): array;
}
