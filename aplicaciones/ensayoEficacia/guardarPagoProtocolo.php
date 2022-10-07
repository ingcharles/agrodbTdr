<?php
session_start();
require_once '../../clases/Conexion.php';

require_once '../../clases/ControladorEnsayoEficacia.php';

require_once './clases/Transaccion.php';

require_once './clases/Flujo.php';
require_once './clases/Perfil.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	$conexion = new Transaccion();
	$ce = new ControladorEnsayoEficacia();
	//miro si solicitud ya existe
	$datoProtocolo=array();
	$yaExiste=false;
	$identificador= $_SESSION['usuario'];
	$tramite=array();
	$tramite_flujo=array();
	try{
		$id_documento=intval($_POST['id_protocolo']);
		if($id_documento>0){
			$datoProtocolo['id_protocolo'] = $id_documento;
			
		}

		$id_flujo=intval($_POST['id_flujo']);
		$id_fase=intval($_POST['id_fase']);
	}catch(Exception $e){

	}

	try {
		//Obtengo el perfil de quien aprueba
		$perfiles= $ce->obtenerPerfiles($conexion,$identificador);
		$perfil=new Perfil($perfiles);
		if($perfil->EsOperador()){
			


			try{
				$datoProtocolo['estado'] = 'verificacionProtocolo';
				
				$conexion->Begin();
				$ce -> guardarProtocolo($conexion,$datoProtocolo);
				
				$conexion->Commit();
				$procesado=true;
				$mensaje['estado'] = 'exito';
				$mensaje['mensaje'] = 'La solicitud de protocolo ha sido enviada';
			}
			catch(Exception $e){
				$procesado=false;
				$conexion->Rollback();
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