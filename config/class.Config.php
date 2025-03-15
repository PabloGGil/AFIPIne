<?php 
// define ("ROOT_DIR",__DIR__ . "/../");
class Config{

    // en caso que exista un archivo de configuracion se debe cargar aca
    function __construct(){
        echo "<br>constructor de config ";
        
    }

// define ("ROOT_DIR",__DIR__ . "/../");
// $rootDir=__DIR__;

const WSDL= "clases/wsaa.wsdl"; # WSDL correspondiente al WSAA
const CERT= "certs/PabloTest.pem"; # Certificado usado para firmar
const PRIVATEKEY="certs/PabloTest.key"; # Clave privada del certificado
const PASSPHRASE= "metro100"; # Password para firmar
const PROXY_HOST=""; # IP del proxy para salir a Internet
const PROXY_PORT=""; # Puerto del proxy
const URL= "https://wsaahomo.afip.gov.ar/ws/services/LoginCms";
const LOG_TKT= "archivos/LoginTicket.xml"; // ticket de acceso
const LOG_TKT_FIRM="archivos/TAF.tmp"; // ticket de acceso firmado
const CUIT=20149576798;
const TKT_AUTH="archivos/tktaut.xml";

}