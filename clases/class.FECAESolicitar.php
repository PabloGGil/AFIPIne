<?php
$path_cli = __DIR__ . '/../';
echo $path_cli;
include_once $path_cli .'clases/class.Auth.php';

class  FECAESolicitar{  

    // cabecera ---
    private $CantReg=1;
    private $PtoVta=2;
    private $CbteTipo=11;
    //-------------
    private $Concepto=1;
    private $DocTipo=80;
    private $DocNro=30711529280;
    private $CbteDesde=2;
    private $CbteHasta=2;
    private $CbteFch='20250206';
    private $ImpTotal=20000;
    private $ImpTotConc=0;
    private $ImpNeto=20000;
    private $ImpOpEx=0;
    private $ImpTrib=0;
    private $ImpIVA=0;
    private $MonId='PES';
    private $MonCotiz=1;
   // private $Tipo=0;
   
    private $Nro=4160;
    private $Cuit=30711529280;

    private $idt=0;
    private $Desct=0;
    private $baseimpt=0;
    private $alict=0;
    private $importet=0;
    private $idi=15;
    private $baseimpi=0;
    private $importei=0;
    private $CondicionIVAReceptorId=15;
    private $dataAuth;
    function __construct()
    {
        $x=new Auth();
        $this->dataAuth=$x->getAuthData();
    }
    function getData(){
        // $dataAuth=new Auth();
        // var_dump($dataAuth);
        $data=[
            // 'FECAESolicitar'=>[           
                'Auth' =>$this->dataAuth,
 
                'FeCAEReq'=>[          
                    'FeCabReq'=>[
                        'CantReg'=>$this->CantReg,
                        'PtoVta'=>$this->PtoVta,
                        'CbteTipo'=>$this->CbteTipo,
                    ],
                    'FeDetReq'=>[
                        'FECAEDetRequest'=>[
                            'CondicionIVAReceptorId'=>$this->CondicionIVAReceptorId,
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
                                'Tipo'=>$this->CbteTipo,
                                'PtoVta'=>$this->PtoVta,
                                'Nro'=>$this->Nro,
                                'Cuit'=>$this->Cuit,
                                'CbteFch'=>$this->CbteFch,
                                ],
                            ],
                            // 'Tributos'=>[
                            //     'Tributo'=>[
                            //         'Id'=>$this->idt,
                            //         'Desc'=>$this->Desct,
                            //         'BaseImp'=>$this->baseimpt,
                            //         'Alic'=>$this->alict,
                            //         'Importe'=>$this->importet,
                            //     ],
                            // ],
                            // 'Iva'=>[
                            //     'AlicIvac'=>[
                            //         'Id'=>$this->idi,
                            //         'BaseImp'=>$this->baseimpi,
                            //         'Importe'=>$this->importei,
                            //     ],
                            // ],

                        ],
                    ]
                ],
            // ],
        ];

        $options = [
            'trace'=> 1,
            'stream_context' => stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
                
            ]),
        ];
         var_dump($data);
        try{
            $client=new SoapClient("https://wswhomo.afip.gov.ar/wsfev1/service.asmx?wsdl", $options);
            $results=$client->FECAESolicitar($data); 
            file_put_contents("LastRequest.xml",$client-> __getLastRequest(),FILE_APPEND); 
            file_put_contents("LastResponse.xml",$client-> __getLastResponse(),FILE_APPEND); 
            if (is_soap_fault($results)){
                exit("Error en cliente SOAP: ".$results->faultcode."\n".$results->faultstring."\n");
            }
            var_dump($results->FECAESolicitarResult);
            return $results->FECAESolicitarResult;
        }catch(SoapFault $e){
            echo "Error: " . $e->getMessage();
        }
    }


}