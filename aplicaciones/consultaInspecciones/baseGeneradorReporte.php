<?php
/**
 * Created by PhpStorm.
 * User: Eduardo
 * Date: 16/10/2017
 * Time: 15:03
 */

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorConsultaInspecciones.php';

header("Content-type: application/octet-stream");
//indicamos al navegador que se está devolviendo un archivo
header("Content-Disposition: attachment; filename=$archivoSalida");
//con esto evitamos que el navegador lo grabe en su caché
header("Pragma: no-cache");
header("Expires: 0");

$conexion = new Conexion();
$cci = new ControladorConsultaInspecciones();

$fechaInicio = $_POST['fechaInicio'];
$fechaFin = $_POST['fechaFin'];
$identificadorUsuario = $_POST['identificadorUsuario'];
$nombreUsuario = $_POST['nombreUsuario'];
$tipoFormulario = isset($_POST['tipoFormulario'])?$_POST['tipoFormulario']:null;
$incluirDatosLaboratorio = true;

if( !isset($_POST['incluirDatosLabotorio'])){
    $incluirDatosLaboratorio = true;//TODO: aquí debe ser false;
}

function incluirDatosLaboratorio(&$campos, &$tablas, $camposLaboratorio){
    $campos = array_merge($campos, $camposLaboratorio);
    if (is_array($tablas)) {
        $tmp = explode('_', $tablas[0]);
        $tablaLaboratorio = $tmp[0] . '_detalle_ordenes';
        array_push($tablas, $tablaLaboratorio);
    } else {
        $tmp = explode('_', $tablas);
        $tablaLaboratorio = $tmp[0] . '_detalle_ordenes';
        $tablas = array($tablas, $tablaLaboratorio);
    }
}

?>