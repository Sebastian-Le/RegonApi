<?php

declare(strict_types=1);

namespace RegonApi;

define('REGON_URL', 'https://wyszukiwarkaregontest.stat.gov.pl/wsBIR/wsdl/UslugaBIRzewnPubl-ver11-test.wsdl');
define('REGON_APIKEY', 'abcde12345abcde12345');
define('DEFAULT_T0O_ACTION', 'https://wyszukiwarkaregontest.stat.gov.pl/wsBIR/UslugaBIRzewnPubl.svc');

use RegonApi\Data\Company;
use RegonApi\Format\FormatAddresses;
use RegonApi\Interfaces\RegonApiInterface;
use RegonApi\Overrides\SoapClient;
use RegonApi\Parse\ParseXML;
use RegonApi\Setup\SoapSetup;
use SoapFault;

class RegonApi implements RegonApiInterface
{
    /**
     * Session key in Regon system
     *
     * @var string
     */
    protected string $sessionKey = '';

    /**
     * Instance of SoapSetup, used for seting up header to actions
     *
     * @var SoapSetup
     */
    protected SoapSetup $soapSetup;

    /**
     * Instance of SoapClient
     *
     * @var SoapClient
     */
    protected SoapClient $soapClient;

    /**
     * create new SoapClient instance with gathered information
     *
     * @return void
     */
    private function connect(): void
    {
        try {
            $this->soapClient = new SoapClient(REGON_URL, $this->soapSetup->getSoapClientOptions());
        } catch (SoapFault $fault) {
            echo 'Error establishing connection SoapFault: ' . $fault;
        }
    }

    /**
     * Establish SOAP connection,  get session key, establish authorized SOAP connection for queries
     */
    public function __construct()
    {
        $this->soapSetup = new SoapSetup();
        $this->connect();
        $this->establishSessionKey();

        /** after handshake, add session key to http header with sid: */
        $streamContextOptions = [
            'http' =>   [
                'header' => 'sid: ' . $this->getSessionKey()
            ],
        ];
        /** refresh connection with new http header */
        $this->soapSetup->setSoapClientOptions($streamContextOptions);
        $this->connect();
    }

    /**
     * Logout using session key
     */
    public function __destruct()
    {
        $this->soapSetup->setSoapActionHeader('http://CIS/BIR/PUBL/2014/07/IUslugaBIRzewnPubl/Wyloguj');
        $this->soapClient->commitHeaders($this->soapSetup->getSoapHeaders());

        $this->soapClient->Wyloguj([
            'pIdentyfikatorSesji' => $this->getSessionKey(),
            ]);
    }

    /**
     * Assign, received session key
     *
     * @return void
     */
    private function establishSessionKey(): void
    {
        $this->soapSetup->setSoapActionHeader('http://CIS/BIR/PUBL/2014/07/IUslugaBIRzewnPubl/Zaloguj');
        $this->soapClient->commitHeaders($this->soapSetup->getSoapHeaders());

        $result = $this->soapClient->Zaloguj([
            'pKluczUzytkownika' => REGON_APIKEY
        ]);

        /// @var $result  20chars long string or empty
        $this->sessionKey = (string)$result->ZalogujResult;
    }

    /** Return Session key
     *
     * @return string
     */
    protected function getSessionKey(): string
    {
        return $this->sessionKey;
    }

    /**
     * For given nip return array of matching Companies
     *
     * @param int $nip
     * @return string
     */
    public function getCompanyDetailsFromNIP(int $nip): string
    {
        try {
            $this->soapSetup->setSoapActionHeader(
                'http://CIS/BIR/PUBL/2014/07/IUslugaBIRzewnPubl/DaneSzukajPodmioty'
            );
            $this->soapClient->commitHeaders($this->soapSetup->getSoapHeaders());

            $result = $this->soapClient->DaneSzukajPodmioty([
                'pParametryWyszukiwania' => [
                    'Nip' => $nip
                ]
            ]);

            if (empty($result->DaneSzukajPodmiotyResult)) {
                return $this->returnErrorMessage(
                    $nip,
                    'Error retrieving data for nip ' . $nip . ' empty data returned.'
                );
            }

            $receivedData = ParseXML::parse($result->DaneSzukajPodmiotyResult);

            /** if error message received */
            if (isset($receivedData->dane->ErrorMessagePl)) {
                return $this->returnErrorMessage($nip, $receivedData->dane->ErrorMessagePl);
            }

            return json_encode(FormatAddresses::format($receivedData));
        } catch (SoapFault $fault) {
            return $this->returnErrorMessage(
                $nip,
                'Error retrieving data for nip ' . $nip . ', SoapFault: ' . $fault
            );
        }
    }

    /**
     * Predefined return error message, used to simplify displaying errors
     *
     * @param int $nip
     * @param string $message
     * @return Company[]
     */
    protected function returnErrorMessage(int $nip, string $message): string
    {
        return json_encode([new Company(
            '',
            '',
            '',
            '',
            (string)$nip,
            'Error: ' . $message
        )]);
    }
}
