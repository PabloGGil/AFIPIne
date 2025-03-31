<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
$path_cli = __DIR__ . '/../../';
// echo $path_cli;
// echo __DIR__;
include_once $path_cli . 'config/class.Config.php';
include_once $path_cli . 'clases/class.Auth.php';
include_once $path_cli . 'clases/class.wsaa.php';
include_once $path_cli . 'clases/class.FECAESolicitar.php';
include_once $path_cli . 'clases/Zclass.FECAESolicitar.php';
include_once $path_cli . 'clases/class.FECompConsultar.php';
include_once $path_cli . 'clases/class.FEParamGetTiposCbte.php';
include_once $path_cli . 'clases/class.FECAEARegInformativo.php';
include_once $path_cli . 'clases/class.FEParamGetTiposDoc.php';
include_once $path_cli . 'clases/class.TiposConcepto.php';
include_once $path_cli . 'clases/FECompUltimoAutorizado.php';
include_once $path_cli . 'clases/FEParamGetCondicionIvaReceptor.php';
session_start();


$json = file_get_contents('php://input');

// Decodifica el JSON a un array asociativo
$data = json_decode($json, true);
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} 
elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} 
// else {
//     $ip = $_SERVER['REQUEST_URI'];
// }
$ret['rc'] = '1';
$ret['msg'] = 'Falto parametro de consulta';
$ret['info'] = array();
$ret['msgerror']='';
$retorno = array();
// $_SESSION['buffer']=array();

//$ret = 'error';
if (isset($_GET['q'])) {
    switch ($_GET['q']) {
        // tipo de comprobante
        case 'tipoComprobante':
            $ajInst=new TiposCbte();
            $res=$ajInst->getData();
            break;
        // tipo de Documentos
        case 'tipoDocumento':
            $ajInst=new TiposDoc();
            $res=$ajInst->getData();
            break;
        // tipo de Concepto
        case 'tipoConcepto':
            $ajInst=new TiposConcepto();
            $res=$ajInst->getData();
            break;
        // tipo de Documento
        case 'tipoDocumento':
            $ajInst=new TiposDoc();
            $res=$ajInst->getData();
            break;
        // Ultimo Autorizado
        case 'ultAutorizado':
            $ajInst=new UltimoAutorizado(2,11);
            $res=$ajInst->getData();
            break;
        // case 'solicitar':
        //     $ajInst=new FECAESolicitar($data);
        //     $res=$ajInst->getData();
        //     break;
    }
    // $cant=count($ret['info']);
    // $retorno['rc'] = $ret['rc'];
    // $retorno['msg'] = $msg ;//.' - ' . $objBSN->DiccionarioToTXT($ret);;
    $retorno['coleccion'] = $res;
    $retorno['msgerror']=$ret['msgerror'];
} else {
    ////////////////////////////////////////////////////////////////////////
    ////////////////////////Inicio de recepcion json////////////////////////
    ////////////////////////////////////////////////////////////////////////
    
    $ret = array();
   

    // if (! isset($_SESSION['user']))
    //     $user='NN';
    // else
    //     $user=$_SESSION['user'];
    // decodifico la entrada en UTF8
    $params = json_decode(file_get_contents('php://input'), true, 512, JSON_UNESCAPED_UNICODE);
    //print_r($params['info']);
    switch ($params['q']) {
       
        case 'solicitar':
            // $objBSN = new BackupBSN();
            // $ret = $objBSN->Agregar_BSN($params['info']);
            // if ($ret['rc']=='0') {
            //     $bitacora->addRegistro('Backup', $user, 'Agregar', '', json_encode($params['info']));
            //     // $rc = 0;
            //     // $msg = 'OK';
            // }
            // break;
            $ajInst=new ZFECAESolicitar($params['info']);
            $res=$ajInst->sendData();
            break;
    }
    $retorno=$ret;
}
header('Content-Type: application/json');
http_response_code(200);
echo json_encode($retorno, JSON_UNESCAPED_UNICODE);

