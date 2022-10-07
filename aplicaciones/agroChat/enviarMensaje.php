<?php
session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorChat.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

$data  = json_decode($_POST['data'], true);

$userId = htmlentities($_POST['usuario'],  ENT_NOQUOTES);
$msg = htmlspecialchars($_POST['mensaje'],  ENT_NOQUOTES, "UTF-8");
$contacto = htmlentities($_POST['contacto'],  ENT_NOQUOTES);
$tipo = htmlspecialchars($_POST['tipo'],  ENT_NOQUOTES);
$contactos= explode("_",$contacto);
$conexion = new Conexion();
$cc = new ControladorChat();

$texto = $_POST['mensaje'];

////////////////////////////////////////////// link ////////////////
$cadena_origen= $texto;

//enlaces normales
$cadena_resultante= preg_replace("/((http|https|www)[^\s]+)/", '<a target="_blank" href="$1">$0</a>', $cadena_origen);

//enlaces con solamente www, se agrega http://
$cadena_resultante= preg_replace("/href=\"www/", 'href="http://www', $cadena_resultante);

//enlaces de twitter
$cadena_resultante = preg_replace("/(@[^\s]+)/", '<a target=\"_blank\"  href="http://twitter.com/intent/user?screen_name=$1">$0</a>', $cadena_resultante);
$cadena_resultante = preg_replace("/(#[^\s]+)/", '<a target=\"_blank\"  href="http://twitter.com/search?q=$1">$0</a>', $cadena_resultante);
//////////////////////////////fin link //////////////////////

$texto_resultante= preg_replace("/((http|https|www)[^\s]+)/", '<a target="_blank" href="$1">$0</a>', $texto);

//enlaces con solamente www, se agrega http://
$texto_resultante= preg_replace("/href=\"www/", 'href="http://www', $texto_resultante);

//enlaces de twitter
$texto_resultante = preg_replace("/(@[^\s]+)/", '<a target=\"_blank\"  href="http://twitter.com/intent/user?screen_name=$1">$0</a>', $texto_resultante);
$texto_resultante = preg_replace("/(#[^\s]+)/", '<a target=\"_blank\"  href="http://twitter.com/search?q=$1">$0</a>', $texto_resultante);

try{
    
    try {
                     
        $conexion->ejecutarConsulta("begin;");        
        $filas=$cc->obtenerEmojis($conexion);
        $items = array();
        while($emojis=pg_fetch_assoc($filas)){
            $contenido = '<img class="emoji" name="::~'.$emojis['nombre'].'" src="'.$emojis['ruta'].'">';
            $nombres = "::~".$emojis['nombre'];           
            array_push($items[$contenido]=$nombres);            
        }
        
        $mensajeEnviado = strtr($texto_resultante,$items);
        $mensajeEnviado =  htmlspecialchars($mensajeEnviado,  ENT_NOQUOTES, "UTF-8");
        
        $result= $cc->enviarMensaje($conexion, $userId, $mensajeEnviado, $contactos[1],$tipo);
       
        
        if($num=pg_num_rows($result)>0){
            $val= pg_fetch_row($result);
            $fecha = $val[0];
        }else{
            $fecha = "error";
        }
            
        $mensaje['estado'] = 'exito';
        $mensaje['mensaje'] = $texto_resultante;
        $mensaje['fecha'] = $fecha;
        
        $conexion->ejecutarConsulta("commit;");        
        
    } catch (Exception $ex){
        $conexion->ejecutarConsulta("rollback;");
        $mensaje['estado'] = $conexion->mensajeError;
        $mensaje['mensaje'] = $ex->getMessage();
        
    } finally {
        $conexion->desconectar();
    }
} catch (Exception $ex) {
    $mensaje['estado'] = $conexion->mensajeError;
    $mensaje['mensaje'] = $ex->getMessage();
    echo json_encode($mensaje);
} finally {
    echo json_encode($mensaje);
}
?>