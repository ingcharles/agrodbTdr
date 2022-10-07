<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacion.php';
require_once '../../clases/ControladorCatastroProducto.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try {
    $conexion = new Conexion();
    $va = new ControladorVacunacion();
    $ccp = new ControladorCatastroProducto();

    // Datos generales vacunacion
    $idSitio = htmlspecialchars($_POST['sitio'], ENT_NOQUOTES, 'UTF-8');
    $idEspecie = htmlspecialchars($_POST['especie'], ENT_NOQUOTES, 'UTF-8');
    $identificadorAdministrador = htmlspecialchars($_POST['operadorVacunacion'], ENT_NOQUOTES, 'UTF-8');
    $identificadorDistribuidor = htmlspecialchars($_POST['distribuidor'], ENT_NOQUOTES, 'UTF-8');
    $identificadorVacunador = htmlspecialchars($_POST['vacunador'], ENT_NOQUOTES, 'UTF-8');
    $numeroCertificado = htmlspecialchars($_POST['certificadoVacunacion'], ENT_NOQUOTES, 'UTF-8');
    $idLoteVacuna = htmlspecialchars($_POST['loteVacuna'], ENT_NOQUOTES, 'UTF-8');
    $idTipoVacuna = htmlspecialchars($_POST['tipoVacuna'], ENT_NOQUOTES, 'UTF-8');
    $costoVacuna = htmlspecialchars($_POST['costoVacuna'], ENT_NOQUOTES, 'UTF-8');
    $fechaVacunacion = htmlspecialchars($_POST['fechaVacunacion'], ENT_NOQUOTES, 'UTF-8');
    $usuarioResponsable = htmlspecialchars($_SESSION['usuario'], ENT_NOQUOTES, 'UTF-8');
    $fechaVacunacionEmision = str_replace("/", "-", $fechaVacunacion);
    $fechaVencimientoVacuna = date('d-m-Y', strtotime('6 month', strtotime($fechaVacunacionEmision)));

    $tipoVacunacion = $_POST['tipoVacunacion'];
    $operacion = $_POST['operacion'];    
    $observacion = $_POST['observacion'];
    $idUnidadComercial = $_POST['unidadMedida'];


    $datosVacunacion = $_POST['datosVacunacion'];
    $arrayDatosVacunacion = json_decode($datosVacunacion, true);

    $idAreas = explode(",", $_POST['idAreas']);
    $idProductos = explode(",", $_POST['idProductos']);
    $idOperaciones = explode(",", $_POST['idOperaciones']);
    $identificadoresProductos = explode(",", $_POST['identificadoresProductos']);
    $cantidadProductos = explode(",", $_POST['cantidadProductos']);

    try {

        $conexion->ejecutarConsulta("begin;");

        if (pg_num_rows($va->consultarCertificadoVacunacion($conexion, $numeroCertificado)) == 0) {

            if ($tipoVacunacion == "lote") {

                  //Guardar cabecera vacunacion
                  $idVacunacion = $va->guardarVacunacion($conexion, $idSitio, $idEspecie, $identificadorAdministrador, $identificadorDistribuidor, $identificadorVacunador, $idLoteVacuna, $idTipoVacuna, $costoVacuna, $numeroCertificado, $fechaVacunacion, $fechaVencimientoVacuna, $usuarioResponsable, 'vigente', $observacion);
                  $idVacunacion = pg_fetch_result($idVacunacion, 0, 'id_vacunacion');
                 
                  for ($i = 0; $i < count($idAreas); $i ++) {
          
                      //Guardar detalle vacunacion
                      $idDetalleVacunacion = $va->guardarDetalleVacunacion($conexion, $idVacunacion, $idAreas[$i], $idProductos[$i], $idOperaciones[$i], $cantidadProductos[$i], $idUnidadComercial, $identificadoresProductos[$i]);
                      $idDetalleVacunacion = pg_fetch_result($idDetalleVacunacion, 0, 'id_detalle_vacunacion');                 
          
                      $qIdentificadoresPorlote = $ccp->obtenerCatastroPorNumeroLotePorIdAreaPorIdProducto($conexion, $idAreas[$i], $identificadoresProductos[$i], $idProductos[$i], $cantidadProductos[$i]);
          
                      while ($identificadoresPorlote = pg_fetch_assoc($qIdentificadoresPorlote)) {
          
                          //Guardar detalle identificadores vacunacion
                          $va->guardarDetalleIdentificadores($conexion, $idDetalleVacunacion, $identificadoresPorlote['identificador_producto']);
                          $ccp->actualizarEstadoDetalleCatastroXIdentificadorProducto($conexion, $identificadoresPorlote['identificador_producto'], 'activo');
                      }
          
                    }
          
                }else if ($tipoVacunacion == "identificador") {

                $arrayIdentificadores = array();

                for ($i = 0; $i < count($idAreas); $i ++) {

                    $arrayIdentificadores['idArea'][$idAreas[$i]]['idProducto'][$idProductos[$i]]['identificadorProducto'][] = $identificadoresProductos[$i];
                    $arrayIdentificadores['idArea'][$idAreas[$i]] += array(
                        'idOperacion' => $idOperaciones[$i]
                    );
                    
                }

                $idVacunacion = $va->guardarVacunacion($conexion, $idSitio, $idEspecie, $identificadorAdministrador, $identificadorDistribuidor, $identificadorVacunador, $idLoteVacuna, $idTipoVacuna, $costoVacuna, $numeroCertificado, $fechaVacunacion, $fechaVencimientoVacuna, $usuarioResponsable, 'vigente', $observacion);
             
                foreach ($arrayIdentificadores['idArea'] as $llaveArea => $valorArea) {

                    $cantidadProducto = 0;

                    foreach ($valorArea['idProducto'] as $llaveProducto => $valorProducto) {

                        $cantidadProducto = count($valorProducto['identificadorProducto']);

                        $idDetalleVacunacion = $va->guardarDetalleVacunacion($conexion, pg_fetch_result($idVacunacion, 0, 'id_vacunacion'), $llaveArea, $llaveProducto, $valorArea['idOperacion'], $cantidadProducto, $idUnidadComercial, $numeroLote);

                        $idDetalleVacunacion = pg_fetch_result($idDetalleVacunacion, 0, 'id_detalle_vacunacion');

                        foreach ($valorProducto['identificadorProducto'] as $llaveIdentificadorProducto => $valorIdentificadorProducto) {

                            $va->guardarDetalleIdentificadores($conexion, $idDetalleVacunacion, $valorIdentificadorProducto);
                            $ccp->actualizarEstadoDetalleCatastroXIdentificadorProducto($conexion, $valorIdentificadorProducto, 'activo');
                            
                        }
                    }
                }
            }

            $va->actualizarEstadoCertificadoVacunacion($conexion, $idEspecie, $numeroCertificado, 'utilizado','ya está utilizado',$usuarioResponsable);

            $mensaje['estado'] = 'exito';
            $mensaje['mensaje'] = "Los datos han sido guardado satisfactoriamente";
        } else {
            $mensaje['estado'] = 'error';
            $mensaje['mensaje'] = "El certificado de vacunación ya ha sido registrado";
        }

        $conexion->ejecutarConsulta("commit;");
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
