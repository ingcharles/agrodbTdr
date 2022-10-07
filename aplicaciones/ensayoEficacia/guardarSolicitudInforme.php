<?php
session_start();
require_once '../../clases/Conexion.php';

require_once '../../clases/ControladorEnsayoEficacia.php';

$ce = new ControladorEnsayoEficacia();

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';
try{
	$identificador= $_SESSION['usuario'];
	$id_solicitud = $_POST['id_solicitud'];

	$dato['id_informe']=$id_solicitud;
	$dato['nivel']=intval($_POST['nivel']);
	$paso_solicitud=$_POST['paso_solicitud'];
	$esGuardar=true;
	try {
		$conexion = new Conexion();
		switch($paso_solicitud){
			case "P2":
				break;
			case "P3":
				$dato['caracteristica'] = htmlspecialchars ($_POST['caracteristica'],ENT_NOQUOTES,'UTF-8');
				$dato['ambito'] = htmlspecialchars ($_POST['ambito'],ENT_NOQUOTES,'UTF-8');
				$dato['efecto_plagas'] = htmlspecialchars ($_POST['efecto_plagas'],ENT_NOQUOTES,'UTF-8');
				$dato['condiciones'] = htmlspecialchars ($_POST['condiciones'],ENT_NOQUOTES,'UTF-8');
				$dato['metodo_aplicacion'] = htmlspecialchars ($_POST['metodo_aplicacion'],ENT_NOQUOTES,'UTF-8');
				$dato['instrucciones'] = htmlspecialchars ($_POST['instrucciones'],ENT_NOQUOTES,'UTF-8');
				$dato['numero_aplicacion'] = htmlspecialchars ($_POST['numero_aplicacion'],ENT_NOQUOTES,'UTF-8');
				$dato['eficacia'] = htmlspecialchars ($_POST['eficacia'],ENT_NOQUOTES,'UTF-8');
				$dato['dosis'] = htmlspecialchars ($_POST['dosis'],ENT_NOQUOTES,'UTF-8');
				$dato['dosis_unidad'] = htmlspecialchars ($_POST['dosis_unidad'],ENT_NOQUOTES,'UTF-8');
				
				$dato['gasto_agua'] = htmlspecialchars ($_POST['gasto_agua'],ENT_NOQUOTES,'UTF-8');
				$dato['fitotoxicidad'] = htmlspecialchars ($_POST['fitotoxicidad'],ENT_NOQUOTES,'UTF-8');
				$dato['conclusiones'] = htmlspecialchars ($_POST['conclusiones'],ENT_NOQUOTES,'UTF-8');
				$dato['recomendaciones'] = htmlspecialchars ($_POST['recomendaciones'],ENT_NOQUOTES,'UTF-8');

				//recupera datos de la matriz
				foreach($_POST as $key=>$item){
					if(substr($key,0,11)=="evaluacion_"){
						$vector=explode('_',$key);
						$valFloat=floatval($item);
						$ce->guardarMatrizEficacia($conexion,$id_solicitud,$vector[1],$vector[2],$valFloat);
					}
				}
				$idProtocolo=$_POST['id_protocolo'];
				$tipoEvaluacion=$_POST['plaga_eval_eficacia'];
				if($tipoEvaluacion!='VEE_OTRO')
					$mensaje['eficacia'] =$ce->obtenerMatrizEvaluacionEficacia($conexion,$idProtocolo,$tipoEvaluacion,$id_solicitud);

				break;
			
			case "P4":
				$dato['ruta'] = htmlspecialchars ($_POST['archivo'],ENT_NOQUOTES,'UTF-8');
				$res=$ce->guardarInformeFinal($conexion,$dato);
				$res=$ce->obtenerInformeFinalEnsayo($conexion,$dato['id_informe']);

				$mensaje['datos']=$res['ruta'];
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = 'El documento ha sido cargado.';
				$esGuardar=false;

				break;
			case "P5":
				
				$esGuardar=false;
				break;
		}
		if($esGuardar){
			$res=$ce->guardarInformeFinal($conexion,$dato);
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

