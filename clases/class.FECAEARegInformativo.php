<?php
$path_cli = __DIR__ . '/../';
// echo $path_cli;
include_once $path_cli .'clases/class.Auth.php';

class  FECAEARegInformativo{  

    // cabecera ---
    private $CantReg=1;
    private $PtoVta=2;
    private $CbteTipo=11;
    //-------------
    private $Concepto=3;
    private $DocTipo=80;
    private $DocNro=30711529280;
    private $CbteDesde=4160;
    private $CbteHasta=4160;
    private $CbteFch='20241202';
    private $ImpTotal=20000.00;
    private $ImpTotConc=0;
    private $ImpNeto=0;
    private $ImpOpEx=0;
    private $ImpTrib=0;
    private $ImpIVA=0;
    private $MonId='PES';
    private $MonCotiz=1;
   // private $Tipo=0;
   
    private $Nro=4160;
    private $Cuit=30711529280;
    private $dataAuth;
   
    function __construct()
    {
        $x=new Auth();
        $this->dataAuth=$x->getAuthData();
    }
    function getData(){
        
        // $dataAuth=new Auth();
       
        $data=[
            'Auth' =>$this->dataAuth,
            'FECAESolicitar'=>[
                'Auth' =>$this->dataAuth,
                'FeCAEReq'=>[          
                    'FeCabReq'>[
                        'CantReg'=>$this->CantReg,
                        'PtoVta'=>$this->PtoVta,
                        'CbteTipo'=>$this->CbteTipo,
                    ],
                
                    'FeDetReq'=>[
                        'FECAEDetRequest'=>[
                            'Concepto'=>$this->Concepto,
                            'DocTipo'=>$this->DocTipo,
                            'DocNro'=>$this->DocNro,
                            'CbteDesde'=>$this->CbteDesde,
                            'CbteHasta'=>$this->CbteHasta,
                            'CbteFch'=>$this->CbteFch,
                            'ImpTotal'=>$this->ImpTotal,
                            'ImpTotConc'=>$this->ImpTotConc,
                            'ImpNeto'=>$this->ImpNeto,
                            'ImpOpEx'=>$this->ImpOpEx,
                            'ImpTrib'=>$this->ImpTrib,
                            'ImpIVA'=>$this->ImpIVA,
                            'MonId'=>$this->MonId,
                            'MonCotiz'=>$this->MonCotiz,
                            'CbtesAsoc'=>[
                                'CbteAsoc'=>[
                                'Tipo'=>$this->Tipo,
                                'PtoVta'=>$this->PtoVta,
                                'Nro'=>$this->Nro,
                                'Cuit'=>$this->Cuit,
                                'CbteFch'=>$this->CbteFch,
                                ],
                            ],
                        ],
                    ]
                ],
            ],
        ];

        $options = [
            'stream_context' => stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
            ]),
        ];
        //  var_dump($data);
        try{
            $client=new SoapClient("https://wswhomo.afip.gov.ar/wsfev1/service.asmx?wsdl", $options);
            $results=$client->FECAEARegInformativo($data); 
            file_put_contents("wsfe.xml",$client-> __getLastRequest()); 
            file_put_contents("wsfe.xml",$client-> __getLastResponse()); 
            if (is_soap_fault($results)){
                exit("SOAP Fault: ".$results->faultcode."\n".$results->faultstring."\n");
            }
            // var_dump($results->FECAEARegInformativoResult);

            return $results->FECAEARegInformativoResult;
        }catch(SoapFault $e){
            echo "Error: " . $e->getMessage();
        }
    }


}