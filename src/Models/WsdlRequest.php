<?php

namespace kadhamw\VocusSOAP\Models;



class WsdlRequest
{

    Public $AccessKey; // Pull this from config
    Public $reqType;
    Public $ProductID;
    Public $PlanID = "";
    Public $Scope = "";
    Public $Parameters;

    public function __construct($reqType, $parameters, $product_id, $plan_id ="" , $scope =""){
        $this->AccessKey = config('vocussoap.access_key');
        $this->ProductID = $product_id;
        $this->reqType = $reqType;
        if ($plan_id) { $this->PlanID = $plan_id; }
        if ($scope) { $this->Scope = $scope; }

        foreach((Array)$parameters as $key => $value){
            $_params[] = array(
                'id' => $key,
                '_' => $value
            );
        }
        $params = ['Param' => $_params];
        $this->Parameters = $params;
    }
}

