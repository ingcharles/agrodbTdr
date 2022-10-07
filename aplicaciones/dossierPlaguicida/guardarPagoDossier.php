<?php
session_start();
require_once '../../clases/Conexion.php';


require_once '../../clases/ControladorEnsayoEficacia.php';
require_once '../../clases/ControladorDossierPlaguicida.php';

require_once '../ensayoEficacia/clases/Transaccion.php';

require_once '../ensayoEficacia/clases/Flujo.php';
require_once '../ensayoEficacia/clases/Perfil.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	$conexion = new Transaccion();
	$ce = new ControladorEnsayoEficacia();
	$cp = new ControladorDossierPlaguicida();
	//miro si solicitud ya existe
	$datosDocumento=array();
	$yaExiste=false;
	$identificador= $_SESSION['usuario'];
	$tramite=array();
	$tramite_flujo=array();
	try{
		$id_documento=intval($_POST['id_solicitud']);
		if($id_documento>0)
			$datosDocumento['id_solicitud'] = $id_documento;
		$id_tramite_flujo=intval($_POST['id_tramite']);

		$id_flujo=intval($_POST['id_flujo']);
		$id_fase=intval($_POST['id_fase']);
	}catch(Exception $e){

	}

	try {
		//Obtengo el perfil de quien aprueba
		$perfiles= $ce->obtenerPerfiles($conexion,$identificador);
		$perfil=new Perfil($perfiles);
		if($perfil->EsOperador()){
			$flujos=$ce->obtenerFlujosDelDocumento($conexion,$id_flujo);
			$flujoAnterior=new Flujo($flujos,$tramite_flujo['id_flujo_documento']);
			$flujo=$flujoAnterior->BuscarFaseSiguiente();


			try{

				$datosDocumento['estado']='asignarTecnico';
				$cp -> guardarSolicitud($conexion,$datosDocumento);

				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = 'La solicitud de protocolo ha sido enviada';
			}
			catch(Exception $e){

				$mensaje['estado'] = 'error';
				$mensaje['mensaje'] = 'Error al actualizar los datos';
			}


			$conexion->desconectar();
			echo json_encode($mensaje);
		}
		else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = 'No se identifico perfiles o tramites';
			$conexion->desconectar();
			echo json_encode($mensaje);
		}
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