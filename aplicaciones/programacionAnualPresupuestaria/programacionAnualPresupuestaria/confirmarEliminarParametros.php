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

$elementosParametros = explode(",",$_POST['elementos']);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>

<header>
	<h1>Confirmar Eliminación</h1>
</header>

<div id="estado"></div>          
  
<form id="eliminarParametros" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="eliminarParametros" data-accionEnExito="ACTUALIZAR" >
	<input type='hidden' id='identificador' name='identificador' value="<?php echo $identificador;?>" />
	<input type='hidden' id='idParametros' name='idParametros' value="<?php echo $_POST['elementos'];?>" />
		
	<p>Los siguientes <b>Parámetros</b> serán eliminados: </p>
 	
	<?php
		if(count ($elementosParametros) > 0){
			for ($i = 0; $i < count ($elementosParametros); $i++) {
				$parametros = pg_fetch_assoc($cpp->abrirParametros($conexion, $elementosParametros[$i]));
	
				echo'<fieldset>
							<legend>Ejercicio N° </label>' .$parametros['ejercicio'].'</legend>
								<div data-linea="1">
									<label>Entidad: </label>' .$parametros['entidad'].'
								</div>
								<div data-linea="2">
									<label>Subprograma: </label>' .$parametros['subprograma'].'
								</div>
								<div data-linea="3">
									<label>Organismo: </label>' .$parametros['organismo'].'
								</div>
								<input type="hidden" name="id[]" value="'.$parametros['ejercicio'].'"/>
						</fieldset>';
				
			}	
		}else{
			echo'<fieldset>
			<legend>Ejercicio N° </label>' .$parametros['ejercicio'].'</legend>
			<label>No se han seleccionado elementos para eliminar.</label>
			</fieldset>';
		}	
	?>
			
	<button id="detalle" type="submit" class="guardar" >Eliminar</button>
	
</form>
</body>

<script type="text/javascript">
var array_parametros= <?php echo json_encode($elementosParametros); ?>;

	$(document).ready(function(){
		distribuirLineas();
		if(array_parametros == ''){
			$("#detalleItem").html('<div class="mensajeInicial">Seleccione uno o varios elementos para continuar.</div>');
		}		
	});
	
	$("#eliminarParametros").submit(function(event){
		event.preventDefault();
		ejecutarJson($(this));
	});	
	
</script>
</html>