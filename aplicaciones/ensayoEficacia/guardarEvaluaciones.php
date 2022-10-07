<?php
session_start();
require_once '../../clases/Conexion.php';

require_once '../../clases/ControladorEnsayoEficacia.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$conexion = new Conexion();
	$ce = new ControladorEnsayoEficacia();
	//miro si solicitud ya existe
	$datoProtocolo=array();
	$yaExiste=false;
	$identificador= $_SESSION['usuario'];
	try{
		$idProtocolo=intval($_POST['id_protocolo']);
		if($idProtocolo>0)
			$datoProtocolo['id_protocolo'] = $idProtocolo;
	}catch(Exception $e){}

	//borra las anteriores evaluaciones

		try {
			$res=$ce -> borrarEvaluacionesPlagas($conexion,$idProtocolo);
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'Los datos de las plagas han sido removidos';
		}
		catch (Exception $ex){
			pg_close($conexion);
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "Error al ejecutar sentencia";
			echo json_encode($mensaje);
		}

	//Guarda las nuevas evaluaciones
		$numero=intval($_POST['plagaNoEvaluacion']);

	for($i=0;$i<$numero;$i++){
		$dato=array();
		$dato['id_protocolo']=$idProtocolo;
		$dato['nombre']=htmlspecialchars ($_POST['evalPlaga_nombre_'.$i],ENT_NOQUOTES,'UTF-8');
		$dato['intervalo']=intval ($_POST['evalPlaga_intervalo_'.$i]);
		$dato['observacion']=htmlspecialchars ($_POST['evalPlaga_observacion_'.$i],ENT_NOQUOTES,'UTF-8');
		//Guardo plaga
		try {
			$res=$ce -> guardarEvaluacionesPlagas($conexion,$dato);
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'Item de la plaga ha sido guardado';
			$yaExiste=true;
		}
		catch (Exception $ex){
			pg_close($conexion);
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "Error al ejecutar sentencia";
			echo json_encode($mensaje);
		}

	}


	//guardo el protocolo
	$datoProtocolo['condicion_suelo'] = htmlspecialchars ($_POST['condicion_suelo'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['condicion_suelo_otro'] = htmlspecialchars ($_POST['condicionSueloOtro'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['condicion_ambiental'] = htmlspecialchars ($_POST['condicion_ambiental'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['condicion_ambiental_otro'] = htmlspecialchars ($_POST['condicionAmbientalOtro'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['muestreo_unidad'] = htmlspecialchars ($_POST['mmEvaluacion'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['muestreo_unidad_otro'] = htmlspecialchars ($_POST['mmEvaluacionOtro'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['muestreo_planta'] = htmlspecialchars ($_POST['mmNumeroPlanta'],ENT_NOQUOTES,'UTF-8'); //intval($_POST['mmNumeroPlanta']);
	$datoProtocolo['muestreo_experimento'] = htmlspecialchars ($_POST['mmNumeroUnidad'],ENT_NOQUOTES,'UTF-8');	//intval ($_POST['mmNumeroUnidad']);
	$datoProtocolo['plaga_eval_numero'] = intval ($_POST['plagaNoEvaluacion']);

	$datoProtocolo['plaga_eval_escala'] = htmlspecialchars ($_POST['tieneEscalaEvaluacion'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['plaga_eval_escala_ref'] = htmlspecialchars ($_POST['escalaEvaluacion'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['plaga_eval_escala_diseno'] = htmlspecialchars ($_POST['escalaEvaluacionDis'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['plaga_eval_escala_describir'] = trim(htmlspecialchars ($_POST['plaga_eval_escala_describir'],ENT_NOQUOTES,'UTF-8'));

	$datoProtocolo['plaga_eval_variable'] = htmlspecialchars ($_POST['varEvaluar'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['plaga_eval_eficacia'] = htmlspecialchars ($_POST['evalEficacia'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['plaga_eval_eficacia_otro'] = htmlspecialchars ($_POST['evalEficaciaOtra'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['plaga_eval_info'] = trim(htmlspecialchars ($_POST['evalOtraInfo'],ENT_NOQUOTES,'UTF-8'));
	
	$datoProtocolo['plaga_eval_escala']=$ce->normalizarBoolean($datoProtocolo['plaga_eval_escala']);


	$datoProtocolo['nivel']=intval($_POST['nivel']);

	try {


		$res=$ce -> guardarProtocolo($conexion,$datoProtocolo);
		if($res['tipo']=="insert")
			$idProtocolo = $res['resultado'][0]['id_protocolo'];
		else
			$fila=$res['resultado'];

		$mensaje['id'] = $idProtocolo;
		$mensaje['dato'] = $res['resultado'];
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Los datos han sido guardados satisfactoriamente';

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