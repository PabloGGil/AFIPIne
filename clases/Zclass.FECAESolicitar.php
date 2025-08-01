<?php
$path_cli = __DIR__ . '/../';
// echo $path_cli;
include_once $path_cli .'clases/class.Auth.php';
include_once $path_cli . 'clases/FECompUltimoAutorizado.php';
include_once $path_cli . 'clases/class.CompAsociados.php';

class  ZFECAESolicitar{  

    // cabecera ---
    private $CantReg;
    private $PtoVta;
    private $CbteTipo;
    //-------------
    private $Concepto;
    private $DocTipo;
    private $DocNro;
    // private $CbteDesde=2;
    // private $CbteHasta=2;
    private $CbteFch;
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
   
    private $cbtesAsociados=array();
    private $cbteAsocTipo=0;
    private $cbteAsocPtoVta=0;
    private $cbteAsocNro=4160;
    private $cbteAsocCuit=30711529280;
    private $cbteAsocCbteFch="";
    
    private $tributos=array();
    private $tribId=0;
    private $tribDesct=0;
    private $tribBaseImp=0;
    private $tribAlic=0;
    private $tribImporte=0;

    private $iva=array();
    private $ivaId=15;
    private $ivaBaseImp=0;
    private $ivaImporte=0;

    private $opcionales=array();
    private $opcId;
    private $opcValor;

    private $compradores=array();
    private $compradorDocTipo;
    private $compradorDocNro;
    private $compradorPorcentaje;

    private $periodoAsociados=array();
    private $pasocFchDesde;
    private $pasocFchHasta;
    
    private $actividades=array();
    private $actId;
    // private $CondicionIVAReceptorId=15;
    private $dataAuth;
    // private $data;


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
        $this->PtoVta=$reg['PtoVta'];
        $this->CbteTipo=$reg['CbteTipo'];
        
        $this->Concepto=$reg['Concepto'];
        $this->DocTipo=$reg['DocTipo'];
        $this->DocNro=$reg['DocNro'];
        // $this->CbteDesde=$reg['CbteDesde'];
        // $this->CbteHasta=$reg['CbteHasta'];
        $this->CbteFch=$reg['CbteFch'];
        $this->ImpTotal=$reg['ImpTotal'];
        $this->ImpTotConc=$reg['ImpTotConc'];
        $this->ImpNeto=$reg['ImpNeto'];
        $this->ImpOpEx=$reg['ImpOpEx'];
        $this->ImpTrib=$reg['ImpTrib'];
        $this->ImpIVA=$reg['ImpIVA'];
        $this->FchServDesde=$reg['ImpIVA'];
        $this->FchServHasta=$reg['ImpIVA'];
        $this->FchVtoPago=$reg['ImpIVA'];
        $this->MonId=$reg['MonId'];
        $this->MonCotiz=$reg['MonCotiz'];
        
        if(isset($reg['CbtesAsoc'])){
             $this->cbtesAsociados=$reg['CbtesAsoc'];
         }else{$this->cbtesAsociados=null;}
        // $this->cbteAsocTipo=$reg['CbtesAsoc']['Tipo'];
        // $this->cbteAsocPtoVta=$reg['CbtesAsoc']['PtoVta'];
        // $this->cbteAsocNro=$reg['CbtesAsoc']['Nro'];
        // $this->cbteAsocCuit=$reg['CbtesAsoc']['Cuit'];
        // $this->cbteAsocCbteFch=$reg['CbtesAsoc']['CbteFch'];
       if (isset($reg['Tributos'])){
            $this->tributos=$reg['Tributos'];
       }else{$this->tributos=null;}
        // $this->tribId=$reg['Tributos']['Id'];
        // $this->tribDesct=$reg['Tributos']['Desc'];
        // $this->tribBaseImp=$reg['Tributos']['BaseImp'];
        // $this->tribAlic=$reg['Tributos']['Alic'];
        // $this->tribImporte=$reg['Tributos']['Importe'];
        if (isset($reg['Iva'])){
            $this->iva=$reg['Iva'];
        }
        else{  $this->iva=null;      }
        
