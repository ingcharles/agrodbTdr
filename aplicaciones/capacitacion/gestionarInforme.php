<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';
require_once '../../clases/ControladorCapacitacion.php';
$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

$opcion=$_POST['opcion'];
$estado=$_POST['estadoAprobacion'];
try{

	$idRequerimiento=$_POST['idRequerimiento'];
	$objetivoCurso=$_POST['objetivoCurso'];
	$justificacionTH=$_POST['justificacionTH'];
	date_default_timezone_set("America/Guayaquil");
	setlocale(LC_TIME, 'spanish');
	$date=strftime('%d de %B de %Y',strtotime(htmlspecialchars ($_POST['fechaPartida'],ENT_NOQUOTES,'UTF-8')));
	$ciudad=$_POST['nombreCanton']!='Canton...'?htmlspecialchars ($_POST['nombreCanton'],ENT_NOQUOTES,'UTF-8'):htmlspecialchars ($_POST['ciudad'],ENT_NOQUOTES,'UTF-8');
	$financiamiento=htmlspecialchars ($_POST['eventoPagado'],ENT_NOQUOTES,'UTF-8')=='SI'?"TOTAL RECURSOS DEL ESTADO":"GRATUITO";
	$dateSolicitud=strftime('%d de %B de %Y',strtotime(htmlspecialchars ($_POST['fechaSolicitud'],ENT_NOQUOTES,'UTF-8')));
	$hoy=strftime('%d de %B de %Y',strtotime(date('Y-m-d')));
	$ocupanteNombre=$_POST["ocupanteNombre"];
	$count = count($ocupanteNombre);
	$presupuestoIndividual=$_POST['costoUnitario']/$count;
	$listado='';

	for ($i = 0; $i < $count; $i++) {
		$listado=$listado.''. $ocupanteNombre[$i].',';
	}

	$valores = array(
			'_NUMEROCAPACITACION_' =>str_pad(htmlspecialchars ($_POST['idRequerimiento'],ENT_NOQUOTES,'UTF-8'), 10, "0", STR_PAD_LEFT),
			'_TIPOFINANCIAMIENTO_' =>$financiamiento,
			'_APROBADOJEFE_' =>htmlspecialchars ($_POST['nombreDirector'],ENT_NOQUOTES,'UTF-8'),
			'_NOMBRERESPONSABLE_' =>htmlspecialchars ($_POST['nombreResponsable'],ENT_NOQUOTES,'UTF-8'),
			'_DIRECCION_' =>htmlspecialchars ($_POST['nombreArea'],ENT_NOQUOTES,'UTF-8'),
			'_TIPOEVENTO_' => htmlspecialchars ($_POST['nombreTipoEvento'],ENT_NOQUOTES,'UTF-8'),
			'_NOMBREEVENTO_' => htmlspecialchars ($_POST['nombre_evento'],ENT_NOQUOTES,'UTF-8'),
			'_OBJETIVO_' => htmlspecialchars ($_POST['objetivoCurso'],ENT_NOQUOTES,'UTF-8'),
			'_JUSTIFICACIONTH_' =>htmlspecialchars ($_POST['justificacionTH'],ENT_NOQUOTES,'UTF-8'),
			'_PERSONAL_JUSTIFICAR_' => htmlspecialchars ($_POST['justificacion'],ENT_NOQUOTES,'UTF-8'),
			'_COSTO_' => htmlspecialchars ($_POST['costoUnitario'],ENT_NOQUOTES,'UTF-8'),
			'_NOMBRE_CERTIFICACION_' => htmlspecialchars ($_POST['nombreCertificacion'],ENT_NOQUOTES,'UTF-8'),
			'_NPARTIDA_' =>htmlspecialchars ($_POST['numeroCertificacion'],ENT_NOQUOTES,'UTF-8'),
			'_FECHAPARTIDA_' => $date,
			'_CIUDAD_' => $ciudad,
			'_PAIS_' =>htmlspecialchars ($_POST['pais'],ENT_NOQUOTES,'UTF-8'),
			'_FECHAINICIO_' =>htmlspecialchars ($_POST['fechaInicio'],ENT_NOQUOTES,'UTF-8'),
			'_FECHAFIN_' =>htmlspecialchars ($_POST['fechaFin'],ENT_NOQUOTES,'UTF-8'),
			'_ASISTENTES_' =>$listado,
			'_FECHA_SOLICITUD_' =>$dateSolicitud,
			'_FECHA COMPLETA_' =>$hoy,
			'_Autor_' =>$_SESSION["usuario"]);

	try {

		$conexion = new Conexion();
		$cc = new ControladorCapacitacion();

		$find = array('/[\-\:\ ]+/', '/&lt;{^&gt;*&gt;/');

		$idDocumento="Informe".preg_replace($find, '', date('Y-m-d h:i:sa'));

		$rutaDocumento="aplicaciones/capacitacion/generados/".$idDocumento.".docx";

		$cc->rtf('informe_capacitacion', $idDocumento, $valores);
		$conexion->ejecutarConsulta("begin;");
		if(strcmp($opcion,"actualizarEstadoEI")==0){
			$cc->actualizarEstadoElaborarInforme($conexion, $idRequerimiento, $estado,$rutaDocumento, $objetivoCurso, $justificacionTH);

		}

		$cc->bloqueoAsistentes($conexion,$idRequerimiento,'1');
		$cc->actualizarPresupuesto($conexion,$idRequerimiento,$presupuestoIndividual);

		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Los datos han sido ingresados satisfactoriamente';
		$conexion->ejecutarConsulta("commit;");
		$conexion->desconectar();
		echo json_encode($mensaje);
			
	} catch (Exception $ex){
		$conexion->ejecutarConsulta("rollback;");
		pg_close($conexion);
		$error=$ex->getMessage();
		$mensaje['estado'] = 'error';
		$suma_cod_error;
		$error_code=0;
		$suma_cod_error= $error_code + (stristr($error, 'duplicate key')!=FALSE)?1:0;
		$error_code= $error_code + $suma_cod_error;
		$suma_cod_error= $error_code + (stristr($error, 'numero_contrato')!=FALSE)?2:0;
		$error_code= $error_code + $suma_cod_error;
			
		switch($error_code){
			case 0:		$mensaje['mensaje'] = 'No se puede ejecutar la sentencia';
			break;
			case 3:		$mensaje['mensaje'] = 'Error: Ya existe un contrato con el mismo número';
			break;
		}
		echo json_encode($mensaje);
	}

} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>