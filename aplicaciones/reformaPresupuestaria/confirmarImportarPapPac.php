<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorProgramacionPresupuestaria.php';
require_once '../../clases/ControladorReformaPresupuestaria.php';

$conexion = new Conexion();
$cpp = new ControladorProgramacionPresupuestaria();
$crp = new ControladorReformaPresupuestaria();
	
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
		<h1>Confirmar Importación PAP - PAC</h1>
	</header>
	
	<div id="estado"></div>          
	  
	<form id="importarPapPac" data-rutaAplicacion="reformaPresupuestaria" data-opcion="importarPapPac" data-accionEnExito="ACTUALIZAR" >
		<input type='hidden' id='identificador' name='identificador' value="<?php echo $identificador;?>" />
		
		<p>La <b>Planificación Anual Presupuestaria y el Plan Anual de Contratación</b> 
		serán <b>activados</b> para la generación de <b>Reformas Presupuestarias</b>.</p>
		
		<fieldset>
			<legend>Importación de Registros PAP - PAC</legend>
				<div data-linea="1">
					<label>Observaciones:</label>
						<input type="text" id="observaciones" name="observaciones" />
				</div>
		</fieldset>
			 			
		<button id="detalle" type="submit" class="guardar" >Importar Ejercicio</button>
		
	</form>
</body>

<script type="text/javascript">
	$(document).ready(function(){
		distribuirLineas();
	});
	
	$("#importarPapPac").submit(function(event){
		event.preventDefault();
		ejecutarJson($(this));
		
	});	
	
</script>
</html>