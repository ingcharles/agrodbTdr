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

	//guardo el protocolo
	$datoProtocolo['plaguicida_tipo'] = htmlspecialchars ($_POST['tipoPlaguicida'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['plaguicida_registro'] = htmlspecialchars ($_POST['noRegistro'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['plaguicida_nombre'] = htmlspecialchars ($_POST['nombreProducto'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['plaguicida_formulacion'] = htmlspecialchars ($_POST['iaFormulacion'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['plaguicida_formulador'] = htmlspecialchars ($_POST['formuladorNombre'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['plaguicida_pais_origen'] = htmlspecialchars ($_POST['formuladorPais'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['plaguicida_no_lote'] = htmlspecialchars ($_POST['formuladorLote'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['plaguicida_permiso_importacion'] = htmlspecialchars ($_POST['docImportacionMuestra'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['plaguicida_modo_accion'] = htmlspecialchars ($_POST['plaguicida_modo_accion'],ENT_NOQUOTES,'UTF-8');

	$datoProtocolo['plaguicida_modo_accion_otro'] = htmlspecialchars ($_POST['otroModoAccion'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['plaguicida_mecanismo'] = trim(htmlspecialchars ($_POST['mecanismoAccion'],ENT_NOQUOTES,'UTF-8'));
	$datoProtocolo['pr_tiene'] = htmlspecialchars ($_POST['tienePlaguicidaReferencia'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['pr_tiene_razon'] = htmlspecialchars ($_POST['razonPlaguicidaReferencia'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['pr_registro'] = htmlspecialchars ($_POST['plagRefNoRegistro'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['pr_formulador'] = htmlspecialchars ($_POST['plagRefFormulador'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['pr_dosis'] = htmlspecialchars ($_POST['pr_dosis'],ENT_NOQUOTES,'UTF-8');

	$datoProtocolo['pr_modo_accion'] = htmlspecialchars ($_POST['pr_modo_accion'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['pr_modo_accion_otro'] = htmlspecialchars ($_POST['plagRefOtroModoAccion'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['pr_mecanismo'] = trim(htmlspecialchars ($_POST['plagRefMecanismoAccion'],ENT_NOQUOTES,'UTF-8'));
	$datoProtocolo['cp_tiene'] = htmlspecialchars ($_POST['tienePlaguicidaCoadyuvante'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['cp_registro'] = htmlspecialchars ($_POST['cyNoRegistro'],ENT_NOQUOTES,'UTF-8');

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
		$mensaje['mensaje'] = 'Composición ha sido guardada';

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