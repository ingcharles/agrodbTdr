<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorReportes.php';
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
		$ndPais = htmlspecialchars($_POST['nombrePais'], ENT_NOQUOTES, 'UTF-8');
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
		$operador= $_POST['usuario'];
		$idTransporte=$_POST['medioTransporte'];

		$idPuerto=htmlspecialchars($_POST['puertoEmbarque'], ENT_NOQUOTES, 'UTF-8');
		$nPuerto=htmlspecialchars($_POST['nombrePuerto'], ENT_NOQUOTES, 'UTF-8');
		$residencia=htmlspecialchars($_POST['residencia'], ENT_NOQUOTES, 'UTF-8');

		$varTipoProducto=$_POST['didTipoProducto'];

		$nTipoPorducto= $_POST['dnTipoProducto'];
		$idSubtipoPorducto= $_POST['didSubtipoProducto'];
		$nSubtipoPorducto= $_POST['dnSubtipoProducto'];
		$idProducto= $_POST['didProducto'];
		$nProducto= $_POST['dnProducto'];
		$sexo= $_POST['dsexo'];
		$raza= $_POST['draza'];
		$edad= $_POST['dedad'];
		$color= $_POST['dcolor'];
		$identicacionProducto= $_POST['didentificacionProducto'];
		$generarComprobante = false;
					
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
			$idSolicitud = pg_fetch_result($ce->guardarNuevaExportacion($conexion,$operador,$tipoSolicitud,$nPropietario,$idPropietario,
									$direccionPropietario, $nDestinatario,$direccionDestinatario,$idPais,$ndPais,$idUso,$nUso,$fechaEmbarque,
				$idPuestoControl,$nPuestoControl,$idProvinciaControl,$nProvinciaControl, $tipoIdentificacion, $telefonoPropietario, $correoPropietario),0,'id_solicitud');
			$ce->guardarDocumentos($conexion, $rutaVacuna, $rutaVeterinario, $rutaAnticuerpos, $idSolicitud, $rutaAutMinAmb);
			$generarComprobante = true;
		} else{
			$idSolicitud = pg_fetch_result($ce->guardarNuevaImportacion($conexion,$operador,$tipoSolicitud,$nPropietario,$idPropietario, 
									$direccionPropietario,$idPais,$ndPais,$idUso,$nUso,$idPuerto,$nPuerto,$residencia,$fechaEmbarque,
				$idPuestoControl,$nPuestoControl,$idProvinciaControl,$nProvinciaControl,$idTransporte, $tipoIdentificacion, $telefonoPropietario, $correoPropietario),0,'id_solicitud');
			$ce->guardarDocumentos($conexion, $rutaVacuna, $rutaVeterinario, $rutaAnticuerpos, $idSolicitud, $rutaAutMinAmb, $rutaZoosanitario);
		}
		
		for($i=0; $i<count($varTipoProducto);$i++){
			$ce->guardarDetalleSolicitud($conexion,$varTipoProducto[$i],$nTipoPorducto[$i],$idSubtipoPorducto[$i],$nSubtipoPorducto[$i],$idProducto[$i],$nProducto[$i],$sexo[$i],$raza[$i],$edad[$i],$color[$i],$identicacionProducto[$i],$idSolicitud);
		}

		$listaCliente =  $cc->listaComprador($conexion,$idPropietario);

		if(pg_num_rows($listaCliente)==0){
			$cc -> guardarNuevoCliente($conexion,$idPropietario,$tipoIdentificacion,$nPropietario,$direccionPropietario,$telefonoPropietario,$correoPropietario);
		}else{
			$cc -> actualizarCliente($conexion,$idPropietario,$tipoIdentificacion,$nPropietario,$direccionPropietario,$telefonoPropietario,$correoPropietario);
		}

		$conexion->ejecutarConsulta("commit;");

		if($generarComprobante){

			$jru = new ControladorReportes();
			
			$ReporteJasper= '/aplicaciones/mercanciasSinValorComercial/reportes/comprobante_exportacion_veterinario.jrxml';
			$salidaReporte= '/aplicaciones/mercanciasSinValorComercial/anexos/comprobante_exportacion_veterinario_'.$idSolicitud.'.pdf';
			$rutaArchivo= 'aplicaciones/mercanciasSinValorComercial/anexos/comprobante_exportacion_veterinario_'.$idSolicitud.'.pdf';
			
			$parameters['parametrosReporte'] = array(
				'idSolicitud'=> (int)$idSolicitud
			);

			$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'mercanciasSinValorComercial');

			$ce->actualizarComprobanteVeterinario($conexion, $idSolicitud, $rutaArchivo);
		}

		$mensaje['estado'] = 'exito';
		
		if($tipoSolicitud=="Exportacion"){
			$mensaje['mensaje'] = $idSolicitud.'-'."Los datos han sido guardados satisfactoriamente.";
		}else{
			$mensaje['mensaje'] = "Los datos han sido guardados satisfactoriamente.";
		}

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