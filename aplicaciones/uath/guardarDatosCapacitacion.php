<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';
//require_once('../../FirePHPCore/FirePHP.class.php'); borrado
//ob_start(); borrado


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$opcion=$_POST['opcion'];
	$institucion = $_POST['institucion'];
	$archivo= $_POST['archivo'];
	$titulo= $_POST['titulo'];
	$pais=$_POST['pais'];
	$estado= $_POST['estado'];
	$usuario=$_POST['usuario'];
	$num_horas=$_POST['horas'];
	$fecha_inicio=$_POST['fecha_inicio'];
	$fecha_fin=$_POST['fecha_fin'];
	$academico_seleccionado=$_POST['academico_seleccionado'];
	$observacion=$_POST['observaciones'];
	$auspiciante=$_POST['auspiciante'];
	$tipoCertificado=$_POST['tipo_certificado'];
	
	if($num_horas ==''){
		$num_horas = 0;
	}
	
	try {
				$conexion = new Conexion();
				$cc = new ControladorCatastro();
				if($opcion=='Nuevo')
				{   
				    $cc->crearDatosCapacitacion($conexion, $usuario, $titulo, $institucion, $pais, $archivo,'Ingresado', $num_horas,$observacion,$fecha_inicio,$fecha_fin,$auspiciante,$tipoCertificado);
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = 'Los datos han sido ingresados satisfactoriamente';
				}
				if($opcion=='Actualizar')
				{
				    $cc->actualizarDatosCapacitacion($conexion,$academico_seleccionado, $usuario, $titulo, $institucion, $pais, $archivo,'Modificado', $num_horas,$fecha_inicio,$fecha_fin,$auspiciante,$tipoCertificado);
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';
				}
				
				
				$conexion->desconectar();
				echo json_encode($mensaje);
				} catch (Exception $ex){
					pg_close($conexion);
					$error=$ex->getMessage();
					//$firephp->warn('Captura Error:'.$error);
					$mensaje['estado'] = 'error';
					$suma_cod_error;
					$error_code=0;
					$suma_cod_error= $error_code + (stristr($error, 'duplicate key')!=FALSE)?1:0;
					$error_code= $error_code + $suma_cod_error;
					$suma_cod_error= $error_code + (stristr($error, 'numero_contrato')!=FALSE)?2:0;
					$error_code= $error_code + $suma_cod_error;
					////$firephp->warn('Captura Error:'.$error);
					////$firephp->warn('Visor:'.stristr($error, 'duplicate key'));
					////$firephp->warn('Error Code:'.$error_code);
					
					////$firephp->warn('Error Code:'.$error_code);
					switch($error_code){
						case 0:		$mensaje['mensaje'] = 'No se puede ejecutar la sentencia';
							break;	
						case 3:		$mensaje['mensaje'] = 'Error: Ya existe un contrato con el mismo número';
							break;
					}
					echo json_encode($mensaje);
				}
/*	}catch (Exception $ex){
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "Error al subir el archivo";
			echo json_encode($mensaje);
	}*/
} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>
