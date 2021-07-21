<?php

namespace kadhamw\VocusSOAP\SOAP;

use kadhamw\VocusSOAP\Connection;
use kadhamw\VocusSOAP\Models\WsdlRequest;

class NBNFactory
{
    #Returns List of Addresses
    public static function AddressLookup($st_number, $st_name, $st_type, $suburb, $state)
    {
        $params = (object)[];
        $params->{'Main.Street1stNumber'} = $st_number;
        $params->{'Main.StreetName'} = $st_name;
        $params->{'Main.StreetType'} = $st_type;
        $params->{'Main.Suburb'} = $suburb;
        $params->{'Main.State'} = $state;
        //$params->{'Main.Postcode'} = $postcode;
        $params->SearchMode = 'DEFAULT';
        $_wsdlobj = new WsdlRequest('GET', $params,'DIR','BROADBAND');

        $conn = new Connection();
        $result = $conn->request($_wsdlobj);

        $params = $result->Parameters->Param;
        $converted = $conn->ConvertPARAMStoOBJ($params); // Change to WsdlResponse
        dd($converted);
        return($converted);
    }

    public static function Qualify($product, $DIR_ID) {
        $params = (object)[];
        $params->{'DirectoryID'} = $DIR_ID;
        $_wsdlobj = new WsdlRequest('GET', $params,'FIBRE','','QUALIFY');

        $conn = new Connection();
        $result = $conn->request($_wsdlobj);

        $params = $result->Parameters->Param;
        dd($params);
        $converted = $conn->ConvertPARAMStoOBJ($params);
        return($converted);
    }

    ### Not used yet
    public static function OrderService(){

    }

    public static function OrderRCService($planID, $serviceID, $realm, $cName, $cPhone, $password, $svcType, $CopperPairID,$DIR_ID, $CPEDIR_ID, $LocationRef){
        $params = (object)[];
        $params->{'ServiceID'} = $serviceID;
        $params->{'Realm'} = $realm;
        $params->{'OrderType'} = 'NEW';
        $params->{'CustomerName'} = $cName;
        $params->{'Phone'} = $cPhone;
        $params->{'Password'} = $password;
        $params->{'ServiceType'} = $svcType;
        $params->{'CopperPairID'} = $CopperPairID;
        $params->{'VoicebandContinuity'} = 'FALSE';
        $params->{'DirectoryID'} = $DIR_ID;
        $params->{'CPEDirectoryID'} = $CPEDIR_ID;
        $params->{'LocationReference'} = $LocationRef;
        $params->{'CentralSplitter'} = 'FALSE';

        $_wsdlobj = new WsdlRequest('CREATE', $params, 'FIBRE', $planID, 'RESELLER-CONNECT');
        $conn = new Connection();
        $result = $conn->request($_wsdlobj);

        return($result);
    }

    ### Not used yet
    public static function OrderNCService(){

    }
}
