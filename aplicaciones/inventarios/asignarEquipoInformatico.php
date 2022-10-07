<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorReportes.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try {
	 
	

	$conexion = new Conexion();
	$jru = new ControladorReportes();
	 
	$conexion->ejecutarConsulta("begin;");
	 
	$identificador = '1308669496';
	$ruta = $constg::RUTA_SERVIDOR_OPT.'/'.$constg::RUTA_APLICACION.'/aplicaciones/inventarios/reportes/';
	//$ruta = str_replace("/", "\\", $ruta);
	 
	//	$ruta = 'C:\\xampp\\htdocs\\agrodb\\aplicaciones\\inventarios\\reportes\\subreporteTres.jasper';
	//echo $ruta;
	try {

		$rutaCertificado = 'aplicaciones/inventarios/reportes/'.$identificador.'.pdf';

		$ReporteJasper='aplicaciones/inventarios/reportes/reporteUno.jrxml';
		
		$parameters['parametrosReporte'] = array(
			'identificador' => (int)  $identificador,
			'rutaSubparmetro' =>  $ruta
		);
		
		$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$rutaCertificado,'ninguno');

		$conexion->ejecutarConsulta("commit;");

		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'El item se ha asignado satisfactoriamente';

		/*   $conexion->desconectar();
		 echo json_encode($mensaje);
		} catch (Exception $ex) {
		pg_close($conexion);
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia".$ex;
		echo json_encode($mensaje);
		}
		} catch (Exception $ex) {
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = 'Error de conexión a la base de datos';
		echo json_encode($mensaje);
		}*/
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

//     session_start();
//     require_once '../../clases/Conexion.php';
//     require_once '../../clases/ControladorInventarios.php';


//     $mensaje = array();
//     $mensaje['estado'] = 'error';
//     $mensaje['mensaje'] = 'Ha ocurrido un error!';


//     try {

//         $identificador = htmlspecialchars($_POST['identificador'], ENT_NOQUOTES, 'UTF-8');
//         $serial = htmlspecialchars($_POST['serial'], ENT_NOQUOTES, 'UTF-8');
//         $tipo = htmlspecialchars($_POST['tipo'], ENT_NOQUOTES, 'UTF-8');

//         try {
//             $conexion = new Conexion();
//             $ci = new ControladorInventario();

//             /*switch ($tipo) {
//                 case "Computador":
//                     $ci->gasignarComputador($conexion, $inspeccion, $equipo, $tipoCarrera, $estado);
//                     break;
//                 case "Monitor":
//                     $ci->asignarMonitor($conexion, $inspeccion, $equipo, $tipoCarrera, $estado);
//                     break;
//                 case "Teclado":
//                     $ci->asignarTeclado($conexion, $inspeccion, $equipo, $tipoCarrera, $estado);
//                     break;
//                 case "Ratón":
//                     $ci->asignarRatón($conexion, $inspeccion, $equipo, $tipoCarrera, $estado);
//                     break;
//             }*/

//             $ci->asignarItemPorSerial($conexion, $identificador, strtoupper($serial));

//             $mensaje['estado'] = 'exito';
//             $mensaje['mensaje'] = 'El item se ha asignado satisfactoriamente';

//             $conexion->desconectar();
//             echo json_encode($mensaje);
//         } catch (Exception $ex) {
//             pg_close($conexion);
//             $mensaje['estado'] = 'error';
//             $mensaje['mensaje'] = "Error al ejecutar sentencia";
//             echo json_encode($mensaje);
//         }
//     } catch (Exception $ex) {
//         $mensaje['estado'] = 'error';
//         $mensaje['mensaje'] = 'Error de conexión a la base de datos';
//         echo json_encode($mensaje);
//     }
?>