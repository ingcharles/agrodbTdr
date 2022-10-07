<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorGestionCalidad.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

$equivalenciasAltas = array('mayor' => 5, 'igual' => 1, 'menor' => .2);
$equivalenciasBajas = array('mayor' => .2, 'igual' => 1, 'menor' => 5);

try{
	
	$causasAAcualizar= array();
	foreach ($_POST as $indice => $valor){
		if ($indice[0] == 'c' ){
			$causas = explode('c', $indice);
			$causasAAcualizar[] = array(
					'c1' => $causas[1],
					'c2' => $causas[2],
					'valor_c1' => $equivalenciasAltas[$valor],
					'valor_c2' => $equivalenciasBajas[$valor]);
		}
	}
	
	//print_r($causasAAcualizar);
	//print_r($_POST);
	
	$hallazgo = htmlspecialchars ($_POST['hallazgo'],ENT_NOQUOTES,'UTF-8');
	$causaRaiz = htmlspecialchars ($_POST['idCausaRaiz'],ENT_NOQUOTES,'UTF-8');
	
	$nuevoEstado = 'Por definir acciones';
	try {
		$conexion = new Conexion();
		$cgc = new ControladorGestionCalidad();
		
		
		//TODO: empezar transacción BEGIN
		$cgc->actualizarValoresDeMatriz($conexion, $causasAAcualizar);
		$cgc->cambiarEstadoDeHallazgo($conexion, $hallazgo, $nuevoEstado);
		
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'La causa raíz se ha determinado exitosamente';//
		$mensaje['nuevoEstado'] = $nuevoEstado;
		$causa = pg_fetch_assoc($cgc->grabarCausaRaiz($conexion, $causaRaiz));
		$mensaje['causaRaiz']= $causa['descripcion'];
		
		//TODO: finalizar transacción COMMIT;
		$conexion->desconectar();
		echo json_encode($mensaje);
	} catch (Exception $ex){
		//TODO: finalizar transacción ROLLBACK;
		pg_close($conexion);
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