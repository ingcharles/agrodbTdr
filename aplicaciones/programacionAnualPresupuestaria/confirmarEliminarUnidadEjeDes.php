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

$elementosUnidadEjeDes = explode(",",$_POST['elementos']);
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
  
<form id="eliminarUnidadEjeDes" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="eliminarUnidadEjeDes" data-accionEnExito="ACTUALIZAR" >
	<input type='hidden' id='identificador' name='identificador' value="<?php echo $identificador;?>" />
	<input type='hidden' id='idUnidadEjeDes' name='idUnidadEjeDes' value="<?php echo $_POST['elementos'];?>" />
		
	<p>Las siguientes <b>Unidades Ejecutoras y Desconcentradas</b> serán eliminadas: </p>
 	
	<?php
		for ($i = 0; $i < count ($elementosUnidadEjeDes); $i++) {
			$unidadEjeDes = pg_fetch_assoc($cpp->abrirUnidadEjeDes($conexion, $elementosUnidadEjeDes[$i]));

			echo'<fieldset>
						<legend>ID N° </label>' .$unidadEjeDes['id_unidad_ejedes'].'</legend>
							<div data-linea="1">
								<label>Nombre: </label>' .$unidadEjeDes['nombre'].'
							</div>
							<div data-linea="2">
								<label>Código: </label>' .$unidadEjeDes['codigo'].'
							</div>
							<div data-linea="3">
								<label>Tipo: </label>'. $unidadEjeDes['tipo'].'
							</div>
							<div data-linea="4">
								<label>Geográfico: </label>' .$unidadEjeDes['codigo_geografico'].'
							</div>
							<input type="hidden" name="id[]" value="'.$unidadEjeDes['id_unidad_ejedes'].'"/>
					</fieldset>';
			
		}			
	?>
			
	<button id="detalle" type="submit" class="guardar" >Eliminar</button>
	
</form>
</body>

<script type="text/javascript">
var array_unidad_ejedes= <?php echo json_encode($elementosUnidadEjeDes); ?>;

	$(document).ready(function(){
		distribuirLineas();
		if(array_unidad_ejedes == ''){
			$("#detalleItem").html('<div class="mensajeInicial">Seleccione uno o varios elementos para continuar.</div>');
		}		
	});
	
	$("#eliminarUnidadEjeDes").submit(function(event){
		event.preventDefault();
		ejecutarJson($(this));
	});	
	
</script>
</html>