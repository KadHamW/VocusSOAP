<?php

namespace KadHamW\VocusSOAP\SOAP;

use KadHamW\VocusSOAP\Connection;
use kadhamw\VocusSOAP\Models\WsdlRequest;

class MobileFactory
{
    public static function OrderService($planID, $serviceID, $SIM, $cName, $cPhone, $bucketID, $DIR_ID, $LOC_REF){
        $params = (object)[];
        $params->{'ServiceID'} = $serviceID;
        $params->{'SIM'} = $SIM;
        $params->{'CustomerName'} = $cName;
        $params->{'Phone'} = $cPhone;
        $params->{'BucketID'} = $bucketID;
        $params->{'DirectoryID'} = $DIR_ID;
        $params->{'LocationReference'} = $LOC_REF;
        $_wsdlobj = new WsdlRequest('CREATE', $params,'MOB',$planID,'STANDARD-POSTPAID');

        $conn = new Connection();
        $result = $conn->request($_wsdlobj);

        dd($result);
        return($result);
    }

    public static function QualifySIM($SIM){
        $params = (object)[];
        $params->{'SIM'} = $SIM;
        $_wsdlobj = new WsdlRequest('GET', $params, 'MOB', 'QUALIFY');

        $conn = new Connection();
        $result = $conn->request($_wsdlobj);

        return($result);
    }
}
