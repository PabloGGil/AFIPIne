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

class CompConsultar{
     
    private $dataAuth;
    
    function __construct(  private $cbtetipo, private $cbtenro, private $ptovta){
        $x=new Auth();
        $this->dataAuth=$x->getAuthData();
        // $this->dataAuth['Cuit']=30711529280;
        // var_dump($this->dataAuth);

    }

   
    function getData(){
        $dataAuth=new Auth();
        $data=[
            
            'Auth' =>$this->dataAuth,
            'FeCompConsReq'=>[
                
                'CbteNro' =>$this->cbtenro,
                'PtoVta' => $this->ptovta,
                'CbteTipo' =>$this->cbtetipo,
            ]
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
            $results=$client->FECompConsultar($data); 
            file_put_contents("wsfe.xml",$client-> __getLastRequest()); 
            file_put_contents("wsfe.xml",$client-> __getLastResponse()); 
            if (is_soap_fault($results)){
                exit("SOAP Fault: ".$results->faultcode."\n".$results->faultstring."\n");
            }
            return($results->FECompConsultarResult);
        }catch(SoapFault $e){
            echo "Error: " . $e->getMessage();
        }
    }
  
}