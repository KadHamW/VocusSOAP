<?php

namespace KadHamW\VocusSOAP;

use PHPUnit\Framework\Exception;
use Illuminate\Support\Facades\Log;
use SoapClient;
use SoapFault;
use stdClass;
use SimpleXMLElement;

class Connection
{

    private $WSDL = "https://wsm.webservice.m2.com.au/WholesaleServiceManagement";
    private $cert_pass;
    private $proxy_enabled;
    private $proxy_host;
    private $proxy_port;

    public function __construct(){
        ### Need to add config files
        ## Check if Authed before sending request.
        $this->cert_pass = config('vocussoap.cert_pass');
        $this->proxy_enabled = config('vocussoap.proxy_enabled');
        $this->proxy_host = config('vocussoap.proxy_host');
        $this->proxy_port = config('vocussoap.proxy_port');
    }

    public function authenticate(){
        $Authed = false;
        $auth_url = "https://wsm.webservice.m2.com.au:9443/login/";
        $cert_pass = $this->cert_pass; # Needs to be pulled from config
        $cert_path = storage_path('protected/production.pem');
        $ca_path = storage_path('protected/entrust_l1k_chain_root.cer');
        $headers = array();
        $headers[] = '';
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $auth_url);
        curl_setopt($curl, CURLOPT_SSLCERT, $cert_path);
        curl_setopt($curl, CURLOPT_SSLCERTPASSWD, $cert_pass);
        curl_setopt($curl, CURLOPT_SSLCERTTYPE, 'PEM');
        curl_setopt($curl, CURLOPT_CAINFO, $ca_path);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        if ($this->proxy_enabled){
            $proxy = '127.0.0.1:4567';
            curl_setopt($curl, CURLOPT_PROXY, $proxy);
            curl_setopt($curl, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
        }

        $result = curl_exec($curl);
        if ($result === false) {
            throw new Exception(curl_error($curl), curl_errno($curl));
        }
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($httpcode === 202){ $Authed = true;}
        curl_close($curl);
        Log::debug("Auth Result: " . $Authed);
        return $Authed;
    }

    public function request($wsdl_obj){
        $wsdl_file = storage_path('protected/WholesaleServiceManagement.wsdl');
        $options = [
            'cache_wsdl'     => WSDL_CACHE_NONE,
            'trace'          => 1,
            'verifypeer' => false,
            'verifyhost' => false,
            'stream_context' => stream_context_create(
                [
                    'http'=> [
                        'user_agent' => 'PHPSoapClient',
                    ],
                    'ssl' => [
                        'verify_peer'       => false,
                        'verify_peer_name'  => false,
                        'allow_self_signed' => true,
                    ]
                ]
            )
        ];
        if ($this->proxy_enabled) {
            $options = [
                'proxy_host' => $this->proxy_host,
                'proxy_port' => $this->proxy_port,
            ];
        }
        $client = new SoapClient($wsdl_file, $options);

        try{
            Log::debug(print_r($wsdl_obj, true));
            switch ($wsdl_obj->reqType) {
                case 'GET':
                    $result = $client->Get($wsdl_obj);
                    break;
                case 'CREATE':
                    $result = $client->Create($wsdl_obj);
                    break;
                default;
                    throw new Exception('CASE NOT MET');
                    break;
                }
        }catch(SoapFault $fault){
            echo("Request Failed");
            Log::error($client->__getLastRequest());
            Log::error($fault->getMessage());
        }
        Log::debug(print_r($result, true));
        return($result);
    }

    public function ConvertPARAMStoOBJ($params){
        $_param = [];
        if (!is_array($params)){
            $_param = $this->ConvertArrayToAscArr($params, $_param);
        } else {
            foreach($params as $param){
                $_param = $this->ConvertArrayToAscArr($param, $_param);
            }
        }
        $o_params = (object)$_param;
        return($o_params);
    }

    public function ConvertArrayToAscArr($param, $_param){
        $param_value = $param->_;
            if (strpos($param->_, '><')){
                $param_value = $this->ConvertXMLtoPHP($param->_);
            }
            if (array_key_exists($param->id, $_param)){
                if (is_array($_param[$param->id])){
                    $_param[$param->id][] = $param_value;
                } else {
                    $_param[$param->id] = array($_param[$param->id]);
                    $_param[$param->id][] = $param_value;
                }
            } else {
                $_param[$param->id] = $param_value;
            }
            return $_param;
    }

    public function ConvertXMLtoPHP($xml){
        $xml = str_replace('<![CDATA[','',$xml);
        $xml = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $xml);
        $s_xml = new SimpleXMLElement($xml);
        $xml_obj = json_decode(json_encode($s_xml));
        return($xml_obj);
    }
}