        // $this->ivaId=$reg['Iva']['Id'];
        // $this->ivaBaseImp=$reg['Iva']['BaseImp'];
        // $this->ivaImporte=$reg['Iva']['Importe'];
        if (isset($reg['Opcionales'])){
            $this->opcionales=$reg['Opcionales'];
        }else{$this->opcionales=null;}
        // $this->opcionales=$reg['Opcionales'];        
        // $this->opcId=$reg['Opcionales']['Id'];
        // $this->opcValor=$reg['Opcionales']['Valor'];
        if (isset($reg['Compradores'])){
            $this->compradores=$reg['Compradores'];
        }else{$this->compradores=null;}
        // $this->compradores=$reg['Compradores'];
        // $this->compradorDocTipo=$reg['Compradores']['DocTipo'];
        // $this->compradorDocNro=$reg['Compradores']['DocNro'];
        // $this->compradorPorcentaje=$reg['Compradores']['Porcentaje'];
        if (isset($reg['PeriodoAsoc'])){
            $this->periodoAsociados=$reg['PeriodoAsoc'];
        }else{$this->periodoAsociados=null;}
        // $this->periodoAsociados=$reg['PeriodoAsoc'];
        // $this->pasocFchDesde=$reg['PeriodoAsoc']['FchDesde'];
        // $this->pasocFchHasta=$reg['PeriodoAsoc']['FchDesde'];
        if (isset($reg['Actividades'])){
            $this->actividades=$reg['Actividades'];
        }else{ $this->actividades=null;}
        // $this->actividades=$reg['Actividades'];
        // $this->actId=$reg['Actividades']['Id'];
    
    }
    function sendData(){
        // sleep(5);
        // Obtener el ultimo nro de comprobante
        $ultimo=new UltimoAutorizado($this->PtoVta,$this->CbteTipo);
        $prox=($ultimo->getData())+1 ;
        // echo $prox;
        if($this->cbtesAsociados!=null){
            $data[]=['FeCAEReq'=>['FeDetReq'=>['FECAEDetRequest'=>['CbtesAsoc'=>$this->cbtesAsociados]]]];
        }
        if($this->tributos!=null){
            $data[]=['FeCAEReq'=>['FeDetReq'=>['FECAEDetRequest'=>['Tributos'=>$this->tributos]]]];
        }

        if($this->iva!=null){
            $data[]=['FeCAEReq'=>['FeDetReq'=>['FECAEDetRequest'=>['Iva'=>$this->iva]]]];
        }
 
        if($this->opcionales!=null){
            $data[]=['FeCAEReq'=>['FeDetReq'=>['FECAEDetRequest'=>['Opcionales'=>$this->opcionales]]]];
        }
 
        if($this->compradores!=null){
            $data[]=['FeCAEReq'=>['FeDetReq'=>['FECAEDetRequest'=>['Compradores'=>$this->compradores]]]];
        }           
 
        if($this->periodoAsociados!=null){
            $data[]=['FeCAEReq'=>['FeDetReq'=>['FECAEDetRequest'=>['PeriodoAsoc'=>$this->periodoAsociados]]]];
        }
 
        if($this->actividades!=null){
            $data[]=['FeCAEReq'=>['FeDetReq'=>['FECAEDetRequest'=>['Actividades'=>$this->actividades]]]];
        }
        $data=[
            //  'FECAESolicitar'=>[           
                'Auth' =>$this->dataAuth,
 
                'FeCAEReq'=>[          
                    'FeCabReq'=>[
                        'CantReg'=>$this->CantReg,
                        'PtoVta'=>$this->PtoVta,
                        'CbteTipo'=>$this->CbteTipo,
                    ],
                    'FeDetReq'=>[
                        'FECAEDetRequest'=>[
                            'CondicionIVAReceptorId' => 6,
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
                           
                            // 'CbtesAsoc'=>$this->cbtesAsociados,
                           
                            // 'Tributos'=>$this->tributos,

                            //  'Iva'=>$this->iva,
 
                            //  'Opcionales'=>$this->opcionales,
 
                            //  'Compradores'=>$this->compradores,           
 
                            //  'PeriodoAsoc'=>$this->periodoAsociados,
 
                            //  'Actividades'=>$this->actividades,
                        ],
                    ],
                ],
            //  ],
        ];
        // var_dump($data);
        $options = [
            'trace'=> 1,
            'stream_context' => stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
                
            ]),
        ];

        // $this->CbteDesde=
        //  var_dump($data);
        try{
            $servicio= 'FECAESolicitar';
            // $client=new SoapClient("https://wswhomo.afip.gov.ar/wsfev1/service.asmx?op=".$servicio, $options);
            $client=new SoapClient("https://wswhomo.afip.gov.ar/wsfev1/service.asmx?wsdl", $options);
            $results=$client->FECAESolicitar($data); 
            file_put_contents("LastRequest.xml",$client-> __getLastRequest(),FILE_APPEND); 
            file_put_contents("LastResponse.xml",$client-> __getLastResponse(),FILE_APPEND); 
            if (is_soap_fault($results)){
                exit("Error en cliente SOAP: ".$results->faultcode."\n".$results->faultstring."\n");
            }
            // var_dump($results->FECAESolicitarResult);
            return $results->FECAESolicitarResult;
        }catch(SoapFault $e){
            echo "Error: " . $e->getMessage();
        }
    }

}