<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorReformaPresupuestaria.php';

$conexion = new Conexion();
$crp = new ControladorReformaPresupuestaria();
	
$identificador=$_SESSION['usuario'];

if($identificador==''){
	$usuario=0;
}else{
	$usuario=1;
	$idAreaFuncionario = $_SESSION['idArea'];
	$nombreProvinciaFuncionario = $_SESSION['nombreProvincia'];
}

$elementosImportacionPapPac = explode(",",$_POST['elementos']);

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>

<header>
	<h1>Confirmar Cierre de Ejercicio</h1>
</header>

<div id="estado"></div>          
  
<form id="confirmarCierreEjercicio" data-rutaAplicacion="reformaPresupuestaria" data-opcion="cerrarEjercicio" data-accionEnExito="ACTUALIZAR" >
	<input type='hidden' id='identificador' name='identificador' value="<?php echo $identificador;?>" />
	
	<p>La siguiente <b>Reforma Presupuestaria</b> será finalizada: </p>
 	
	<?php
		for ($i = 0; $i < count ($elementosImportacionPapPac); $i++) {
			$importacion = pg_fetch_assoc($crp->buscarImportacionPapPac($conexion, $elementosImportacionPapPac[$i]));

			echo'<fieldset>
					<legend>Ejercicio ' .$importacion['anio'].'</legend>
						<div data-linea="1">
							<label>Fecha de Importación: </label>' .$importacion['fecha_importacion'].'
						</div>
						<div data-linea="2">
							<label>Observaciones: </label>' .$importacion['observaciones'].'
						</div>
						<div data-linea="3">
							<label>Estado: </label>' .$importacion['estado'].'
						</div>
						<input type="hidden" name="id[]" value="'.$importacion['anio'].'"/>
				</fieldset>';	
			
		}		
	?>
			
	<button id="detalle" type="submit" class="guardar" >Enviar</button>
	
</form>
</body>

<script type="text/javascript">
var array_planificacion_anual= <?php echo json_encode($elementosImportacionPapPac); ?>;

	$(document).ready(function(){
		distribuirLineas();
		if(array_planificacion_anual == ''){
			$("#detalleItem").html('<div class="mensajeInicial">Seleccione uno o varios elementos para continuar.</div>');
		}		
	});
	
	$("#confirmarCierreEjercicio").submit(function(event){
		event.preventDefault();
		ejecutarJson($(this));
	});	
	
</script>
</html>