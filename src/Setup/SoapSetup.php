<?php

declare(strict_types=1);

namespace RegonApi\Setup;

use RegonApi\Interfaces\SoapSetupInterface;
use SoapHeader;

class SoapSetup implements SoapSetupInterface
{
    protected array $soapHeaders = [];
    protected array $soapClientOptions = [];

    /**
     * Set Soap Client options
     *
     * @param array|null $streamContextOptions
     * @return void
     */
    public function setSoapClientOptions(?array $streamContextOptions = []): void
    {
        $this->soapClientOptions = [
            'soap_version' => SOAP_1_2,
            'trace' => true,
            'style' => SOAP_DOCUMENT,
            'stream_context' => stream_context_create($streamContextOptions),
        ];
    }

    /**
     * Return Soap Client Options, if soapClientOptions is empty, set default values
     *
     * @return array
     */
    public function getSoapClientOptions(): array
    {
        !empty($this->soapClientOptions) ?: $this->setSoapClientOptions();

        return $this->soapClientOptions;
    }

    /**
     * Set Soap Action header
     *
     * @param string $action
     * @return void
     */
    public function setSoapActionHeader(string $action): void
    {
        $this->clearSoapHeaders();
        $this->addSoapHeader('To', DEFAULT_T0O_ACTION);
        $this->addSoapHeader('Action', $action);
    }

    /**
     * Return SOAP headers
     *
     * @return array
     */
    public function getSoapHeaders(): array
    {
        return $this->soapHeaders;
    }

    /**
     * Add new SOAP header
     *
     * @param string $name
     * @param string $action
     * @return void
     */
    private function addSoapHeader(string $name, string $action): void
    {
        $this->soapHeaders[] = new SoapHeader('http://www.w3.org/2005/08/addressing', $name, $action);
    }

    /**
     * Clear SOAP headers
     *
     * @return void
     */
    private function clearSoapHeaders(): void
    {
        $this->soapHeaders = [];
    }
}
