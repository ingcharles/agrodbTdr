<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAreas.php';
require_once '../../clases/ControladorReformaPresupuestaria.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	
	$fecha = getdate();
	$anio = $fecha['year'];
	
	$idPresupuesto = htmlspecialchars ($_POST['idPresupuesto'],ENT_NOQUOTES,'UTF-8');
	$idPlanificacionAnual = htmlspecialchars ($_POST['idPlanificacionAnual'],ENT_NOQUOTES,'UTF-8');
	$ejercicio = htmlspecialchars ($_POST['ejercicio'],ENT_NOQUOTES,'UTF-8');
	$entidad = htmlspecialchars ($_POST['entidad'],ENT_NOQUOTES,'UTF-8');
	
	$idUnidadEjecutora = htmlspecialchars ($_POST['idUnidadEjecutora'],ENT_NOQUOTES,'UTF-8');
	$unidadEjecutora = htmlspecialchars ($_POST['nombreUnidadEjecutora'],ENT_NOQUOTES,'UTF-8');
	
	$idUnidadDesconcentrada = htmlspecialchars ($_POST['idUnidadDesconcentrada'],ENT_NOQUOTES,'UTF-8');
	$unidadDesconcentrada = htmlspecialchars ($_POST['nombreUnidadDesconcentrada'],ENT_NOQUOTES,'UTF-8');
	
	$programa = htmlspecialchars ($_POST['programa'],ENT_NOQUOTES,'UTF-8');
	$subprograma = htmlspecialchars ($_POST['subprograma'],ENT_NOQUOTES,'UTF-8');
	
	$codigoProyecto = htmlspecialchars ($_POST['codigoProyecto'],ENT_NOQUOTES,'UTF-8');
	$codigoActividad = htmlspecialchars ($_POST['codigoActividad'],ENT_NOQUOTES,'UTF-8');
	$obra = htmlspecialchars ($_POST['obra'],ENT_NOQUOTES,'UTF-8');
	$geografico = htmlspecialchars ($_POST['geografico'],ENT_NOQUOTES,'UTF-8');
	
	$idRenglon = htmlspecialchars ($_POST['idRenglon'],ENT_NOQUOTES,'UTF-8');
	$nombreRenglon = htmlspecialchars ($_POST['nombreRenglon'],ENT_NOQUOTES,'UTF-8');
	$renglon = htmlspecialchars ($_POST['codigoRenglon'],ENT_NOQUOTES,'UTF-8');
	
	$renglonAuxiliar = htmlspecialchars ($_POST['renglonAuxiliar'],ENT_NOQUOTES,'UTF-8');
	$fuente = htmlspecialchars ($_POST['fuente'],ENT_NOQUOTES,'UTF-8');
	$organismo = htmlspecialchars ($_POST['organismo'],ENT_NOQUOTES,'UTF-8');
	$correlativo = htmlspecialchars ($_POST['correlativo'],ENT_NOQUOTES,'UTF-8');
	
	$idCPC = htmlspecialchars ($_POST['idCPC'],ENT_NOQUOTES,'UTF-8');
	$nombreCPC = htmlspecialchars ($_POST['nombreCPC'],ENT_NOQUOTES,'UTF-8');
	$cpc = htmlspecialchars ($_POST['codigoCPC'],ENT_NOQUOTES,'UTF-8');
	
	$idActividad = htmlspecialchars ($_POST['idActividad'],ENT_NOQUOTES,'UTF-8');
	$nombreActividad = htmlspecialchars ($_POST['nombreActividad'],ENT_NOQUOTES,'UTF-8');
	$actividad = htmlspecialchars ($_POST['codActividad'],ENT_NOQUOTES,'UTF-8');
	
	$idTipoCompra = htmlspecialchars ($_POST['idTipoCompra'],ENT_NOQUOTES,'UTF-8');
	$tipoCompra = htmlspecialchars ($_POST['nombreTipoCompra'],ENT_NOQUOTES,'UTF-8');
	
	$idProcedimientoSugerido = htmlspecialchars ($_POST['idProcedimientoSugerido'],ENT_NOQUOTES,'UTF-8');
	$procedimientoSugerido = htmlspecialchars ($_POST['nombreProcedimientoSugerido'],ENT_NOQUOTES,'UTF-8');
	
	$detalleGasto = htmlspecialchars ($_POST['detalleGasto'],ENT_NOQUOTES,'UTF-8');
	$cantidadAnual = htmlspecialchars ($_POST['cantidadAnual'],ENT_NOQUOTES,'UTF-8');
	
	$idUnidadMedida = htmlspecialchars ($_POST['idUnidadMedida'],ENT_NOQUOTES,'UTF-8');
	$unidadMedida = htmlspecialchars ($_POST['nombreUnidadMedida'],ENT_NOQUOTES,'UTF-8');
	
	$costo = htmlspecialchars ($_POST['costo'],ENT_NOQUOTES,'UTF-8');
	$iva = htmlspecialchars ($_POST['iva'],ENT_NOQUOTES,'UTF-8');
	$costoIva = $costo + (($costo*$iva)/100);
	
	$costoOriginal = htmlspecialchars ($_POST['costoOriginal'],ENT_NOQUOTES,'UTF-8');
	$ivaOriginal = htmlspecialchars ($_POST['ivaOriginal'],ENT_NOQUOTES,'UTF-8');
	$costoIvaOriginal = htmlspecialchars ($_POST['costoIvaOriginal'],ENT_NOQUOTES,'UTF-8');
	
	$idCuatrimestre = htmlspecialchars ($_POST['idCuatrimestre'],ENT_NOQUOTES,'UTF-8');
	$cuatrimestre = htmlspecialchars ($_POST['nombreCuatrimestre'],ENT_NOQUOTES,'UTF-8');
	
	$idTipoProducto = htmlspecialchars ($_POST['idTipoProducto'],ENT_NOQUOTES,'UTF-8');
	$tipoProducto = htmlspecialchars ($_POST['nombreTipoProducto'],ENT_NOQUOTES,'UTF-8');
	
	$idCatalogoElectronico = htmlspecialchars ($_POST['idCatalogoElectronico'],ENT_NOQUOTES,'UTF-8');
	$catalogoElectronico = htmlspecialchars ($_POST['nombreCatalogoElectronico'],ENT_NOQUOTES,'UTF-8');	
	
	$idFondosBID = htmlspecialchars ($_POST['idFondosBID'],ENT_NOQUOTES,'UTF-8');
	$fondosBID = htmlspecialchars ($_POST['nombreFondosBID'],ENT_NOQUOTES,'UTF-8');
	
	$idOperacionBID = htmlspecialchars ($_POST['idOperacionBID'],ENT_NOQUOTES,'UTF-8');
	$operacionBID = htmlspecialchars ($_POST['nombreOperacionBID'],ENT_NOQUOTES,'UTF-8');
	
	$idProyectoBID = htmlspecialchars ($_POST['idProyectoBID'],ENT_NOQUOTES,'UTF-8');
	$proyectoBID = htmlspecialchars ($_POST['nombreProyectoBID'],ENT_NOQUOTES,'UTF-8');
	
	$idTipoRegimen = htmlspecialchars ($_POST['idTipoRegimen'],ENT_NOQUOTES,'UTF-8');
	$tipoRegimen = htmlspecialchars ($_POST['nombreTipoRegimen'],ENT_NOQUOTES,'UTF-8');
	
	$tipoPresupuesto = htmlspecialchars ($_POST['tipoPresupuesto'],ENT_NOQUOTES,'UTF-8');

	$agregarPac = htmlspecialchars ($_POST['agregarPac'],ENT_NOQUOTES,'UTF-8');
	
	$razonCambio = htmlspecialchars ($_POST['razonCambio'],ENT_NOQUOTES,'UTF-8');
	
	$identificador = $_SESSION['usuario'];
	$idAreaFuncionario = $_SESSION['idArea'];
	
	
	
	try {
		$conexion = new Conexion();
		$ca = new ControladorAreas();
		$crp = new ControladorReformaPresupuestaria();
		
		//Área de usuario para revisión del jefe inmediato
		$areaFuncionario = pg_fetch_assoc($ca->areaUsuario($conexion, $identificador));
		$idAreaFuncionario = $areaFuncionario['id_area'];
		
		$idAreaRevisor = $areaFuncionario['id_area_padre'];
		$idRevisor = pg_fetch_result($ca->buscarResponsableSubproceso($conexion,$idAreaRevisor ), 0, 'identificador');

		$conexion->ejecutarConsulta("begin;");
		
		//crear opciones para control de cambios y auditorias!!!!!
			//todos los cambios deben ser registrados en una tabla con los campos, fechas y observaciones 
			//del usuario debe registrarse también cada proceso de revisión y aprobación o rechazo con fechas
			//de las tablas temporales y de las finales en una sola.
		
		$numControlCambios = pg_fetch_result($crp->generarNumeroControlCambios($conexion, $idPlanificacionAnual, $idPresupuesto), 0, 'numero');
		$numControlCambios++;
		
		$crp -> registrarControlCambios($conexion, $idPlanificacionAnual, $idPresupuesto, $numControlCambios, 
										$identificador, $idAreaFuncionario, $razonCambio, $detalleGasto, $idUnidadMedida, 
										$unidadMedida, $costo, $iva, $costoIva, $cuatrimestre, 
										$idRevisor, $idAreaRevisor, $estadoRevisor, $observacionRevisor, 
										'modificacionUsuario');
		
		//Cambiar de estado a la Planificación
		$crp -> enviarPlanificacionAnualTemporal($conexion, $idPlanificacionAnual, 'creado');	
		$crp -> enviarPresupuestoTemporal($conexion, $idPresupuesto, 'creado');
		
		//Verificar su hay incremento o decremento de dinero
		//--consultar si deben validarse los costos con IVA!!
		if($costo > $costoOriginal){
			$tipoCambio = 'incremento';
		}else{
			$tipoCambio = 'decremento';
		}
		
		/*
		if($costoIva > $costoIvaOriginal){
			$tipoCambio = 'incremento';
		}else{
			$tipoCambio = 'decremento';
		}		 
		 */
		
		$crp->modificarPresupuestoTemporal($conexion, $idPresupuesto, $detalleGasto, $idUnidadMedida, 
											$unidadMedida, $costo, $cuatrimestre, $iva, $costoIva, $tipoCambio);

		$conexion->ejecutarConsulta("commit;");
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Los datos fueron actualizados';

		$conexion->desconectar();
		echo json_encode($mensaje);
	
	} catch (Exception $ex){
		$conexion->ejecutarConsulta("rollback;");
		$mensaje['mensaje'] = $ex->getMessage();
		$mensaje['error'] = $conexion->mensajeError;
		$conexion->desconectar();
	}/* finally {
		$conexion->desconectar();
	}*/
	
} catch (Exception $ex) {
	$mensaje['mensaje'] = $ex->getMessage();
	$mensaje['error'] = $conexion->mensajeError;
	$conexion->desconectar();
} /*finally {
	echo json_encode($mensaje);
}*/
?>