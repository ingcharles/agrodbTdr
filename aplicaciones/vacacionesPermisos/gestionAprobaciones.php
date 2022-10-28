<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacaciones.php';
require_once '../../clases/ControladorAreas.php';
$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';
try{
	$conexion = new Conexion();
	$cv = new ControladorVacaciones();
	$opcion=$_POST['opcion'];
	$id_registro=$_POST['id_solicitud_permiso'];
	$fila = pg_fetch_assoc($cv->obtenerPermisoSolicitado($conexion,$id_registro));
	$minutosConsumidos=$fila['minutos_utilizados'];
	$identificadorUsuario = $_SESSION['usuario'];
	try {
	    $conexion->ejecutarConsulta("begin;");
		if(strcmp($opcion,"Actualizar")==0){
			$cv->actualizarEstadoPermiso($conexion,$id_registro,$_POST['estado_solicitud']);
			//-----------actualizar estado de encargos-------------------------------------------------
			$cv->actualizarEstadoResponsablePuesto($conexion,$id_registro,$_POST['estado_solicitud']);
			//-----------------------------------------------------------------------------------------
			if(strcmp($fila['codigo'],"PE-PIV")==0 || strcmp($fila['codigo'],"VA-VA")==0 || strcmp($fila['codigo'],"PE-PIVF")==0){
				if (strcmp($_POST['estado_solicitud'],"Aprobado")==0){
					$cv->actualizarSaldosFuncionario($conexion,$fila['identificador'],$minutosConsumidos, $id_registro);
					$minutos=pg_fetch_result($cv->consultarSaldoFuncionario($conexion,$fila['identificador']),0,'minutos_disponibles');
					if($minutos == ''){
					    $minutos=0;
					}
					$cv->actualizarMinutosActuales($conexion,$id_registro,$minutos);
				}
			}
		}
		//Registro de observaciones del proceso
		$cv->agregarObservacion($conexion, 'El usuario '.$identificadorUsuario.' ha '.$_POST['estado_solicitud'].' la solicitud de '.$fila['descripcion_subtipo'].' con fecha de salida '
				.$fila['fecha_inicio'].', fecha de retorno '.$fila['fecha_fin'].' y con '.$fila['minutos_utilizados'].' minutos solicitados',
				$id_registro, $identificadorUsuario);
		$cv->agregarObservacion($conexion, 'El usuario '.$identificadorUsuario.' agrega la siguiente observación a la solicitud: '.$_POST['observaciones'],
				$id_registro, $identificadorUsuario);
		$cv->agregarObservacionPermiso($conexion,$_POST['observaciones'], $id_registro);
		$conexion->ejecutarConsulta("commit;");
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Los datos han sido registrados satisfactoriamente';
		$conexion->desconectar();
		echo json_encode($mensaje);
	} catch (Exception $ex){
	    $err = preg_replace( "/\r|\n/", " ", $conexion->mensajeError);
	    $conexion->ejecutarLogsTryCatch($ex.'--'.$err);
	    $conexion->ejecutarConsulta("rollback;");
	    $conexion->desconectar();
	    $mensaje['estado'] = 'error';
	    $mensaje['mensaje'] = "Error al ejecutar sentencia";
	    echo json_encode($mensaje);
	}
} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>