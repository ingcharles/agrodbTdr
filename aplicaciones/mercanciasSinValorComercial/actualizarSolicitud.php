<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCertificados.php';
require_once '../../clases/ControladorMercanciasSinValorComercial.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';


try{
	$conexion = new Conexion();
	$cc = new ControladorCertificados();
	$ce = new ControladorMercanciasSinValorComercial();

	try {
		$idSolicitud = htmlspecialchars($_POST['solicitud'], ENT_NOQUOTES, 'UTF-8');
		$tipoSolicitud = htmlspecialchars($_POST['tipoSolicitud'], ENT_NOQUOTES, 'UTF-8');
		$nPropietario = htmlspecialchars($_POST['nombrePropietario'], ENT_NOQUOTES, 'UTF-8');
		$idPropietario = htmlspecialchars($_POST['identificacionPropietario'], ENT_NOQUOTES, 'UTF-8');
		$direccionPropietario = htmlspecialchars($_POST['direccionPropietario'], ENT_NOQUOTES, 'UTF-8');
		$tipoIdentificacion = htmlspecialchars($_POST['tipoIdentificacion'], ENT_NOQUOTES, 'UTF-8');
		$telefonoPropietario = htmlspecialchars($_POST['telefonoPropietario'], ENT_NOQUOTES, 'UTF-8');
		$correoPropietario = htmlspecialchars($_POST['correoPropietario'], ENT_NOQUOTES, 'UTF-8');
		$nDestinatario = htmlspecialchars($_POST['nombreDestinatario'], ENT_NOQUOTES, 'UTF-8');
		$direccionDestinatario = htmlspecialchars($_POST['direccionDestinatario'], ENT_NOQUOTES, 'UTF-8');
		$idPais = htmlspecialchars($_POST['pais'], ENT_NOQUOTES, 'UTF-8');
		$nPais = htmlspecialchars($_POST['nombrePais'], ENT_NOQUOTES, 'UTF-8');
		$idUso = htmlspecialchars($_POST['uso'], ENT_NOQUOTES, 'UTF-8');
		$nUso = htmlspecialchars($_POST['nombreUso'], ENT_NOQUOTES, 'UTF-8');
		$fechaEmbarque = htmlspecialchars($_POST['fechaEmbarque'], ENT_NOQUOTES, 'UTF-8');
		$idPuestoControl = htmlspecialchars($_POST['puestoControl'], ENT_NOQUOTES, 'UTF-8');
		$nPuestoControl = htmlspecialchars($_POST['nombrePuestoControl'], ENT_NOQUOTES, 'UTF-8');
		$idProvinciaControl = htmlspecialchars($_POST['idProvincia'], ENT_NOQUOTES, 'UTF-8');
		$nProvinciaControl = htmlspecialchars($_POST['nombreProvincia'], ENT_NOQUOTES, 'UTF-8');
		$rutaVacuna = htmlspecialchars($_POST['rutaVacuna'], ENT_NOQUOTES, 'UTF-8');
		$rutaVeterinario = htmlspecialchars($_POST['rutaVeterinario'], ENT_NOQUOTES, 'UTF-8');
		$rutaAnticuerpos = htmlspecialchars($_POST['rutaAnticuerpos'], ENT_NOQUOTES, 'UTF-8');
		$rutaAutMinAmb = htmlspecialchars($_POST['rutaAutMinAmb'], ENT_NOQUOTES, 'UTF-8');
		$rutaZoosanitario = htmlspecialchars($_POST['rutaZoosanitario'], ENT_NOQUOTES, 'UTF-8');
		$idTransporte = $_POST['medioTransporte'];
		
		$idPuerto=htmlspecialchars($_POST['puertoEmbarque'], ENT_NOQUOTES, 'UTF-8');
		$nPuerto=htmlspecialchars($_POST['nombrePuerto'], ENT_NOQUOTES, 'UTF-8');
		$residencia=htmlspecialchars($_POST['residencia'], ENT_NOQUOTES, 'UTF-8');
					
		if($rutaAnticuerpos=="0"){
			$rutaAnticuerpos='';
		}
		
		if($rutaAutMinAmb=="0"){
			$rutaAutMinAmb='';
		}
		
		if($rutaZoosanitario=="0"){
			$rutaZoosanitario='';
		}

		$conexion->ejecutarConsulta("begin;");
		
		if($tipoSolicitud=="Exportacion"){
			$ce->actualizarSolicitud($conexion,$idSolicitud,$tipoSolicitud,$nPropietario,$idPropietario, $direccionPropietario, $nDestinatario,$direccionDestinatario,$idPais,$nPais,$idUso,$nUso,$fechaEmbarque,$idPuestoControl,$nPuestoControl,$idProvinciaControl,$nProvinciaControl, $tipoIdentificacion, $telefonoPropietario, $correoPropietario);
		} else{
			$ce->actualizarImportacion($conexion,$idSolicitud,$tipoSolicitud,$nPropietario,$idPropietario, $direccionPropietario,$idPais,$nPais,$idPuerto,$nPuerto,$residencia,$fechaEmbarque,$idPuestoControl,$nPuestoControl,$idProvinciaControl,$nProvinciaControl,$idTransporte,$idUso,$nUso, $tipoIdentificacion, $telefonoPropietario, $correoPropietario);
		}

		$ce->actualizarDocumentos($conexion, $rutaVacuna, $rutaVeterinario, $rutaAnticuerpos, $rutaAutMinAmb, $idSolicitud, $rutaZoosanitario);

		$listaCliente =  $cc->listaComprador($conexion,$idPropietario);
		
		if(pg_num_rows($listaCliente)==0){
			$cc -> guardarNuevoCliente($conexion,$idPropietario,$tipoIdentificacion,$nPropietario,$direccionPropietario,$telefonoPropietario,$correoPropietario);
		}else{
			$cc -> actualizarCliente($conexion,$idPropietario,$tipoIdentificacion,$nPropietario,$direccionPropietario,$telefonoPropietario,$correoPropietario);
		}

		$conexion->ejecutarConsulta("commit;");

		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = "Los datos han sido guardados satisfactoriamente.";

	} catch (Exception $ex) {
		$conexion->ejecutarConsulta("rollback;");
		$mensaje['mensaje'] = $ex->getMessage();
		$mensaje['error'] = $conexion->mensajeError;
	} finally {
		$conexion->desconectar();
	}
} catch (Exception $ex) {
	$mensaje['mensaje'] = $ex->getMessage();
	$mensaje['error'] = $conexion->mensajeError;
} finally {
	echo json_encode($mensaje);
}