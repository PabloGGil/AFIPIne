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
include_once "clases/class.FECAEAConsultar.php";
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


/*------------------------------------------------------------
--- FECCAESolicitar 
--------------------------------------------------------------*/
// $auth=new Auth();
// $dataAuth=$auth->getAuthData();
// var_dump($dataAuth);
// $tipoConcepto=new TiposConcepto();
// $tipoComprobante=new ParamGetTiposCbte();
// var_dump($tipoComprobante->getData());
// $fecae=new FECAEARegInformativo();
// $fecae->getData();
 
// var_dump($x->CbteNro);
 // $aux=new CondicionIvaReceptor();
// var_dump($aux->getData());
// $fecae=new CompConsultar(11,6,4);
$fecae=new FECAEAConsultar(202503,2);

$result=$fecae->getData();
var_dump($result);
// $fecae=new FECAESolicitar();
// $fecae->getData();


/*------------------------------------------------------------
--- FECCAEConsultar
--------------------------------------------------------------*/


//  function xml2array($xml)
//     {
//         $json = json_encode(simplexml_load_string($xml));
//         return json_decode($json, true);
//     }