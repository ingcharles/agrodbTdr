<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorMovilizacionAnimal.php';

$mensaje = array();
$mensaje['estado'] = 'inicio';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

	try {
		$conexion = new Conexion();
		$ma = new ControladorMovilizacionAnimal();
		$datos = array(
				'id_movilizacion_animal' => htmlspecialchars ($_POST['id_movilizacion_animal'],ENT_NOQUOTES,'UTF-8'),
				'usuario_reponsable' => $_SESSION['usuario'],
				'observacion_fiscalizacion' => htmlspecialchars ($_POST['observacionNuevaFiscalizacion'],ENT_NOQUOTES,'UTF-8'),
				'estado_fiscalizacion' => htmlspecialchars ($_POST['estadoNuevaFiscalizacion'],ENT_NOQUOTES,'UTF-8'),
				'fecha_fiscalizacion' => htmlspecialchars ($_POST['fechaNuevaFiscalizacion'],ENT_NOQUOTES,'UTF-8')
		);
		
		//TODO: GENERAR SECUENCIAL Y NUMERO DE FISCALIZACION
		$qCertificado = $ma->generarCertificadoFiscalizacionMovilizacionAnimal($conexion);
		$resultadoFiscalizacion = pg_fetch_assoc($qCertificado);
		$fecha= date('dmy');
		$secuencial = $resultadoFiscalizacion['numero']+1;
		$secuencialFiscalizacion = str_pad($secuencial, 6, "0", STR_PAD_LEFT);
		
		//TODO: GUARDAR FISCALIZACION 
		$dFiscalizador = $ma->guardarDatosFiscalizadormovilizacionAnimal($conexion, $datos['id_movilizacion_animal'], $secuencialFiscalizacion, $datos['usuario_reponsable'], $datos['observacion_fiscalizacion'], $datos['estado_fiscalizacion'], $datos['fecha_fiscalizacion']);
		
		//TODO: CAMBIAR ESTADO A FISCALIZADO EN MOVILIZACION
		$ma->actualizarEstadoFiscalizadorMovilizacionAnimal($conexion, $datos['id_movilizacion_animal']);
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Los datos han sido ingresados satisfactoriamente.';
		
		$conexion->desconectar();
		echo json_encode($mensaje);
	} catch (Exception $ex){
		pg_close($conexion);
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia";
		echo json_encode($mensaje);
	}

 

?>
