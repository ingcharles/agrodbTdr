<?php
session_start();
require_once '../../clases/Conexion.php';

require_once '../../clases/ControladorEnsayoEficacia.php';
require_once '../../clases/ControladorDossierFertilizante.php';


$ce = new ControladorEnsayoEficacia();
$cf=new ControladorDossierFertilizante();

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';
try{
	$identificador= $_SESSION['usuario'];
	$id_solicitud = $_POST['id_solicitud'];
	if(!($id_solicitud==null || $id_solicitud=='_nuevo'))
		$dato['id_solicitud']=$id_solicitud;
	$dato['nivel']=intval($_POST['nivel']);
	$paso_solicitud=$_POST['paso_solicitud'];
	$esGuardar=true;
	try {
		$conexion = new Conexion();
		switch($paso_solicitud){
			case "P1":
				$dato['direccion_referencia'] = htmlspecialchars ($_POST['dirReferencia'],ENT_NOQUOTES,'UTF-8');
				$dato['ci_representante_legal'] = htmlspecialchars ($_POST['ciLegal'],ENT_NOQUOTES,'UTF-8');
				$dato['email_representante_legal'] = htmlspecialchars ($_POST['correoLegal'],ENT_NOQUOTES,'UTF-8');
				$dato['tipo_producto'] = htmlspecialchars ($_POST['tipo_producto'],ENT_NOQUOTES,'UTF-8');
				$dato['id_sitio'] = htmlspecialchars ($_POST['id_sitio'],ENT_NOQUOTES,'UTF-8');
				$dato['id_area'] = htmlspecialchars ($_POST['id_area'],ENT_NOQUOTES,'UTF-8');
				$dato['ci_representante_tecnico'] = htmlspecialchars ($_POST['ci_representante_tecnico'],ENT_NOQUOTES,'UTF-8');
				$dato['objetivo'] = htmlspecialchars ($_POST['objetivo'],ENT_NOQUOTES,'UTF-8');
				$dato['clon_registro_madre'] = htmlspecialchars ($_POST['clon_registro_madre'],ENT_NOQUOTES,'UTF-8');
				$dato['declaracion_juramentada'] = htmlspecialchars ($_POST['declaracion_juramentada'],ENT_NOQUOTES,'UTF-8');

				$dato['nivel']=intval($_POST['nivel']);
				break;
			case "P2":
				break;
			case "P3":
				$dato['producto_nombre'] = htmlspecialchars ($_POST['nombreProducto'],ENT_NOQUOTES,'UTF-8');
				$dato['id_tipo_producto'] = htmlspecialchars ($_POST['id_tipo_producto'],ENT_NOQUOTES,'UTF-8');
				$dato['id_subtipo_producto'] = htmlspecialchars ($_POST['id_subtipo_producto'],ENT_NOQUOTES,'UTF-8');
				$dato['estado_fisico'] = htmlspecialchars ($_POST['estado_fisico'],ENT_NOQUOTES,'UTF-8');
				$dato['id_formulacion'] = htmlspecialchars ($_POST['id_formulacion'],ENT_NOQUOTES,'UTF-8');
				$dato['uso'] = htmlspecialchars ($_POST['uso'],ENT_NOQUOTES,'UTF-8');
				$dato['cantidad'] = htmlspecialchars ($_POST['cantidad'],ENT_NOQUOTES,'UTF-8');
				$dato['unidad'] = htmlspecialchars ($_POST['unidad'],ENT_NOQUOTES,'UTF-8');
				break;
			case "P4":
				$dato['vida_util'] = htmlspecialchars ($_POST['vida_util'],ENT_NOQUOTES,'UTF-8');
				$dato['unidad_vida_util'] = htmlspecialchars ($_POST['unidad_vida_util'],ENT_NOQUOTES,'UTF-8');
				$dato['densidad'] = htmlspecialchars ($_POST['densidad'],ENT_NOQUOTES,'UTF-8');
				$dato['unidad_densidad'] = htmlspecialchars ($_POST['unidad_densidad'],ENT_NOQUOTES,'UTF-8');
				$dato['ph'] = htmlspecialchars ($_POST['ph'],ENT_NOQUOTES,'UTF-8');
				$dato['solubilidad'] = htmlspecialchars ($_POST['solubilidad'],ENT_NOQUOTES,'UTF-8');
				$dato['granulometria'] = htmlspecialchars ($_POST['granulometria'],ENT_NOQUOTES,'UTF-8');
				$dato['corrosividad'] = htmlspecialchars ($_POST['corrosividad'],ENT_NOQUOTES,'UTF-8');
				$dato['materia_prima'] = htmlspecialchars ($_POST['materia_prima'],ENT_NOQUOTES,'UTF-8');
				$dato['modo_preparacion'] = htmlspecialchars ($_POST['modo_preparacion'],ENT_NOQUOTES,'UTF-8');
				$dato['ambito_aplicacion'] = htmlspecialchars ($_POST['ambito_aplicacion'],ENT_NOQUOTES,'UTF-8');
				$dato['modo_aplicacion'] = htmlspecialchars ($_POST['modo_aplicacion'],ENT_NOQUOTES,'UTF-8');

				break;
			case "P5":
				$dato['dosis'] = htmlspecialchars ($_POST['dosis'],ENT_NOQUOTES,'UTF-8');
				$dato['unidad_dosis'] = htmlspecialchars ($_POST['unidadDosis'],ENT_NOQUOTES,'UTF-8');
				$dato['epoca_aplicacion'] = htmlspecialchars ($_POST['epoca_aplicacion'],ENT_NOQUOTES,'UTF-8');
				$dato['frecuencia_aplicacion'] = htmlspecialchars ($_POST['frecuencia_aplicacion'],ENT_NOQUOTES,'UTF-8');
				$dato['metodo_aplicacion'] = htmlspecialchars ($_POST['metodo_aplicacion'],ENT_NOQUOTES,'UTF-8');
				$dato['condiciones_aplicacion'] = htmlspecialchars ($_POST['condiciones_aplicacion'],ENT_NOQUOTES,'UTF-8');
				$dato['compatibilidad'] = htmlspecialchars ($_POST['compatibilidad'],ENT_NOQUOTES,'UTF-8');
				$dato['fitotoxicidad'] = htmlspecialchars ($_POST['fitotoxicidad'],ENT_NOQUOTES,'UTF-8');

				break;
			case "P6":
				$dato['metodos_analisis'] = htmlspecialchars ($_POST['metodos_analisis'],ENT_NOQUOTES,'UTF-8');
				$dato['envase_producto'] = htmlspecialchars ($_POST['envase_producto'],ENT_NOQUOTES,'UTF-8');
				$dato['materia_organica'] = htmlspecialchars ($_POST['materia_organica'],ENT_NOQUOTES,'UTF-8');
				$dato['materia_prima_organica'] = htmlspecialchars ($_POST['materia_prima_organica'],ENT_NOQUOTES,'UTF-8');
				$dato['carbono'] = htmlspecialchars ($_POST['carbono'],ENT_NOQUOTES,'UTF-8');
				$dato['nitrogeno'] = htmlspecialchars ($_POST['nitrogeno'],ENT_NOQUOTES,'UTF-8');
				$dato['humedad_minima'] = htmlspecialchars ($_POST['humedad_minima'],ENT_NOQUOTES,'UTF-8');
				$dato['humedad_maxima'] = htmlspecialchars ($_POST['humedad_maxima'],ENT_NOQUOTES,'UTF-8');

				break;
			case "P7":
				$dato['proceso_fabricacion'] = htmlspecialchars ($_POST['proceso_fabricacion'],ENT_NOQUOTES,'UTF-8');
				$dato['capacidad_neutralizadora'] = htmlspecialchars ($_POST['capacidad_neutralizadora'],ENT_NOQUOTES,'UTF-8');
				$dato['restricciones_uso'] = htmlspecialchars ($_POST['restricciones_uso'],ENT_NOQUOTES,'UTF-8');
				$dato['eliminacion_productos'] = htmlspecialchars ($_POST['eliminacion_productos'],ENT_NOQUOTES,'UTF-8');
				$dato['metodos_cultivo'] = htmlspecialchars ($_POST['metodos_cultivo'],ENT_NOQUOTES,'UTF-8');

				break;

			case "P8":
				break;

		}
		if($esGuardar){
			$res=$cf->guardarSolicitud($conexion,$dato);
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

