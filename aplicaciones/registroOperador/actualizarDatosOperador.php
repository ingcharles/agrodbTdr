<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorCertificados.php';
require_once '../../clases/ControladorRegistroOperador.php';


$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$conexion = new Conexion();
	$cc = new ControladorCatalogos();
		
	$identificador = $_POST['identificador'];
	$razon = $_POST['razon'];
	$nombreLegal = $_POST['nombreLegal'];
	$apellidoLegal = $_POST['apellidoLegal'];
	$nombreTecnico = $_POST['nombreTecnico'];
	$apellidoTecnico = $_POST['apellidoTecnico'];
	$idProvincia = $_POST['provincia'];
	$idCanton = $_POST['canton'];
	$idParroquia = $_POST['parroquia'];
	$direccion = $_POST['direccion'];
	$telefono1 = $_POST['telefono1'];
	$telefono2 = $_POST['telefono2'];
	$celular1 = $_POST['celular1'];
	$celular2 = $_POST['celular2'];
	$fax = $_POST['fax'];
	$correo = $_POST['correo'];
	$registroOrquideas = $_POST['registroOrquideas'];
	$registroMadera = $_POST['registroMadera'];
	$correoFacturacion = $_POST['correoFacturacion'];
	$tipoActividad = $_POST['tipoActividad'];
	//$gs1 = $_POST['gs1'];
	
	$qLocalizacion = $cc->obtenerNombreLocalizacion($conexion, $idProvincia);
	$provincia = pg_fetch_assoc($qLocalizacion);
	
	$qLocalizacion = $cc->obtenerNombreLocalizacion($conexion, $idCanton);
	$canton = pg_fetch_assoc($qLocalizacion);
	
	$qLocalizacion = $cc->obtenerNombreLocalizacion($conexion, $idParroquia);
	$parroquia = pg_fetch_assoc($qLocalizacion);
	
	try {

		$cr = new ControladorRegistroOperador();
		$ccert = new ControladorCertificados();
		
		$operador = $cr->buscarOperador($conexion, $identificador);
		$datosFacturacion = $ccert -> listaComprador($conexion,$identificador);
		
		if (pg_num_rows($operador) > 0){
			
			$cr->actualizarDatosOperador($conexion, $identificador,$razon,$nombreLegal,$apellidoLegal,$nombreTecnico,$apellidoTecnico,$provincia['nombre'],$canton['nombre'],
					$parroquia['nombre'],$direccion,$telefono1,$telefono2,$celular1,$celular2,$fax,$correo,$registroOrquideas,$registroMadera, $tipoActividad);
			
			$tipoCliente = (strlen($identificador) == '13' ? '04': '05' );
			$razonSocial = ($razon == '' ? $nombreLegal.' '.$apellidoLegal : $razon);
			
			if(pg_num_rows($datosFacturacion) == 0){							
				$ccert -> guardarNuevoCliente($conexion,$identificador, $tipoCliente, $razonSocial, $direccion, $telefono1, $correoFacturacion);				
			}else{				
				$ccert -> actualizarMailCliente($conexion, $identificador, $razonSocial, $direccion, $telefono1, $correoFacturacion);				
			}
			
			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente';
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = 'Error al actualizar sus datos, por favor contáctese con Agrocalidad';
		}
		
		
		
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