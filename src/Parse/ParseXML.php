<?php

declare(strict_types=1);

namespace RegonApi\Parse;

use RegonApi\Interfaces\ParseInterface;
use stdClass;
use SimpleXMLElement;
use Exception;

class ParseXML implements ParseInterface
{
    /**
     * From given XML string, return SimpleXMLElement object
     *
     * @param string $data
     * @return stdClass|void
     */
    public static function parse(string $data)
    {
        try {
            $xmlObj =  new SimpleXMLElement($data);
            /** convert SimpleXMLElement obj to stdClass obj */
            return json_decode(json_encode($xmlObj));
        } catch (Exception $e) {
            echo 'SimpleXMLElement exception ' . $e;
        }
    }
}
