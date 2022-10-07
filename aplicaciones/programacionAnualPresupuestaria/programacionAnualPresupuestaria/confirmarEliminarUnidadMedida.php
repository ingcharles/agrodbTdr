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

$elementosUnidadesMedida = explode(",",$_POST['elementos']);
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
  
<form id="eliminarUnidadesMedida" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="eliminarUnidadMedida" data-accionEnExito="ACTUALIZAR" >
	<input type='hidden' id='identificador' name='identificador' value="<?php echo $identificador;?>" />
	<input type='hidden' id='idUnidadMedida' name='idUnidadMedida' value="<?php echo $_POST['elementos'];?>" />
		
	<p>Las siguientes <b>Unidades de Medida</b> serán eliminadas: </p>
 	
	<?php
		for ($i = 0; $i < count ($elementosUnidadesMedida); $i++) {
			$unidadesMedida = pg_fetch_assoc($cpp->abrirUnidadMedida($conexion, $elementosUnidadesMedida[$i]));

			echo'<fieldset>
						<legend>ID N° </label>' .$unidadesMedida['id_unidad_medida'].'</legend>
							<div data-linea="1">
								<label>Nombre: </label>' .$unidadesMedida['nombre'].'
							</div>
							<div data-linea="2">
								<label>Unidad: </label>' .$unidadesMedida['codigo'].'
							</div>
							<input type="hidden" name="id[]" value="'.$unidadesMedida['id_unidad_medida'].'"/>
					</fieldset>';
			
		}			
	?>
			
	<button id="detalle" type="submit" class="guardar" >Eliminar</button>
	
</form>
</body>

<script type="text/javascript">
var array_unidades_medida= <?php echo json_encode($elementosUnidadesMedida); ?>;

	$(document).ready(function(){
		distribuirLineas();
		if(array_unidades_medida == ''){
			$("#detalleItem").html('<div class="mensajeInicial">Seleccione uno o varios elementos para continuar.</div>');
		}		
	});
	
	$("#eliminarUnidadesMedida").submit(function(event){
		event.preventDefault();
		ejecutarJson($(this));
	});	
	
</script>
</html>