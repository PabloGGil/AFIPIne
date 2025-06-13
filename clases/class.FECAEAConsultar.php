<?php
$path_cli = __DIR__ . '/../';
// echo $path_cli;
include_once $path_cli . 'clases/class.Auth.php';
/* --------------------------------
<FeCompConsReq>
    <CbteTipo>int</CbteTipo>
    <CbteNro>long</CbteNro>
    <PtoVta>int</PtoVta>
</FeCompConsReq>
----------------------------------*/

class FECAEAConsultar{
     
    public $dataAuth;
    
    function __construct(  private $periodo, private $orden){
        $x=new Auth();
        $this->dataAuth=new Auth();
        $this->dataAuth=$x->getAuthData();
        $this->dataAuth['Cuit']="27273556333";
        // var_dump($this->dataAuth);

    }

   
    function getData(){
         $dataAuth=new Auth();
        //  $this->dataAuth.setCuit("30711529280");
        $data=[
           
            'Auth' =>$this->dataAuth,
            // 'FeCompConsReq'=>[
                
                'Periodo' =>$this->periodo,
                'Orden' => $this->orden,
                
            // ]
        ];
        $options = [
            'stream_context' => stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
            ]),
        ];
        //   var_dump($data);
        try{
            $client=new SoapClient("https://wswhomo.afip.gov.ar/wsfev1/service.asmx?wsdl", $options);
            $results=$client->FECAEAConsultar($data); 
            file_put_contents("wsfe.xml",$client-> __getLastRequest()); 
            file_put_contents("wsfe.xml",$client-> __getLastResponse()); 
            if (is_soap_fault($results)){
                exit("SOAP Fault: ".$results->faultcode."\n".$results->faultstring."\n");
            }
            return($results->FECAEAConsultarResult);
        }catch(SoapFault $e){
            echo "Error: " . $e->getMessage();
        }
    }
  
}

// $d=new CompConsultar(11,3,10);
// var_dump($d->getData());