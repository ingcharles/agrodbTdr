<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEtiquetas.php';

$conexion = new Conexion();
$ce = new ControladorEtiquetas();

	try {
		
		$conexion->ejecutarConsulta("begin;");
		$solicitudes=explode(",",$_POST['id']);
		
		for ($i = 0; $i < count ($solicitudes); $i++) {
			$qDatosSolicitud=$ce->abrirSolicitudEtiquetasEnviada($conexion,$solicitudes[$i]);
			if($qDatosSolicitud[0]['estado']=='Enviado')
			$ce->eliminarSolicitudEtiqueta($conexion, $solicitudes[$i]);
		}
		
		$conexion->ejecutarConsulta("commit;");
		
	} catch (Exception $ex) {
		$conexion->ejecutarConsulta("rollback;");
	} finally {
		$conexion->desconectar();
	}
?>
<script type="text/javascript">
	$(document).ready(function(){
		abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);
	});		
</script>
