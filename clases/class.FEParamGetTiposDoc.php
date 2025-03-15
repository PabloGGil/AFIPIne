<?php
$path_cli = __DIR__ . '/../';

include_once $path_cli ."clases/class.Auth.php";
class TiposDoc{
   private $dataAuth;
    function __construct()
    {
        $x=new Auth();
        $this->dataAuth=$x->getAuthData();
        // var_dump($this->dataAuth);
    }
    function getData(){
        $dataAuth=new Auth();
        $data=[
            
            'Auth' =>$this->dataAuth
          
        ];
        $options = [
            'stream_context' => stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
            ]),
        ];
        // var_dump($data);
        try{
            $client=new SoapClient("https://wswhomo.afip.gov.ar/wsfev1/service.asmx?wsdl", $options);
           
            $results=$client->FEParamGetTiposDoc($data); 
            file_put_contents("wsfe.xml",$client-> __getLastRequest()); 
            file_put_contents("wsfe.xml",$client-> __getLastResponse()); 
            if (is_soap_fault($results)){
                exit("SOAP Fault: ".$results->faultcode."\n".$results->faultstring."\n");
            }
            
            // var_dump($results->FEParamGetTiposDocResult);
            return $results->FEParamGetTiposDocResult;
        }catch(SoapFault $e){
            echo "Error: " . $e->getMessage();
            echo htmlspecialchars(print_r($e, true));
        }
    }
}