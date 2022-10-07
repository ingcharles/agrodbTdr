<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorProgramacionPresupuestaria.php';

$conexion = new Conexion();
$cpp = new ControladorProgramacionPresupuestaria();
	
$fecha = getdate();
$anio = $fecha['year'];

$identificador=$_SESSION['usuario'];

if($identificador==''){
	$usuario=0;
}else{
	$usuario=1;
	$idAreaFuncionario = $_SESSION['idArea'];
	$nombreProvinciaFuncionario = $_SESSION['nombreProvincia'];
}

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>

<header>
	<h1>Confirmar Cierre de Proceso</h1>
</header>

<div id="estado"></div>          
  
<form id="enviarCierre" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="cerrarProcesoDGPGE" data-accionEnExito="ACTUALIZAR" >
	<input type='hidden' id='identificador' name='identificador' value="<?php echo $identificador;?>" />
	
	<p>Al finalizar el proceso, la <b>Planificación Anual Revisada por la Dirección General de Planificación y Gestión 
	Estratégica</b> será <b>aprobada</b> para la generación de la Matriz PAP final.</p>
 			
	<button id="detalle" type="submit" class="guardar" >Cerrar Proceso</button>
	
</form>
</body>

<script type="text/javascript">
var array_planificacion_anual= <?php echo json_encode($elementosPlanificacionAnual); ?>;

	$(document).ready(function(){
		distribuirLineas();
		if(array_planificacion_anual == ''){
			$("#detalleItem").html('<div class="mensajeInicial">Seleccione uno o varios elementos para continuar.</div>');
		}		
	});
	
	$("#enviarCierre").submit(function(event){
		event.preventDefault();
		ejecutarJson($(this));
	});	
	
</script>
</html>