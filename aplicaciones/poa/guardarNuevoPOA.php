<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorPAPP.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$id_estrategico = $_POST['listaObjetivoEstrategico'];
	$id_proceso = $_POST['listaProcesos'];
	$id_subproceso = $_POST['listaSubprocesos'];
	$id_actividades = $_POST['listaActividades'];
	$descripcion_actividad = $_POST['descripcionActividad'];
	$usuario = $_POST['usuario'];
	$detalle = $_POST['detalleActividad'];
	$anio = $_POST['anio'];
	
	//Valores por defecto cambio
	$id_componentes = 0;
	$descripcion_componentes = '';
	$id_listaIndicadores = 0;
	$meta1 = 0;
	$meta2 = 0;
	$meta3 = 0;
	$meta4 = 0;
	/*$id_componentes = $_POST['listaComponentes'];
	$descripcion_componentes = $_POST['descripcionComponente'];
	$id_listaIndicadores = $_POST['listaIndicadores'];
	$meta1 = $_POST['meta1'];
	$meta2 = $_POST['meta2'];
	$meta3 = $_POST['meta3'];
	$meta4 = $_POST['meta4'];*/
	
		
	try {
		$conexion = new Conexion();
		$cpoa = new ControladorPAPP();
		
	    $cpoa->nuevaPlanta($conexion,$id_estrategico, $id_proceso, $id_subproceso, $id_componentes, $descripcion_componentes, $id_actividades, $descripcion_actividad,$id_listaIndicadores, $meta1, $meta2,$meta3,$meta4,$usuario, $detalle, $anio);
	    
	    $mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'El Registro de la Proforma ha sido generado satisfactoriamente';
		
		$conexion->desconectar();			
		echo json_encode($mensaje);
	} catch (Exception $ex){
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
