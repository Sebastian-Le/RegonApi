<?php

declare(strict_types=1);

namespace RegonApi\tests;

use PHPUnit\Framework\TestCase;
use RegonApi\RegonApi;
use RegonApi\Interfaces\RegonApiInterface;

final class RegonApiTest extends TestCase
{
    public function testGetCompanyDetailsFromNIPPass()
    {
        $regonApi = new RegonApi();

        self::assertInstanceOf(RegonApiInterface::class, $regonApi);
        self::assertInstanceOf(RegonApi::class, $regonApi);

        $nipActive = 5261040828;

        $returned = $regonApi->getCompanyDetailsFromNIP($nipActive);
        self::assertJson($returned);
    }
}
