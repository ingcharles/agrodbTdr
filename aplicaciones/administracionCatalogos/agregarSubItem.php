<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAdministrarCatalogos.php';

$idCatalogoPadre = htmlspecialchars($_POST['idCatalogoPadre'], ENT_NOQUOTES, 'UTF-8');
$idItemPadre = htmlspecialchars($_POST['idItemPadre'], ENT_NOQUOTES, 'UTF-8');
$idCatalogoHijo = htmlspecialchars($_POST['idCatalogoHijo'], ENT_NOQUOTES, 'UTF-8');
$idItemHijo = htmlspecialchars($_POST['idItemHijo'], ENT_NOQUOTES, 'UTF-8');
$nombreItemHijo = htmlspecialchars($_POST['nombreItemHijo'], ENT_NOQUOTES, 'UTF-8');
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
        $buscarItemCatalogoHijo = $cac->buscarItemPorIdCatalogoPadreIdCatalogoHijoIdItemPadre($conexion, $idCatalogoPadre, $idCatalogoHijo, $idItemPadre, '= '. $idItemHijo, $nivel, $idSubitemCatalogoPadre, 'activo', "= 'activo'");

        if (pg_num_rows($buscarItemCatalogoHijo) == 0) {
            
            $conexion->ejecutarConsulta("begin;");
            
            $buscarItemCatalogoHijo = $cac->buscarItemPorIdCatalogoPadreIdCatalogoHijoIdItemPadre($conexion, $idCatalogoPadre, $idCatalogoHijo, $idItemPadre, 'is null', $nivel, $idSubitemCatalogoPadre, 'activo', 'is null');
            
            if(pg_num_rows($buscarItemCatalogoHijo) != 0){
                $idSubitemCatalogoPadre = pg_fetch_result($cac->actualizarItemHijo($conexion, $idCatalogoPadre, $idCatalogoHijo, $idItemPadre, $idItemHijo, $nivel, $idSubitemCatalogoPadre), 0, 'id_subitem_catalogo');
            }else{
                $idSubitemCatalogoPadre = pg_fetch_result($cac->guardarItemHijo($conexion, $idCatalogoPadre, $idCatalogoHijo, $idItemPadre, $idItemHijo, $identificador, $nivel, $idSubitemCatalogoPadre), 0, 'id_subitem_catalogo');
            }

            $conexion->ejecutarConsulta("commit;");

            $mensaje['estado'] = 'exito';
            $mensaje['mensaje'] = $cac->imprimirLineaItemoHijo($idCatalogoPadre, $idCatalogoHijo, $idItemPadre, $idItemHijo, $nombreItemHijo, $nivel, $idExclusionCatalogo, $idSubitemCatalogoPadre, $etapaProceso);
        }else{
            $mensaje['mensaje'] = 'El item ya ha sido asignado.';
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

