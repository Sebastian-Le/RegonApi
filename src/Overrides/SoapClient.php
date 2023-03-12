<?php

declare(strict_types=1);

namespace RegonApi\Overrides;

class SoapClient extends \SoapClient
{
    public function commitHeaders($headers)
    {
        $this->__setSoapHeaders($headers);
    }

    /**
     * All credits to
     * @author Pradeep Kumar Mishra
     * @github https://gist.github.com/pkmishra/2243055
     *
     * This client extends the usual SoapClient to handle mtom encoding. Due
     * to mtom encoding soap body has test apart from valid xml. This extension
     * remove the text and just keeps the response xml.
     */

    public function __doRequest($request, $location, $action, $version, $one_way = 0): ?string
    {
        $response = parent::__doRequest($request, $location, $action, $version, $one_way);
        //if response content type is mtom strip away everything but the xml.
        if (strpos($response, "Content-Type: application/xop+xml") !== false) {
            //not using stristr function twice because not supported in php 5.2 as shown below
            //$response = stristr(stristr($response, "<s:"), "</s:Envelope>", true) . "</s:Envelope>";
            $tempstr = stristr($response, "<s:");
            $response = substr($tempstr, 0, strpos($tempstr, "</s:Envelope>")) . "</s:Envelope>";
        }
        //log_message($response);
        return $response;
    }
}
