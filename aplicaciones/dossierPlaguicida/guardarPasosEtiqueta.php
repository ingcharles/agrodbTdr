<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorDossierPlaguicida.php';
require_once '../../clases/ControladorEnsayoEficacia.php';

$cg=new ControladorDossierPlaguicida();
$ce = new ControladorEnsayoEficacia();

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';
try{
	$identificador= $_SESSION['usuario'];
	$id_solicitud = $_POST['id_solicitud'];

	$dato['id_solicitud']=$id_solicitud;
	
	$paso_solicitud=$_POST['paso_solicitud'];
	$esGuardar=true;
	try {
		$conexion = new Conexion();
		switch($paso_solicitud){

			case "E1":

				$dato['precaucion_uso'] = trim(htmlspecialchars ($_POST['precaucion_uso'],ENT_NOQUOTES,'UTF-8'));
				$dato['medidas_seguridad'] = trim(htmlspecialchars ($_POST['medidas_seguridad'],ENT_NOQUOTES,'UTF-8'));
				$dato['almacen_manejo'] = trim(htmlspecialchars ($_POST['almacen_manejo'],ENT_NOQUOTES,'UTF-8'));
				$dato['medidas_auxilio'] = trim(htmlspecialchars ($_POST['medidas_auxilio'],ENT_NOQUOTES,'UTF-8'));
				$dato['nota_medico'] = trim(htmlspecialchars ($_POST['nota_medico'],ENT_NOQUOTES,'UTF-8'));
				$dato['medidas_envases'] = trim(htmlspecialchars ($_POST['medidas_envases'],ENT_NOQUOTES,'UTF-8'));
				$dato['medidas_ambiente'] = trim(htmlspecialchars ($_POST['medidas_ambiente'],ENT_NOQUOTES,'UTF-8'));
				$dato['instruccion_uso'] = trim(htmlspecialchars ($_POST['instruccion_uso'],ENT_NOQUOTES,'UTF-8'));
				$dato['modo_empleo'] = trim(htmlspecialchars ($_POST['modo_empleo'],ENT_NOQUOTES,'UTF-8'));
				$dato['epoca_aplicacion'] = trim(htmlspecialchars ($_POST['epoca_aplicacion'],ENT_NOQUOTES,'UTF-8'));
				$dato['periodo_reingreso'] = trim(htmlspecialchars ($_POST['periodo_reingreso'],ENT_NOQUOTES,'UTF-8'));
				$dato['fitoxicidad'] = trim(htmlspecialchars ($_POST['fitoxicidad'],ENT_NOQUOTES,'UTF-8'));
				$dato['compatibilidad'] = trim(htmlspecialchars ($_POST['compatibilidad'],ENT_NOQUOTES,'UTF-8'));
				$dato['id_categoria_toxicologica'] = htmlspecialchars ($_POST['id_categoria_toxicologica'],ENT_NOQUOTES,'UTF-8');
				$dato['rotulo_veneno'] = trim(htmlspecialchars ($_POST['rotulo_veneno'],ENT_NOQUOTES,'UTF-8'));
				$dato['responsabilidad'] = trim(htmlspecialchars ($_POST['responsabilidad'],ENT_NOQUOTES,'UTF-8'));
				
				break;

			case "P11":
				break;
		}
		if($esGuardar){
			$res=$cg->guardarEtiqueta($conexion,$dato);
			if($res['tipo']=="insert")
				$id_solicitud = $res['resultado'][0]['id_solicitud'];
			else
				$fila=$res['resultado'];

			$mensaje['id'] = $id_solicitud;
			$mensaje['dato'] = $res['resultado'];
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'La solicitud ha sido actualizada';
		}
		$conexion->desconectar();

		echo json_encode($mensaje);

	}
	catch (Exception $ex){
		pg_close($conexion);
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia";
		echo json_encode($mensaje);
	}
}
catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}

?>

