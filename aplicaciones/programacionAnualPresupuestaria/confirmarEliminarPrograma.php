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

$elementosProgramas = explode(",",$_POST['elementos']);
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
  
<form id="eliminarPrograma" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="eliminarPrograma" data-accionEnExito="ACTUALIZAR" >
	<input type='hidden' id='identificador' name='identificador' value="<?php echo $identificador;?>" />
	<input type='hidden' id='idPrograma' name='idPrograma' value="<?php echo $_POST['elementos'];?>" />
		
	<p>Los siguientes <b>Programas, Proyectos y Actividades</b> serán eliminados: </p>
 	
	<?php
		for ($i = 0; $i < count ($elementosProgramas); $i++) {
			$programa = pg_fetch_assoc($cpp->abrirPrograma($conexion, $elementosProgramas[$i]));

			echo'<fieldset>
						<legend>ID N° </label>' .$programa['id_programa'].'</legend>
							<div data-linea="1">
								<label>Nombre: </label>' .$programa['nombre'].'
							</div>
							<div data-linea="2">
								<label>Codigo: </label>' .$programa['codigo'].'
							</div>
							<input type="hidden" name="id[]" value="'.$programa['id_programa'].'"/>
					</fieldset>';
			
		}			
	?>
			
	<button id="detalle" type="submit" class="guardar" >Eliminar</button>
	
</form>
</body>

<script type="text/javascript">
var array_programa= <?php echo json_encode($elementosProgramas); ?>;

	$(document).ready(function(){
		distribuirLineas();
		if(array_programa == ''){
			$("#detalleItem").html('<div class="mensajeInicial">Seleccione uno o varios elementos para continuar.</div>');
		}		
	});
	
	$("#eliminarPrograma").submit(function(event){
		event.preventDefault();
		ejecutarJson($(this));
	});	
	
</script>
</html>