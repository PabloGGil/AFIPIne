<?php
//include_once 'wsaa-client.php';
// define ("CUIT", 27273556333);
define ("RPATH",__DIR__ . '/../');
include_once RPATH .'config/class.Config.php';

class Auth{

    private $token;
    private $sign;
    private $cuit;

    function __construct( ){
        $wsa=new WSAA("wsfe");
        $ta = fopen(RPATH . Config::TKT_AUTH, "rb");
        $xmlstr = stream_get_contents($ta);
        fclose($ta);
        $tadata = new SimpleXMLElement($xmlstr);

        $this->token=$tadata->credentials->token;
        $this->sign=$tadata->credentials->sign;
        $qit=$tadata->header->destination;
       
        preg_match('/CUIT (\d{11})/', $qit, $matches);
        $this->cuit=intval($matches[1]);
        
    }
    public function getToken(){
        return $this->token;
    }
    public function getSign(){
        return $this->sign;
    }
    public function getCuit(){
        return $this->cuit;
    }
    public function setToken($token){
        $this->token=$token;
    }

    public function setSign($sign){
        $this->sign=$sign;
    }

    public function setCuit($cuit){
        $this->cuit=$cuit;
    }

    public function getAuthData() {
        $data=[
            'Token' => $this->token,
            'Sign' => $this->sign,
            'Cuit' => $this->cuit,
        ];
        //var_dump($data);
        return $data; 
    }
}