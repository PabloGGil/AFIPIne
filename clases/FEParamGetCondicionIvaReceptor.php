<?php
$path_cli = __DIR__ . '/../';
// echo $path_cli;
include_once $path_cli . "clases/class.Auth.php";
class CondicionIvaReceptor{
   private $dataAuth;
   
   
   function __construct()
    {
        $x=new Auth();
        $this->dataAuth=$x->getAuthData();
       
    }
    function getData(){
        $dataAuth=new Auth();
        $data=[
            
            'Auth' =>$this->dataAuth,
         
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
           
            $results=$client->FEParamGetCondicionIvaReceptor($data); 
            file_put_contents("wsfe.xml",$client-> __getLastRequest(),FILE_APPEND);
            file_put_contents("wsfe.xml",$client-> __getLastResponse(),FILE_APPEND); 
            if (is_soap_fault($results)){
                exit("SOAP Fault: ".$results->faultcode."\n".$results->faultstring."\n");
            }
            
            // var_dump($results->FEParamGetCondicionIvaReceptorResult);
            return $results->FEParamGetCondicionIvaReceptorResult;
        }catch(SoapFault $e){
            echo "Error: " . $e->getMessage();
            echo htmlspecialchars(print_r($e, true));
        }
    }
}