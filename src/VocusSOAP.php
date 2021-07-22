<?php

namespace KadHamW\VocusSOAP;
use kadhamw\VocusSOAP\SOAP\GeneralFactory;
use KadHamW\VocusSOAP\Connection;
use KadHamW\VocusSOAP\SOAP\MobileFactory;
use kadhamw\VocusSOAP\SOAP\NBNFactory;
use kadhamw\VocusSOAP\SOAP\TCASFactory;

class VocusSOAP
{
    public function test(){
        $result = NBNFactory::AddressLookup('','Alma','ST','Rockhampton','QLD');
        dd($result);
    }

    public function auth(){
        $conn = new Connection();
        $conn->authenticate();
    }

}
