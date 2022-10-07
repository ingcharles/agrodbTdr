<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacionAnimal.php';

$conexion = new Conexion();
$vdr = new ControladorVacunacionAnimal();

print_r($_POST);

$datos = array(
	'numero_documento' => htmlspecialchars ($_POST['numero_documento'],ENT_NOQUOTES,'UTF-8'),
	'usuario_responsable' => htmlspecialchars ($_POST['usuario_responsable'],ENT_NOQUOTES,'UTF-8'),
	'observacion' => htmlspecialchars ($_POST['observacion'],ENT_NOQUOTES,'UTF-8'),
	'estado' => htmlspecialchars ($_POST['cmbTipoAnulacion'],ENT_NOQUOTES,'UTF-8')		
);
					
if($datos['estado']=='anulado'){
	echo ($datos['numero_documento']);
	//Paso 1.- Cambia estado => g_vacunacion_animal.serie_documentos
	$p1 = $vdr->actualizarEstadoSerieDocumentos($conexion, $datos['numero_documento'], $datos['observacion'], $datos['estado']);		
	//Paso 2.- Cambia estado => g_vacunacion_animal.vacuna_animales
	$p2 = $vdr->actualizarEstadoVacunacion($conexion, $datos['numero_documento'], $datos['observacion'], $datos['estado'], $datos['usuario_responsable']);
	//Paso 3.- Cambia estado => g_vacunacion_animal.catastro
	$p3 = $vdr->catastroEstadoVacunacion($conexion, $datos['numero_documento']);		
}	
	
if($datos['estado']=='cambiado'){
	$dEmisorMovilizacion = $cm->actualizarEstadoMovilizacion($conexion,	$datos['numero_documento'], $datos['observacion'], $datos['estado']);
}

$conexion->desconectar();
echo "Guardar los datos";	

?>