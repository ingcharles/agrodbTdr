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


	$datoProtocolo['identificador'] = $identificador;
	//Primero borra las ubicaciones anteriores
	try {
		$res=$ce -> borrarUbicacionGeografica($conexion,$idProtocolo);
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Los datos han sido creados satisfactoriamente';
	}catch (Exception $ex){
		pg_close($conexion);
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia";
		echo json_encode($mensaje);
	}
	//luego guardo las ubicacines geograficas 1
	$datosUbica=array();
	$datosUbica['id_protocolo'] = $idProtocolo;
	$datosUbica['provincia'] = intval ($_POST['ubicaAgoProvincia']);
	$datosUbica['canton'] = intval ($_POST['ubicaAgoCanton']);
	$datosUbica['parroquia'] = intval ($_POST['ubicaAgoParroquia']);
	$datosUbica['zona']="z1";
	try {
		$res=$ce -> guardarUbicacionGeografica($conexion,$datosUbica);
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Los datos han sido creados satisfactoriamente';
	}catch (Exception $ex){
		pg_close($conexion);
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia";
		echo json_encode($mensaje);
	}
	//luego guardo las ubicacines geograficas 2
	$datosUbica==array();
	$datosUbica['id_protocolo'] = $idProtocolo;
	$datosUbica['provincia'] = intval ($_POST['ubicaAgoProvincia2']);
	$datosUbica['canton'] = intval ($_POST['ubicaAgoCanton2']);
	$datosUbica['parroquia'] = intval ($_POST['ubicaAgoParroquia2']);
	$datosUbica['zona']="z2";
	try {
		$res=$ce -> guardarUbicacionGeografica($conexion,$datosUbica);
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Los datos han sido creados satisfactoriamente';
	}catch (Exception $ex){
		pg_close($conexion);
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia";
		echo json_encode($mensaje);
	}

	//guardo el protocolo
	$datoProtocolo['condicion_experimento'] = htmlspecialchars ($_POST['condExperimento'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['diseno_experimento'] = htmlspecialchars ($_POST['expTipoDis'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['diseno_otro_text'] = trim(htmlspecialchars ($_POST['expTipoOtroDes'],ENT_NOQUOTES,'UTF-8'));
	$datoProtocolo['diseno_otro'] = htmlspecialchars ($_POST['expTipoOtro'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['parcela_total'] = intval($_POST['parcelaTotal']);
	$datoProtocolo['parcela_unidad'] = intval($_POST['parcelaUnidad']);
	$datoProtocolo['parcela_util'] = intval($_POST['parcelaUtil']);
	$datoProtocolo['tratamientos'] = intval($_POST['noTratamientos']);
	$datoProtocolo['repeticiones'] = intval($_POST['noRepeticiones']);
	$datoProtocolo['observaciones'] = intval($_POST['noObservaciones']);

	$datoProtocolo['experimento_otra_info'] = trim(htmlspecialchars ($_POST['otraConsideracion'],ENT_NOQUOTES,'UTF-8'));

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
		$mensaje['mensaje'] = 'Condición del experimento guardada';

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