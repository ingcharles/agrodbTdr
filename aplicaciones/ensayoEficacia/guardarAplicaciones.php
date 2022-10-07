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

	//gurado los tratamientos
	$numero=intval ($_POST['tratamientos']);

	//borra lo anterior
	if($numero>0){
		try {
			$res=$ce -> borrarTratamientos($conexion,$idProtocolo);
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'Los datos de las plagas han sido removidos';
		}
		catch (Exception $ex){
			pg_close($conexion);
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "Error al ejecutar sentencia";
			echo json_encode($mensaje);
		}
	}
	//Guarda los nuevos tratamientos
	if($numero>8)
		$numero=8;
	for($i=1;$i<=$numero;$i++){
		$codigo="$i";
		$dosis=htmlspecialchars ($_POST['tratamientoT'.$i],ENT_NOQUOTES,'UTF-8');
		//Guardo tratamiento
		try {
			$res=$ce -> guardarTratamientos($conexion,$idProtocolo,$codigo,$dosis);
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
	$datoProtocolo['tipo_aplicacion'] = htmlspecialchars ($_POST['tipoAplicacion'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['tipo_aplicacion_otro'] = htmlspecialchars ($_POST['tipoAplicacionOtro'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['equipo_usado'] = htmlspecialchars ($_POST['tipoEquipoUso'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['equipo_usado_otro'] = htmlspecialchars ($_POST['tipoEquipoUsoOtro'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['tipo_boquilla'] = htmlspecialchars ($_POST['tipoBoquilla'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['momento_aplicacion'] = htmlspecialchars ($_POST['cantidadAplicacion'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['aplicacion_fenologia'] = htmlspecialchars ($_POST['aplicacionFenologia'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['aplicacion_umbral'] = htmlspecialchars ($_POST['aplicacionUmbral'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['aplicacion_intervalo'] = intval ($_POST['aplicacionIntervalo']);
	$datoProtocolo['tiene_unidad_dosis'] = htmlspecialchars ($_POST['tieneUnidadDosis'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['unidad_dosis'] = htmlspecialchars ($_POST['unidadDosis'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['unidad_dosis_otro'] = htmlspecialchars ($_POST['unidadDosisOtra'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['tratamientos_info'] = trim(htmlspecialchars ($_POST['tratamientosInfo'],ENT_NOQUOTES,'UTF-8'));
	$datoProtocolo['equipo_proteccion'] = htmlspecialchars ($_POST['equipo_proteccion'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['equipo_proteccion_otro'] = htmlspecialchars ($_POST['equipoProteccionOtro'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['estadio'] = htmlspecialchars ($_POST['estadioInsecto'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['estadio_otro'] = htmlspecialchars ($_POST['estadioInsectoOtro'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['aplicacion_funguicida'] = htmlspecialchars ($_POST['aplicarFungicida'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['aplicacion_herbicida'] = htmlspecialchars ($_POST['aplicarHerbicida'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['modo_aplicacion_info'] = trim(htmlspecialchars ($_POST['otraInformacion'],ENT_NOQUOTES,'UTF-8'));

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
		$mensaje['mensaje'] = 'Los datos han sido creados satisfactoriamente';

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