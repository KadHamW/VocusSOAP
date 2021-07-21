<?php

namespace kadhamw\VocusSOAP\SOAP;

use kadhamw\VocusSOAP\Connection;
use kadhamw\VocusSOAP\Models\WsdlRequest;

class TCASFactory
{
    public static function poll($transID){
        $params = (object)[];
        $params->{'TransactionID'} = $transID;

        $_wsdlobj = new WsdlRequest('GET',$params,'TCAS','TRANSACTION-ID','RESPONSE');
        $conn = new Connection;
        $result = $conn->request($_wsdlobj);

        $params = $result->Parameters->Param;
        $converted = $conn->ConvertPARAMStoOBJ($params); // Change to WsdlResponse

        return($converted);
    }
}
