<?php

declare(strict_types=1);

namespace RegonApi\tests;

use PHPUnit\Framework\TestCase;
use RegonApi\Data\Company;
use RegonApi\Format\FormatAddresses;

final class FormatAddressesTest extends TestCase
{
    public function testFormat()
    {
        $regonCompanysDataSample = new \stdClass();
        $regonCompanysDataSample->dane[0] = new \stdClass();
        $regonCompanysDataSample->dane[0]->Regon = '000331501';
        $regonCompanysDataSample->dane[0]->Nip = '5261040828';
        $regonCompanysDataSample->dane[0]->StatusNip = new \stdClass();
        $regonCompanysDataSample->dane[0]->Nazwa = 'GŁÓWNY URZĄD STATYSTYCZNY';
        $regonCompanysDataSample->dane[0]->Wojewodztwo = 'MAZOWIECKIE';
        $regonCompanysDataSample->dane[0]->Powiat = 'm. st. Warszwa';
        $regonCompanysDataSample->dane[0]->Gmina = 'Śródmieście';
        $regonCompanysDataSample->dane[0]->Miejscowosc = 'Warszawa';
        $regonCompanysDataSample->dane[0]->KodPocztowy = '00-925';
        $regonCompanysDataSample->dane[0]->Ulica = 'ul. Test-Krucza';
        $regonCompanysDataSample->dane[0]->NrNieruchomosci = '208';
        $regonCompanysDataSample->dane[0]->NrLokalu = new \stdClass();
        $regonCompanysDataSample->dane[0]->Typ = 'P';
        $regonCompanysDataSample->dane[0]->SilosID = '6';
        $regonCompanysDataSample->dane[0]->DataZakonczeniaDzialalnosci = new \stdClass();
        $regonCompanysDataSample->dane[0]->MiejscowoscPoczty = 'Warszawa';

        $expectedFormatedData = [
            new Company(
                'GŁÓWNY URZĄD STATYSTYCZNY',
                'Test-Krucza 208',
                '00-925',
                'Warszawa',
                '5261040828',
                'AKTYWNY'
            )];

        $formatedData = FormatAddresses::format($regonCompanysDataSample);

        self::assertIsArray($formatedData);
        self::assertEquals($expectedFormatedData, $formatedData);
    }
}
