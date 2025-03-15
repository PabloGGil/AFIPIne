<?php

include_once "clases/class.Auth.php";
include_once "clases/class.wsaa.php";
include_once "clases/class.FECAESolicitar.php";
include_once "clases/class.FECompConsultar.php";
include_once "clases/class.FEParamGetTiposCbte.php";
include_once "clases/class.FECAEARegInformativo.php";
include_once "clases/class.FEParamGetTiposDoc.php";
include_once "clases/class.TiposConcepto.php";
include_once "clases/FECompUltimoAutorizado.php";
include_once "clases/FEParamGetCondicionIvaReceptor.php";

define ("ROOT_DIR",'.');
define ("WSDL", "clases/wsaa.wsdl"); # WSDL correspondiente al WSAA
define ("CERT", "certs/PabloTest.pem"); # Certificado usado para firmar
define ("PRIVATEKEY", "certs/PabloTest.key"); # Clave privada del certificado
define ("PASSPHRASE", "metro100"); # Password para firmar
define ("PROXY_HOST", ""); # IP del proxy para salir a Internet
define ("PROXY_PORT", ""); # Puerto del proxy
define ("URL", "https://wsaahomo.afip.gov.ar/ws/services/LoginCms");
define ("LOG_TKT", "archivos/LoginTicket.xml"); // ticket de acceso
define ("LOG_TKT_FIRM", "archivos/TAF.tmp"); // ticket de acceso firmado
define ("CUIT", 20149576798);
define ("TKT_AUTH","archivos/tktaut.xml");

if(tktExpirado()){
    $wsaa=new WSAA();
    $wsaa->CreaLoginTkt('wsfe');
    $cms=$wsaa->FirmaLoginTkt();
    $auth=$wsaa->CallWSAA($cms);
    var_dump($auth);
}
/*------------------------------------------------------------
--- FECCAESolicitar 
--------------------------------------------------------------*/
$auth=new Auth();
$dataAuth=$auth->getAuthData();
var_dump($dataAuth);
// $tipoConcepto=new TiposConcepto();
// $tipoComprobante=new ParamGetTiposCbte();
// var_dump($tipoComprobante->getData());
// $fecae=new FECAEARegInformativo();
// $fecae->getData();
 $fecae=new UltimoAutorizado(2,11);
 $fecae->getData();
// $aux=new CondicionIvaReceptor();
// var_dump($aux->getData());
// $fecae=new CompConsultar(11,2,2);
// $fecae->getData();
// $fecae=new FECAESolicitar();
// $fecae->getData();


/*------------------------------------------------------------
--- FECCAEConsultar
--------------------------------------------------------------*/
// $auth=new Auth();
// $dataAuth=$auth->getAuthData();

// $fecae=new FECompConsultar(11,4160,2);
// $fecae->getData();

/* Devuelve la fecha de expiracion del ticket */
function get_expiration()
{
    $ta = fopen(TKT_AUTH, 'r');
       
    $xmlstr = stream_get_contents($ta);
    fclose($ta);
    $tadata = new SimpleXMLElement($xmlstr);
    $r =$tadata->header->expirationTime;
    return $r;
}
// Indica si el tichet esta expirado
function tktExpirado(){
    date_default_timezone_set('UTC');
    $fechaExpiracion=get_expiration();
    $fechaActual=date('c');
    var_dump($fechaActual);
    if ($fechaActual < $fechaExpiracion) {
        echo "La fecha aún no ha expirado.";
        return false;
    } else {
        echo "La fecha ha expirado.";
        return true;
    }
    
    // $r = DateTime($nose['header']['expirationTime']);
   
}

 function xml2array($xml)
    {
        $json = json_encode(simplexml_load_string($xml));
        return json_decode($json, true);
    }