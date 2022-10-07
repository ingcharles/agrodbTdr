<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAdministrarCatalogos.php';

$idCatalogoPadre = htmlspecialchars($_POST['idCatalogoPadre'], ENT_NOQUOTES, 'UTF-8');
$idSubitem = htmlspecialchars($_POST['idItemPadre'], ENT_NOQUOTES, 'UTF-8');
$idCatalogoHijo = htmlspecialchars($_POST['idCatalogoHijo'], ENT_NOQUOTES, 'UTF-8');
$nombreCatalogoHijo = htmlspecialchars($_POST['nombreCatalogoHijo'], ENT_NOQUOTES, 'UTF-8');
$nivel = htmlspecialchars($_POST['nivel'], ENT_NOQUOTES, 'UTF-8');
$idExclusionCatalogo = $_POST['idExclusionCatalogo'];
$idSubitemCatalogoPadre = $_POST['idSubitemCatalogoPadre'];
$etapaProceso = unserialize($_POST['etapaProceso']);
$identificador = $_SESSION['usuario'];

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try {

    $conexion = new Conexion();
    $cac = new ControladorAdministrarCatalogos();

    try {
        $buscarCatalogoHijo = $cac->buscarCatalogoPorIdCatalogoPadreIdCatalogoHijoIdItemPadre($conexion, $idCatalogoPadre, $idCatalogoHijo, $idSubitem, $nivel, $idSubitemCatalogoPadre);

        if (pg_num_rows($buscarCatalogoHijo) == 0) {

            $conexion->ejecutarConsulta("begin;");

            $cac->guardarCatalogoHijoAsignado($conexion, $idCatalogoPadre, $idCatalogoHijo, $idSubitem, $identificador, $nivel, $idSubitemCatalogoPadre);

            $conexion->ejecutarConsulta("commit;");

            $mensaje['estado'] = 'exito';
            $mensaje['mensaje'] = $cac->imprimirLineaCatalogoHijo($idCatalogoPadre, $idCatalogoHijo, $idSubitem, $nombreCatalogoHijo, $nivel, $idExclusionCatalogo, $etapaProceso, $idSubitemCatalogoPadre);
        }else{
            $mensaje['mensaje'] = 'El catalogo ya ha sido asignado.';
        }
    } catch (Exception $ex) {
        $conexion->ejecutarConsulta("rollback;");
        $mensaje['mensaje'] = $ex->getMessage();
        $mensaje['error'] = $conexion->mensajeError;
    } finally {
        $conexion->desconectar();
    }
} catch (Exception $ex) {
    $mensaje['mensaje'] = $ex->getMessage();
    $mensaje['error'] = $conexion->mensajeError;
} finally {
    echo json_encode($mensaje);
}

?>

