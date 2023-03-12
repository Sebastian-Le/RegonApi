<?php

declare(strict_types=1);

namespace RegonApi\Format;

use RegonApi\Data\Company;
use RegonApi\Interfaces\FormatAddressesInterface;
use stdClass;

class FormatAddresses implements FormatAddressesInterface
{
    /**
     * Replace empty values (stdClass objects) with ''
     *
     * @param stdClass $companyData
     * @return stdClass
     */
    private static function replaceEmpty(stdClass $companyData): stdClass
    {
        foreach ($companyData as &$value) {
            $value = is_object($value) ? false : $value;
        }
        return $companyData;
    }

    /**
     * Format company address to given format
     *
     * If there's no street name (ex. village), format address to <Miejscowosc> <NrNieruchomosci>
     * and for <Miejscowosc> assign <Gmina>
     *
     * @param stdClass $regonsCompanyData
     * @return string
     */
    private static function formatCompanyAddress(stdClass $regonsCompanyData): string
    {
        $companyAddress = '';
        if ($regonsCompanyData->Ulica) {
            $companyAddress .= trim(str_replace(["ul.", "UL."], "", $regonsCompanyData->Ulica));
        } elseif ($regonsCompanyData->Gmina && $regonsCompanyData->Miejscowosc) {
            $companyAddress .= $regonsCompanyData->Miejscowosc;
            $regonsCompanyData->Miejscowosc = $regonsCompanyData->Gmina;
        }

        if ($regonsCompanyData->NrNieruchomosci) {
            $companyAddress .= ' ' . $regonsCompanyData->NrNieruchomosci;
        }
        if ($regonsCompanyData->NrLokalu) {
            $companyAddress .= '/' . $regonsCompanyData->NrLokalu;
        }

        return $companyAddress;
    }

    /**
     * Loop through returned result set and format each result
     *
     * @param stdClass $recivedData
     * @return array
     */
    public static function format(stdClass $recivedData): array
    {
        $CompaniesArray = [];

        /** REGON search results returns object with array or object  */
        $recivedData = is_array($recivedData->dane) ? $recivedData->dane : $recivedData;

        foreach ($recivedData as $regonsCompanyData) {
            $regonsCompanyData = self::replaceEmpty($regonsCompanyData);

            if (
                isset($regonsCompanyData->DataZakonczeniaDzialalnosci)
                && $regonsCompanyData->DataZakonczeniaDzialalnosci
            ) {
                $status = "WYKRESLONY";
            } elseif (
                isset($regonsCompanyData->DataZawieszeniaDzialalnosci)
                && $regonsCompanyData->DataZawieszeniaDzialalnosci
            ) {
                $status = "ZAWIESZONY";
            } else {
                $status = "AKTYWNY";
            }

            $CompaniesArray[] = new Company(
                $regonsCompanyData->Nazwa,
                self::formatCompanyAddress($regonsCompanyData),
                $regonsCompanyData->KodPocztowy,
                $regonsCompanyData->Miejscowosc,
                $regonsCompanyData->Nip,
                $status
            );
        }

        return $CompaniesArray;
    }
}
