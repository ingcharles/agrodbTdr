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
		$idProtocolo=$_POST['id_protocolo'];
		if($idProtocolo==null || $idProtocolo=='_nuevo' || $idProtocolo=='0'){
			$datoProtocolo['identificador'] = $identificador;
		}
		else{
			$datoProtocolo['id_protocolo'] = $idProtocolo;
		}
	}catch(Exception $e){}

	
	$datoProtocolo['direccion_referencia'] = htmlspecialchars ($_POST['dirReferencia'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['ci_representante_legal'] = htmlspecialchars ($_POST['ciLegal'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['email_representante_legal'] = htmlspecialchars ($_POST['correoLegal'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['normativa'] = htmlspecialchars ($_POST['normativa'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['motivo'] = htmlspecialchars ($_POST['motivo'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['cultivo_menor'] = htmlspecialchars ($_POST['boolModalidad'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['cultivo_menor']=$ce->normalizarBoolean($datoProtocolo['cultivo_menor']);
	$datoProtocolo['ci_tecnico_reconocido'] = htmlspecialchars ($_POST['ciTecnico'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['cultivo'] = htmlspecialchars ($_POST['cultivoNomCien'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['cultivo_comun'] = htmlspecialchars ($_POST['cultivoNomComun'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['uso'] = htmlspecialchars ($_POST['subTipoProducto'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['complejo_fungico'] = htmlspecialchars ($_POST['boolFungico'],ENT_NOQUOTES,'UTF-8');
	$datoProtocolo['complejo_fungico']=$ce->normalizarBoolean($datoProtocolo['complejo_fungico']);
	if($datoProtocolo['cultivo_comun']==null)
		$datoProtocolo['cultivo_comun']=$datoProtocolo['cultivo'];
	$datoProtocolo['nivel']=intval($_POST['nivel']);

	try {


		$res=$ce -> guardarProtocolo($conexion,$datoProtocolo);

		if($res['tipo']=="insert"){
			$idProtocolo = $res['resultado'][0]['id_protocolo'];
			$mensaje['id'] = $idProtocolo;
		}
		else{
			$mensaje['resultado'] = $res['resultado'];
		}
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'La solicitud ha sido actualizada';

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
