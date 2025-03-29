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
    private $FchServDesde="";
    private $FchServHasta="";
    private $FchVtoPago="";
    private $MonId='PES';
    private $MonCotiz=1;
   
    private $cbteAsocTipo=0;
    private $cbteAsocPtoVta=0;
    private $cbteAsocNro=4160;
    private $cbteAsocCuit=30711529280;
    private $cbteAsocCbteFch="";

    private $tribId=0;
    private $tribDesct=0;
    private $tribBaseImp=0;
    private $tribAlic=0;
    private $tribImporte=0;

    private $ivaId=15;
    private $ivaBaseImp=0;
    private $ivaImporte=0;

    private $opcId;
    private $opcValor;

    private $compradorDocTipo;
    private $compradorDocNro;
    private $compradorPorcentaje;

    private $pasocFchDesde;
    private $pasocFchHasta;
    
    private $actId;
    // private $CondicionIVAReceptorId=15;
    private $dataAuth;


    function __construct($reg)
    {
        $x=new Auth();
        $this->dataAuth=$x->getAuthData();
        $this->CantReg=1;
        $this->CargaRegistro($reg);
        // $tok=$reg['Auth']['token'];
        
        // $this->CondicionIVAReceptorId=15;
    }
   
    function CargaRegistro($reg){
        $this->PtoVta=$reg['FeCAEReq']['FeCabReq']['PtoVta'];
        $this->CbteTipo=$reg['FeCAEReq']['FeCabReq']['CbteTipo'];
        
        $this->Concepto=$reg['FeCAEReq']['FeDetReq']['FECAEDetRequest']['Concepto'];
        $this->DocTipo=$reg['FeCAEReq']['FeDetReq']['FECAEDetRequest']['DocTipo'];
        $this->DocNro=$reg['FeCAEReq']['FeDetReq']['FECAEDetRequest']['DocNro'];
        $this->CbteDesde=$reg['FeCAEReq']['FeDetReq']['FECAEDetRequest']['CbteDesde'];
        $this->CbteHasta=$reg['FeCAEReq']['FeDetReq']['FECAEDetRequest']['CbteHasta'];
        $this->CbteFch=$reg['FeCAEReq']['FeDetReq']['FECAEDetRequest']['CbteFch'];
        $this->ImpTotal=$reg['FeCAEReq']['FeDetReq']['FECAEDetRequest']['ImpTotal'];
        $this->ImpTotConc=$reg['FeCAEReq']['FeDetReq']['FECAEDetRequest']['ImpTotConc'];
        $this->ImpNeto=$reg['FeCAEReq']['FeDetReq']['FECAEDetRequest']['ImpNeto'];
        $this->ImpOpEx=$reg['FeCAEReq']['FeDetReq']['FECAEDetRequest']['ImpOpEx'];
        $this->ImpTrib=$reg['FeCAEReq']['FeDetReq']['FECAEDetRequest']['ImpTrib'];
        $this->ImpIVA=$reg['FeCAEReq']['FeDetReq']['FECAEDetRequest']['ImpIVA'];
        $this->FchServDesde=$reg['FeCAEReq']['FeDetReq']['FECAEDetRequest']['ImpIVA'];
        $this->FchServHasta=$reg['FeCAEReq']['FeDetReq']['FECAEDetRequest']['ImpIVA'];
        $this->FchVtoPago=$reg['FeCAEReq']['FeDetReq']['FECAEDetRequest']['ImpIVA'];
        $this->MonId=$reg['FeCAEReq']['FeDetReq']['FECAEDetRequest']['MonId'];
        $this->MonCotiz=$reg['FeCAEReq']['FeDetReq']['FECAEDetRequest']['MonCotiz'];
        
        $this->cbteAsocTipo=$reg['FeCAEReq']['FeDetReq']['FECAEDetRequest']['CbtesAsoc']['Tipo'];
        $this->cbteAsocPtoVta=$reg['FeCAEReq']['FeDetReq']['FECAEDetRequest']['CbtesAsoc']['PtoVta'];
        $this->cbteAsocNro=$reg['FeCAEReq']['FeDetReq']['FECAEDetRequest']['CbtesAsoc']['Nro'];
        $this->cbteAsocCuit=$reg['FeCAEReq']['FeDetReq']['FECAEDetRequest']['CbtesAsoc']['Cuit'];
        $this->cbteAsocCbteFch=$reg['FeCAEReq']['FeDetReq']['FECAEDetRequest']['CbtesAsoc']['CbteFch'];
       

        $this->tribId=$reg['FeCAEReq']['FeDetReq']['FECAEDetRequest']['Tributos']['Id'];
        $this->tribDesct=$reg['FeCAEReq']['FeDetReq']['FECAEDetRequest']['Tributos']['Desc'];
        $this->tribBaseImp=$reg['FeCAEReq']['FeDetReq']['FECAEDetRequest']['Tributos']['BaseImp'];
        $this->tribAlic=$reg['FeCAEReq']['FeDetReq']['FECAEDetRequest']['Tributos']['Alic'];
        $this->tribImporte=$reg['FeCAEReq']['FeDetReq']['FECAEDetRequest']['Tributos']['Importe'];

        $this->ivaId=$reg['FeCAEReq']['FeDetReq']['FECAEDetRequest']['Iva']['Id'];
        $this->ivaBaseImp=$reg['FeCAEReq']['FeDetReq']['FECAEDetRequest']['Iva']['BaseImp'];
        $this->ivaImporte=$reg['FeCAEReq']['FeDetReq']['FECAEDetRequest']['Iva']['Importe'];
        
        $this->opcId=$reg['FeCAEReq']['FeDetReq']['FECAEDetRequest']['Opcionales']['Id'];
        $this->opcValor=$reg['FeCAEReq']['FeDetReq']['FECAEDetRequest']['Opcionales']['Valor'];

        $this->compradorDocTipo=$reg['FeCAEReq']['FeDetReq']['FECAEDetRequest']['Compradores']['DocTipo'];
        $this->compradorDocNro=$reg['FeCAEReq']['FeDetReq']['FECAEDetRequest']['Compradores']['DocNro'];
        $this->compradorPorcentaje=$reg['FeCAEReq']['FeDetReq']['FECAEDetRequest']['Compradores']['Porcentaje'];

        $this->pasocFchDesde=$reg['FeCAEReq']['FeDetReq']['FECAEDetRequest']['PeriodoAsoc']['FchDesde'];
        $this->pasocFchHasta=$reg['FeCAEReq']['FeDetReq']['FECAEDetRequest']['PeriodoAsoc']['FchDesde'];

        $this->actId=$reg['FeCAEReq']['FeDetReq']['FECAEDetRequest']['Actividades']['Id'];
    
    }
    function sendData(){
        // Obtener el ultimo nro de comprobante
        $ultimo=new UltimoAutorizado($this->cbtePtoVta,$this->cbteTipo);
        $prox=($ultimo->getData())+1 ;
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
                            // 'CondicionIVAReceptorId'=>$this->CondicionIVAReceptorId,
                            'Concepto'=>$this->Concepto,
                            'DocTipo'=>$this->DocTipo,
                            'DocNro'=>$this->DocNro,
                            'CbteDesde'=>$prox,
                            'CbteHasta'=>$prox,
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
                                'Tipo'=>$this->cbteTipo,
                                'PtoVta'=>$this->cbtePtoVta,
                                'Nro'=>$this->cbteNro,
                                'Cuit'=>$this->cbteCuit,
                                'CbteFch'=>$this->cbteCbteFch,
                                ],
                            ],
                            'Tributos'=>[
                                'Tributo'=>[
                                    'Id'=>$this->tribId,
                                    'Desc'=>$this->tribDesct,
                                    'BaseImp'=>$this->tribBaseImp,
                                    'Alic'=>$this->tribAlic,
                                    'Importe'=>$this->tribImporte,
                                ],
                            ],
                            'Iva'=>[
                                'AlicIvac'=>[
                                    'Id'=>$this->ivaId,
                                    'BaseImp'=>$this->ivaBaseImp,
                                    'Importe'=>$this->ivaImporte,
                                ],
                            ],
                            'Opcionales'=>[
                                'Opcional'=>[
                                        'Id'=> $this->opcId,
                                        'Valor'=>$this->opcValor,
                                ],
                            ],
                            'Compradores'=>[
                                'Comprador'=>[
                                    'DocTipo'=>$this->compradorDocTipo,
                                    'DocNro'=>$this->compradorDocNro,
                                    'Porcentaje'=>$this->compradorPorcentaje,
                                ],
                            ],              
                            'PeriodoAsoc'=>[
                                'FchDesde'=>$this->pasocFchDesde,
                                'FchHasta'=>$this->pasocFchHasta,
                            ],
                            'Actividades'=>[
                                'Actividad'=>[
                                    'Id'=>$this->actId,
                                ],
                            ],
                            
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

        $this->CbteDesde=
         var_dump($data);
        try{
            $servicio= 'FECAESolicitar';
            $client=new SoapClient("https://wswhomo.afip.gov.ar/wsfev1/service.asmx?op=".$servicio, $options);
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