
<?php
# Autor: Gerardo Fisanotti (AFIP, 2007)
# Descripción: Obtener un ticket de acceso (TA) del WSAA
# Entrada:
# WSDL, CERT, PRIVATEKEY, PASSPHRASE, SERVICE, URL
# Ver las definiciones abajo
# Salida:
# TA.xml: El ticket de acceso otorgado por el WSAA
// define ("RPATH",__DIR__ . '/../');
include_once RPATH .'config/class.Config.php';



/* --------------------------------------------------------
  Crea archivo xml para solicitar el Ticket de acceso
----------------------------------------------------------*/
class WSAA{

    private $token;
    private $sign;
    private $cuit;
    private $service;

    function __construct($serv)
    {
       
        $service=$serv; 
        // veriicar si existen los archivos
        if (!file_exists(RPATH .Config::TKT_AUTH)) {

            // echo "<br>el archivo  ".Config::TKT_AUTH. "no existe; se va a crear" ;
            $this->CreaLoginTkt('wsfe');
            $cms=$this->FirmaLoginTkt();
            $auth=$this->CallWSAA($cms);
        }
        elseif($this->tktExpirado() || !file_exists(Config::TKT_AUTH)){
            // $wsaa=new WSAA();
            $this->CreaLoginTkt('wsfe');
            $cms=$this->FirmaLoginTkt();
            $auth=$this->CallWSAA($cms);
            // var_dump($auth);
        }
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
    // Indica si el tichet esta expirado
    function tktExpirado(){
        date_default_timezone_set('UTC');
        $fechaExpiracion=$this->get_expiration();
        $fechaActual=date('c');
        // var_dump($fechaActual);
        if ($fechaActual < $fechaExpiracion) {
            // echo "<br>La fecha aún no ha expirado.";
            return false;
        } else {
            // echo "<br>La fecha ha expirado.";
            return true;
        }
        
        // $r = DateTime($nose['header']['expirationTime']);
    
    }

    function get_expiration()
    {
        $ta = fopen(RPATH . Config::TKT_AUTH, 'r');
        
        $xmlstr = stream_get_contents($ta);
        fclose($ta);
        $tadata = new SimpleXMLElement($xmlstr);
        $r =$tadata->header->expirationTime;
        return $r;
    }

    function xml2array($xml)
    {
            $json = json_encode(simplexml_load_string($xml));
            return json_decode($json, true);
    }

    function CreaLoginTkt($service="")
    {
        $TRA = new SimpleXMLElement(
        '<?xml version="1.0" encoding="UTF-8"?>' .
        '<loginTicketRequest version="1.0">'.
        '</loginTicketRequest>');
        $TRA->addChild('header');
        $TRA->header->addChild('uniqueId',date('U'));
        $TRA->header->addChild('generationTime',date('c',date('U')-60));
        $TRA->header->addChild('expirationTime',date('c',date('U')+86400));
        $TRA->addChild('service',$service);
        $TRA->asXML( RPATH . Config::LOG_TKT);
    }

/* --------------------------------------------------------
 Firma el LoginTicket 
 Ingresa el login Ticket el certificado y la privada. 
 Genera un archivo intermedio LOG_TKTF.tmp y le quita los encabezados MIME para obtener el valor que hay que hay que carhar en in0 .
----------------------------------------------------------*/
    function FirmaLoginTkt()
    {
        
        if (!file_exists(RPATH . Config::LOG_TKT)) {
            exit("Failed to open". Config::LOG_TKT ."\n");
        }
    // $STATUS=openssl_pkcs7_sign("../archivos/TRA.xml", "../archivos/TRA.tmp", CERT,array(PRIVATEKEY),array(),!PKCS7_DETACHED);
    
        $command = "openssl cms -sign -in  ". RPATH . Config::LOG_TKT ." -out ". RPATH. Config::LOG_TKT_FIRM. " -signer ". RPATH. Config::CERT ." -inkey ".RPATH.  Config::PRIVATEKEY ." -nodetach -outform PEM";
      
        exec($command, $output, $STATUS);
        // $STATUS=openssl_pkcs7_sign("../archivos/TRA.xml","../archivos/TRA.tmp",CERT,array(PRIVATEKEY),array(),PKCS7_DETACHED);
        
        if ($STATUS) {
            exit("ERROR generating PKCS#7 signature\n");
        }
        $inf=fopen(RPATH . Config::LOG_TKT_FIRM, "r");
        $i=0;
        $CMS="";
        $nroFilas=count(file(RPATH . Config::LOG_TKT_FIRM));
        while (!feof($inf))
        {
            
            $buffer=fgets($inf);
            // print_r($i ."---".$buffer);
            if ( $i>0 and $i< $nroFilas-1 ) {
                $CMS.=$buffer;
            }
            $i++;
        }
        fclose($inf);
    // unlink($inf);
        return $CMS;
    }


    function CallWSAA($CMS)
    {
        try{
            $options = [
                'stream_context' => stream_context_create([
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ],
                ]),
            ];
            $client=new SoapClient(RPATH . Config::WSDL, $options);
            $results=$client->loginCms(array('in0'=>$CMS)); 
            file_put_contents("requestloginCms.xml",$client-> __getLastRequest()); 
            file_put_contents("responseloginCms.xml",$client-> __getLastResponse()); 
            if (is_soap_fault($results)){
                exit("SOAP Fault: ".$results->faultcode."\n".$results->faultstring."\n");
            } 
            // var_dump($results->loginCmsReturn);
            $tktauth=fopen(RPATH . Config::TKT_AUTH,"w");
            fwrite($tktauth,$results->loginCmsReturn);
            fclose($tktauth);
            return  $results->loginCmsReturn;

        }
        catch (SoapFault $e){
            echo "<br>Error: " . $e->getMessage();
        }
    }
}
