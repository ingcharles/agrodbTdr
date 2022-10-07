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

$elementosRenglo = explode(",",$_POST['elementos']);
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
  
<form id="eliminarRenglon" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="eliminarRenglon" data-accionEnExito="ACTUALIZAR" >
	<input type='hidden' id='identificador' name='identificador' value="<?php echo $identificador;?>" />
	<input type='hidden' id='idRenglon' name='idRenglon' value="<?php echo $_POST['elementos'];?>" />
		
	<p>Los siguientes <b>Renglos</b> serán eliminados: </p>
 	
	<?php
		for ($i = 0; $i < count ($elementosRenglo); $i++) {
			$renglo = pg_fetch_assoc($cpp->abrirRenglon($conexion, $elementosRenglo[$i]));

			echo'<fieldset>
						<legend>ID N° </label>' .$renglo['id_renglon'].'</legend>
							<div data-linea="1">
								<label>Nombre: </label>' .$renglo['nombre'].'
							</div>
							<div data-linea="2">
								<label>Ítem Presupuestario: </label>' .$renglo['codigo'].'
							</div>
							<input type="hidden" name="id[]" value="'.$renglo['id_renglon'].'"/>
					</fieldset>';
			
		}			
	?>
			
	<button id="detalle" type="submit" class="guardar" >Eliminar</button>
	
</form>
</body>

<script type="text/javascript">
var array_renglon= <?php echo json_encode($elementosRenglo); ?>;

	$(document).ready(function(){
		distribuirLineas();
		if(array_renglon == ''){
			$("#detalleItem").html('<div class="mensajeInicial">Seleccione uno o varios elementos para continuar.</div>');
		}		
	});
	
	$("#eliminarRenglon").submit(function(event){
		event.preventDefault();
		ejecutarJson($(this));
	});	
	
</script>
</html>