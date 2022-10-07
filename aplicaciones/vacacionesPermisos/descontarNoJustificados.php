<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacaciones.php';
require_once '../../clases/ControladorAreas.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

setlocale(LC_ALL,"es_ES");

try{
	$conexion = new Conexion();
	$cv = new ControladorVacaciones();

	try {
        
		$cv->cambiarEstadoXMinutos($conexion,'1');
	    $resultado=$cv->consultarRegistrosEstadoMinutos($conexion,'1');
			
		while($fila = pg_fetch_assoc($resultado)){
			$cv->actualizarSaldosFuncionario($conexion, $fila['identificador'], $fila['minutos_utilizados']);
			$cv->crearObservacionVacacion($conexion,$fila['id_permiso_empleado'],'Descuento por no presentar justificativos',$fila['identificador']);
		}
		$cv->cambiarEstadoXMinutos($conexion,'2');
		
	


		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Los datos han sido registrados satisfactoriamente';
		$conexion->desconectar();
		echo json_encode($mensaje);
			
	} catch (Exception $ex){
		pg_close($conexion);
		$error=$ex->getMessage();
		$mensaje['estado'] = 'error';
		$suma_cod_error;
		$error_code=0;
		$suma_cod_error= $error_code + (stristr($error, 'duplicate key')!=FALSE)?1:0;
		$error_code= $error_code + $suma_cod_error;
		$suma_cod_error= $error_code + (stristr($error, 'numero_contrato')!=FALSE)?2:0;
		$error_code= $error_code + $suma_cod_error;
			
		switch($error_code){
			case 0:		$mensaje['mensaje'] = 'No se puede ejecutar la sentencia';
			break;
			case 3:		$mensaje['mensaje'] = 'Error: Ya existe un contrato con el mismo número';
			break;
		}
		echo json_encode($mensaje);
	}

} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>