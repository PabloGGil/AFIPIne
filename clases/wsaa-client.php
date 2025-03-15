
<?php
# Autor: Gerardo Fisanotti (AFIP, 2007)
# Descripción: Obtener un ticket de acceso (TA) del WSAA
# Entrada:
# WSDL, CERT, PRIVATEKEY, PASSPHRASE, SERVICE, URL
# Ver las definiciones abajo
# Salida:
# TA.xml: El ticket de acceso otorgado por el WSAA



define ("WSDL", "wsaa.wsdl"); # WSDL correspondiente al WSAA
define ("CERT", "../certs/ine.pem"); # Certificado usado para firmar
define ("PRIVATEKEY", "../certs/ine.key"); # Clave privada del certificado
define ("PASSPHRASE", "metro100"); # Password para firmar
define ("PROXY_HOST", ""); # IP del proxy para salir a Internet
define ("PROXY_PORT", ""); # Puerto del proxy
define ("URL", "https://wsaahomo.afip.gov.ar/ws/services/LoginCms");
define ("LOG_TKT", "../archivos/LoninTicket.xml"); // ticket de acceso
define ("LOG_TKT_FIRM", "../archivos/TAF.tmp"); // ticket de acceso firmado
define ("CUIT", 27273556333);

/* --------------------------------------------------------
  Crea archivo xml para solicitar el Ticket de acceso
----------------------------------------------------------*/
function CreaLoginTkt($SERVICE)
{
    $TRA = new SimpleXMLElement(
    '<?xml version="1.0" encoding="UTF-8"?>' .
    '<loginTicketRequest version="1.0">'.
    '</loginTicketRequest>');
    $TRA->addChild('header');
    $TRA->header->addChild('uniqueId',date('U'));
    $TRA->header->addChild('generationTime',date('c',date('U')-60));
    $TRA->header->addChild('expirationTime',date('c',date('U')+86400));
    $TRA->addChild('service',$SERVICE);
    $TRA->asXML(LOG_TKT);
}

/* --------------------------------------------------------
 Firma el LoginTicket 
 Ingresa el login Ticket el certificado y la privada. 
 Genera un archivo intermedio LOG_TKTF.tmp y le quita los encabezados MIME para obtener el valor que hay que hay que carhar en in0 .
----------------------------------------------------------*/
function FirmaLoginTkt()
{
    
    if (!file_exists(LOG_TKT)) {
        exit("Failed to open". LOG_TKT ."\n");
    }
// $STATUS=openssl_pkcs7_sign("../archivos/TRA.xml", "../archivos/TRA.tmp", CERT,array(PRIVATEKEY),array(),!PKCS7_DETACHED);
 
    $command = "openssl cms -sign -in  ".LOG_TKT ." -out ".LOG_TKT_FIRM. " -signer ".CERT ." -inkey ". PRIVATEKEY ." -nodetach -outform PEM";
    // echo $command;
    exec($command, $output, $STATUS);
    // $STATUS=openssl_pkcs7_sign("../archivos/TRA.xml","../archivos/TRA.tmp",CERT,array(PRIVATEKEY),array(),PKCS7_DETACHED);
    
    if ($STATUS) {
        exit("ERROR generating PKCS#7 signature\n");
    }
    $inf=fopen(LOG_TKT_FIRM, "r");
    $i=0;
    $CMS="";
    $nroFilas=count(file(LOG_TKT_FIRM));
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
        $client=new SoapClient(WSDL, $options);
        $results=$client->loginCms(array('in0'=>$CMS)); 
        file_put_contents("requestloginCms.xml",$client-> __getLastRequest()); 
        file_put_contents("responseloginCms.xml",$client-> __getLastResponse()); 
        if (is_soap_fault($results)){
            exit("SOAP Fault: ".$results->faultcode."\n".$results->faultstring."\n");
        } return
        $results->loginCmsReturn;
    }
    catch (SoapFault $e){
        echo "Error: " . $e->getMessage();
    }
}
function ShowUsage($MyPath)
{
printf("Uso : %s Arg#1 Arg#2\n", $MyPath);
printf("donde: Arg#1 debe ser el service name del WS de negocio.\n");
printf(" Ej.: %s wsfe\n", $MyPath);
}

ini_set("soap.wsdl_cache_enabled", "0");
if (!file_exists(CERT)) {exit("Failed to open ".CERT."\n");}
if (!file_exists(PRIVATEKEY)) {exit("Failed to open ".PRIVATEKEY."\n");}
// if (!file_exists(WSDL)) {exit("Failed to open ".WSDL."\n");}
if ( $argc < 2 ) {ShowUsage($argv[0]); exit();}


$SERVICE=$argv[1];
CreaLoginTkt($SERVICE);
$CMS=FirmaLoginTkt();
//print_r($CMS);
$z=CallWSAA($CMS);

if (!file_put_contents("../archivos/TktAut.xml", $z)) {
    exit();
}