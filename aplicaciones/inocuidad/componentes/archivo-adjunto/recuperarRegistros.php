<?php
/**
 * User: ccarrera
 * Date: 2/21/18
 * Time: 11:06 PM
 */
require_once './controladores/ControladorArchivoAdjunto.php';
$controladorArchivo = new ControladorArchivoAdjunto();
$data=$controladorArchivo->crearTablaArchivos($_POST['tabla'],$_POST['registro']);
echo $data;
