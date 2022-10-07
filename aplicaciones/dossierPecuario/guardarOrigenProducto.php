<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorDossierPecuario.php';
require_once '../../clases/ControladorEnsayoEficacia.php';

require_once '../ensayoEficacia/clases/Transaccion.php';
require_once '../ensayoEficacia/clases/Flujo.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';
try{

	$conexion = new Transaccion();
	$cp=new ControladorDossierPecuario();
	$ce=new ControladorEnsayoEficacia();

	$idUsuario= $_SESSION['usuario'];
	$id_solicitud = $_POST['id_solicitud'];

	$dato['id_solicitud'] = $id_solicitud;

	$dato['id_clasificacion_subtipo'] = htmlspecialchars ($_POST['id_clasificacion_subtipo'],ENT_NOQUOTES,'UTF-8');
	$dato['partida_arancelaria'] = htmlspecialchars ($_POST['partida_arancelaria'],ENT_NOQUOTES,'UTF-8');
	$dato['id_formulacion'] = htmlspecialchars ($_POST['id_formulacion'],ENT_NOQUOTES,'UTF-8');
	$dato['producto_cantidad'] = htmlspecialchars ($_POST['producto_cantidad'],ENT_NOQUOTES,'UTF-8');
	$dato['producto_unidad'] = htmlspecialchars ($_POST['producto_unidad'],ENT_NOQUOTES,'UTF-8');
	$dato['es_nueva_cepa'] = $ce->normalizarBoolean($_POST['es_nueva_cepa']);
	$dato['nueva_cepa'] = htmlspecialchars ($_POST['nueva_cepa'],ENT_NOQUOTES,'UTF-8');


	try {
		if($dato['es_nueva_cepa']=='1'){

			$mensaje['evaluarIngreso']='1';

			//Inyecta flujo
			$id_flujo=$_POST['id_flujo'];
			if($id_flujo==null)
				$id_flujo=$_SESSION['idAplicacion'];
			$flujos=$ce->obtenerFlujosDelDocumento($conexion,$id_flujo);
			$flujoActual=new Flujo($flujos,'DP','id_subtipo_producto',$id_flujo);
			$id_subtipo_producto= htmlspecialchars ($_POST['id_subtipo_producto'],ENT_NOQUOTES,'UTF-8');

			$flujoActual=$flujoActual->InicializarFlujo($id_subtipo_producto,'NUEVA',1);
			$identificador=$idUsuario;
			$identificador_destino=$flujoActual->PerfilSiguiente();
			$flujo=$flujoActual->BuscarFaseSiguiente();
			if($flujo!=null){
				$dato['id_flujo_documento'] =$flujo->Flujo_documento();
				$dato['estado'] =$flujo->EstadoActual();
				$fecha=new DateTime();
				$fechaSubsanacion=clone $fecha;
				$fecha = $fecha->format('Y-m-d');

				$f=$flujo->Plazo();
				$fechaSubsanacion->add(new DateInterval('P'.$f.'D'));		//AÑADE plazo en DIAS
				$fechaSubsanacion = $fechaSubsanacion->format('Y-m-d');


				try{

					$division='DIV_PICH';

					$conexion->Begin();
					$cp ->guardarSolicitud($conexion,$dato);

					$numeroTramite=$ce ->guardarTramiteDelDocumento($conexion,$flujo->TipoDocumento(),$id_solicitud,$identificador_destino,$fecha,$fechaSubsanacion,'S',$division);
					$ce->guardarFlujoDelTramite($conexion,null,$numeroTramite,$flujo->Flujo_documento(),$identificador_destino,$identificador,$identificador,'S',$fecha,$fechaSubsanacion);

					$conexion->Commit();
					$procesado=true;
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = 'La solicitud ha sido enviada';
				}
				catch(Exception $e){
					$conexion->Rollback();
					$mensaje['estado'] = 'fallo';
					$mensaje['mensaje'] = 'Error al generar el trámite';
				}
			}

		}
		else{
			$dato['nivel']=intval($_POST['nivel']);
			$mensaje['mensaje'] = 'La solicitud ha sido actualizada';
			$mensaje['evaluarIngreso']='0';
			$mensaje['estado'] = 'exito';
			$cp->guardarSolicitud($conexion,$dato);
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
	pg_close($conexion);
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}


?>



