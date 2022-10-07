<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEmpleadoEmpresa.php';
$conexion = new Conexion();
$cee = new ControladorEmpleadoEmpresa();
$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');

switch ($opcion) {
	case 'obtenerEmpleado':
		$qEmpleadoEmpresaRol = $cee->obtenerEmpleadoEmpresa($conexion,$_POST['identificadorEmpleado'],$_POST['nombreEmpleado']);
		echo '<label>Empleados: </label>';
		echo '<select id="empleado" name="empleado">';
		while ($fila = pg_fetch_assoc($qEmpleadoEmpresaRol)){
			echo '<option  value="'. $fila['identificador'].'" >'.$fila['nombres']. ' - ' .$fila['identificador'].'</option>';
		}
		echo '<option value="0">Seleccione...</option>';
		echo '</select>';
		$alerta=pg_num_rows($qEmpleadoEmpresaRol)!=0?1:0;
	break;
}
?>

<script type="text/javascript">     
var alertaEstado= <?php echo json_encode($alerta); ?>;
	$(document).ready(function(){
		if(alertaEstado==0){
			$("#estado").html('No existe usuario').addClass('alerta');
		}else{
			$("#estado").html('');
		}	
		 distribuirLineas();
	});
</script>