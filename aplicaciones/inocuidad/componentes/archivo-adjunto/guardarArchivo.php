<?php
/**
 * Created by PhpStorm.
 * User: advance
 * Date: 1/24/18
 * Time: 11:01 PM
 */

require_once './controladores/ControladorArchivoAdjunto.php';
$controller = new ControladorArchivoAdjunto();
$id_modelo = isset($_POST['id_modelo']) ? $_POST['id_modelo'] : null;
$nombre = isset($_POST['adjunto_nombre']) ? $_POST['adjunto_nombre'] : null;
$descripcion = isset($_POST['adjunto_descripcion']) ? $_POST['adjunto_descripcion'] : null;
$fecha_carga = isset($_POST['adjunto_fecha_carga']) ? $_POST['adjunto_fecha_carga'] : null;
$etiqueta = isset($_POST['adjunto_etiqueta']) ? $_POST['adjunto_etiqueta'] : null;
$ruta = isset($_POST['ruta']) ? $_POST['ruta'] : null;

$itemArchivo = new ItemArchivo($id_modelo,$nombre,$descripcion,$fecha_carga,$etiqueta,$ruta);

$tabla = isset($_POST['adjunto_tabla']) ? $_POST['adjunto_tabla'] : null;
$registro = isset($_POST['adjunto_registro']) ? $_POST['adjunto_registro'] : null;
$modelo = new ItemArchivoModelo(null,$tabla,$registro);

$controller->guardarItemArchivo($itemArchivo,$modelo);

