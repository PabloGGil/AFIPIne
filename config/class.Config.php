<?php 
// define ("ROOT_DIR",__DIR__ . "/../");
class Config{
    
    private static $configCompleto=[];
    static function load(){
  // Cargar .env
        $envData = parse_ini_file(__DIR__ .'/../.env', true);
        if (!$envData || !isset($envData['APP_ENV'])) {
            throw new Exception("Error cargando .env o APP_ENV no definido");
        }
        // echo __DIR__;
        $entorno = $envData['APP_ENV'];
        
        // Cargar defaults
        $defaultFile = __DIR__ . '/defaults.json';
        if (!file_exists($defaultFile)) {
            throw new Exception("Archivo defaults.json no encontrado");
        }
        
        $defaultConfig = json_decode(file_get_contents($defaultFile), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Error decodificando defaults.json");
        }
        
        // Cargar entorno específico
        $envFile = __DIR__ . "/entorno/" . $entorno . ".json";
        if (!file_exists($envFile)) {
            throw new Exception("Archivo de entorno $envFile no encontrado");
        }
        
        $envConfig = json_decode(file_get_contents($envFile), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Error decodificando $envFile");
        }
        
        // Combinar y asignar a la propiedad estática
        self::$configCompleto = array_merge($defaultConfig, $envConfig);   
    }

    public static function  getConfig(){
        return self::$configCompleto;
    }

    public static function get($key) {
        return self::$configCompleto[$key];
    }   
}

// const WSDL= "clases/wsaa.wsdl"; # WSDL correspondiente al WSAA
// const CERT= "certs/PabloTest.pem"; # Certificado usado para firmar
// const PRIVATEKEY="certs/PabloTest.key"; # Clave privada del certificado
// const PASSPHRASE= "metro100"; # Password para firmar
// const PROXY_HOST=""; # IP del proxy para salir a Internet
// const PROXY_PORT=""; # Puerto del proxy
// const URL= "https://wsaahomo.afip.gov.ar/ws/services/LoginCms";
// const LOG_TKT= "archivos/LoginTicket.xml"; // ticket de acceso
// const LOG_TKT_FIRM="archivos/TAF.tmp"; // ticket de acceso firmado
// const CUIT=20149576798;
// const TKT_AUTH="archivos/tktaut.xml";
// const AMBIENTE=["Homologacion","Produccion"];


// Config::load();
// echo( Config::get("CERT"));