<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorCapacitacion.php';
require_once '../../clases/ControladorEmpleados.php';
require_once '../../clases/ControladorCatastro.php';

$conexion = new Conexion();
$cat = new ControladorCatalogos();
$cem = new ControladorEmpleados();
$cc = new ControladorCapacitacion();
$ct = new ControladorCatastro();
try {

	$identificador=$_POST['identificador'];
	$idRequerimiento=$_POST['idRequerimiento'];
	$conocimientoTrasmitidos=$_POST['conocimientosTransmitidos'];
	$nombreDirector=$_POST['nombreDirector'];
	$identificadorDirector=$_POST['identificadorDirector'];
	$conexion->ejecutarConsulta("begin;");
	$resRequerimiento=$cc->obtenerRequerimientos ($conexion,'','','',$idRequerimiento,'','','','');
	$fila = pg_fetch_assoc($resRequerimiento);
	
	$qNombreCapacitado = $cem->obtenerFichaEmpleado($conexion, $identificador);
	$nombreCapacitado = pg_fetch_assoc($qNombreCapacitado);
	if($fila['provincia']==''){
		$fila['provincia']='null';
	}
	if($fila['canton']==''){
		$fila['canton']='null';
	}
	
	$provincia = pg_fetch_assoc($cat->obtenerNombreLocalizacion($conexion, $fila['provincia']));
	$canton = pg_fetch_assoc($cat->obtenerNombreLocalizacion($conexion, $fila['canton']));
	

	$lugar=$fila['pais']=='Ecuador'?$fila['pais'].' - '.$provincia['nombre'].' - '.$canton['nombre']:$fila['pais'].' '.$fila['ciudad'];
	$funcionariosReplicados= $cc->obtenerFuncionariosReplicados($conexion,$idRequerimiento,$identificador);

	while($filaFuncionarios = pg_fetch_assoc($funcionariosReplicados)){
		$listado.=strval($filaFuncionarios['apellido'].' '.$filaFuncionarios['nombre']."\r\n\n");
	}
	
	$qContratoActivo=$ct->obtenerInformacionFuncionarioContratoActivo($conexion, $identificador);
	$contratoActivo = pg_fetch_assoc($qContratoActivo);
	
	$qContratoActivoDirector=$ct->obtenerInformacionFuncionarioContratoActivo($conexion, $identificadorDirector);
	$contratoActivoDirector = pg_fetch_assoc($qContratoActivoDirector);
	
	$valores = array(
			'_NOMBRECAPACITACION_' =>$fila['nombre_evento'],
			'_NOMBRESERVIDORCAPACITADO_' =>$nombreCapacitado['apellido'].' '.$nombreCapacitado['nombre'],
			'_CARGOSERVIDORCAPACITADO_' => $contratoActivo['nombre_puesto'],
			'_NOMBREJEFEINMEDIATO_' => $nombreDirector,
			'_CARGOJEFEINMEDIATO_' => $contratoActivoDirector['nombre_puesto'],
			'_DURACIONHORAS_' => $fila['horas'],
			'_FECHADESDE_' => $fila['fecha_inicio'],
			'_FECHAHASTA_' => $fila['fecha_fin'],
			'_LUGARCAPACITACION_' => $lugar,
			'_PARTICIPANTESREPLICACION_' => $listado,
			'_CONOCIMIENTOSTRANSMITIDOS_' =>$conocimientoTrasmitidos,
			'_Autor_' => $identificador);

	$find = array('/[\-\:\ ]+/', '/&lt;{^&gt;*&gt;/');
	$idDocumento="InformeReplica".preg_replace($find, '', date('Y-m-d h:i:sa'));
	$rutaDocumento="aplicaciones/capacitacion/generados/".$idDocumento.".docx";
	$cc->rtf('agr_dru_2', $idDocumento, $valores);
	
	$cc->actualizarDocumentoyConocimientos($conexion, $idRequerimiento,$identificador,$conocimientoTrasmitidos,$rutaDocumento);

	$resultado = pg_fetch_assoc($cc->verificarCambioEstadoFormatoReplica($conexion, $idRequerimiento));
	
	if($resultado['total'] == $resultado['estado']){
		$cc->actualizarEstadoRequerimiento($conexion, $idRequerimiento,'17');
	}

	echo '<div data-linea="5">
			<label>Archivo de replicación: </label>';
	echo $rutaDocumento==''? '<span class="alerta">No se ha generado ningún informe</span>':'<a href='.$rutaDocumento.' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a></div>';
	$conexion->ejecutarConsulta("commit;");

} catch (Exception $ex) {
	$conexion->ejecutarConsulta("rollback;");
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
	
} finally {
	$conexion->desconectar();
}

?>
<script type="text/javascript">
$(document).ready(function(){
	distribuirLineas();	
	$("#btnGenerarReporte").hide();		
});
</script>