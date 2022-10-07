<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorReportes.php';
require_once '../../clases/ControladorCertificadoCalidad.php';


?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>

<?php
	
	$observacion = htmlspecialchars($_POST['observacion'],ENT_NOQUOTES,'UTF-8');
	$estado = htmlspecialchars($_POST['estadoFinal'],ENT_NOQUOTES,'UTF-8');
	$inspector = htmlspecialchars ($_POST['inspector'],ENT_NOQUOTES,'UTF-8');
	$idSolicitud = htmlspecialchars ($_POST['idSolicitud'],ENT_NOQUOTES,'UTF-8');
	

					
			
	$conexion = new Conexion();
	$cc = new ControladorCertificadoCalidad();
	
	$cc->actualizarResultadoInspeccionResponsable($conexion, $idSolicitud, $estado, $observacion, $inspector);
	
	
	
	/*
	//Generando orden de pago
	$filename = $reporte."_".$idCliente."_".date("Y-m-d")."_".$fecha_partir3.'_'.$fecha_partir2.'_'.$fecha_partir4.'.pdf';

	
	//Ruta del reporte compilado por Jasper y generado por IReports
	$jru = new ControladorReportes();
	
	$rutaServidor = 'agrodb';//'agrodbPrueba'ojo
	
	$Reporte=''.$_SERVER['DOCUMENT_ROOT'].'/'.$rutaServidor.'/aplicaciones/financiero/reportes/reporteOrden.jasper';
	$SalidaReporte=''.$_SERVER['DOCUMENT_ROOT'].'/'.$rutaServidor.'/aplicaciones/financiero/documentos/ordenPago/'.$filename;
	$rutaArchivo = 'aplicaciones/financiero/documentos/ordenPago/'.$filename;

	$parameters = new java('java.util.HashMap');
	$parameters ->put('idpago',(int)$fila['id_pago']);
	$parameters ->put('rutaLogo', $_SERVER['DOCUMENT_ROOT'].'/'.$rutaServidor.'/aplicaciones/general/img/logoReporte.png');
	
	$jru->runReportToPdfFile($Reporte,$SalidaReporte,$parameters,$conexion->getConnection());
	$ordenPago = $cc->abrirOrdenPago($conexion,$fila['id_pago']);
	$orden = pg_fetch_assoc($ordenPago);
	*/
	
	
	echo '<embed id="visor" src='.$rutaArchivo.' width="540" height="550">';
	
	//$cc -> guardarRutaOrdenPago($conexion,$fila['id_pago'],$rutaArchivo);
	
?>

</body>
	<script type="text/javascript">
		

		$(document).ready(function(){
			abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);
		});		

	</script>
</html>
