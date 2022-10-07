<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCapacitacion.php';

$mensaje = array ();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{
	$conexion = new Conexion();
	$cv = new ControladorCapacitacion();
	try {

		$conexion->ejecutarConsulta("begin;");
		if($_POST['elementos']!=''){
			$capacitacion=explode(",",$_POST['elementos']);
			for ($i = 0; $i < count ($capacitacion); $i++) {
				if(pg_num_rows($cv->buscarRequerimiento($conexion, $capacitacion[$i]))!=0){
					$cv->eliminarRequerimiento($conexion, $capacitacion[$i]);
					$mensaje['estado'] = 'exito';
					$mensaje['mensaje'] = "Los datos han sido eliminados satisfactoriamente";
				}
			}
			
		}
		$conexion->ejecutarConsulta("commit;");

	} catch (Exception $ex) {
		$conexion->ejecutarConsulta("rollback;");
		$mensaje['mensaje'] = $ex->getMessage(); 
		$mensaje['error'] =$conexion->mensajeError;
	} finally { 
		$conexion->desconectar();
	}
} catch
(Exception $ex) {
	$mensaje['mensaje'] = $ex->getMessage();
	$mensaje['error'] = $conexion->mensajeError;
} finally { 
	echo json_encode($mensaje);
}

?>

<script type="text/javascript">
	$('#_actualizar').click();
</script>

