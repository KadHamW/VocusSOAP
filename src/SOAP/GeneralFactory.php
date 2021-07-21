<?php

namespace kadhamw\VocusSOAP\SOAP;

use kadhamw\VocusSOAP\Connection;
use kadhamw\VocusSOAP\Models\WsdlRequest;

class GeneralFactory
{
    public static function RegisterAddress($FN,$LN,$st1stNumber,$stName,$stType,$suburb,$state,$postcode,$country,$ctFN,$ctLN,$ctPhone,$emFN,$emLN,$emPhone){
        $params = (object)[];
        $params->{'FirstName'} = $FN;
        $params->{'LastName'} = $LN;
        $params->{'Main.Street1stNumber'} = $st1stNumber;
        $params->{'Main.StreetName'} = $stName;
        $params->{'Main.StreetType'} = $stType;
        $params->{'Main.Suburb'} = $suburb;
        $params->{'Main.State'} = $state;
        $params->{'Main.Postcode'} = $postcode;
        $params->{'Main.Country'} = $country;

        $params->{'Normal.ContactFirstName'} = $ctFN;
        $params->{'Normal.ContactLastName'} = $ctLN;
        $params->{'Normal.Phone'} = $ctPhone;
        $params->{'Emergency.ContactFirstName'} = $emFN;
        $params->{'Emergency.ContactLastName'} = $emLN;
        $params->{'Emergency.Phone'} = $emPhone;

        $_wsdlobj = new WsdlRequest('CREATE', $params,'DIR','STANDARD');

        $conn = new Connection();
        $result = $conn->request($_wsdlobj);

        $DirectoryID = $result->Parameters->Param->_;
        return $DirectoryID;
    }
}
