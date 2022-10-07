<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAreas.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorProgramacionPresupuestaria.php';

$conexion = new Conexion();
$ca = new ControladorAreas();
$cc = new ControladorCatalogos();
$cu = new ControladorUsuarios();
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

$elementosPlanificacionAnual = explode(",",$_POST['elementos']);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>

<header>
	<h1>Confirmar Envío</h1>
</header>

<div id="estado"></div>          
  
<form id="eliminarPlanificacionAnual" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="eliminarPlanificacionAnual" data-accionEnExito="ACTUALIZAR" >
	<input type='hidden' id='identificador' name='identificador' value="<?php echo $identificador;?>" />
	<input type='hidden' id='idPlanificacionAnual' name='idPlanificacionAnual' value="<?php echo $_POST['elementos'];?>" />
		
	<p>La siguiente <b>Planificación Anual y sus Presupuestos</b> serán eliminados: </p>
 	
	<?php
		for ($i = 0; $i < count ($elementosPlanificacionAnual); $i++) {
			$planificacionAnual = pg_fetch_assoc($cpp->abrirProgramacionAnual($conexion, $elementosPlanificacionAnual[$i], $identificador));

			if(($planificacionAnual['estado'] != 'aprobado')){
					echo'<fieldset>
							<legend>Código N° </label>' .$planificacionAnual['id_planificacion_anual'].'</legend>
								<div data-linea="1">
									<label>Gestión/Unidad: </label>' .$planificacionAnual['gestion'].'
								</div>
								<div data-linea="2">
									<label>Tipo: </label>' .$planificacionAnual['tipo'].'
								</div>
								<div data-linea="3">
									<label>Proceso/Proyecto: </label>'. $planificacionAnual['proceso_proyecto'].'
								</div>
								<div data-linea="4">
									<label>Componente: </label>' .$planificacionAnual['componente'].'
								</div>
								<div data-linea="5">
									<label>Actividad: </label>' .$planificacionAnual['actividad'].'
								</div>
								<input type="hidden" name="id[]" value="'.$planificacionAnual['id_planificacion_anual'].'"/>
						</fieldset>';
			}
			
		}			
	?>
			
	<button id="detalle" type="submit" class="guardar" >Eliminar</button>
	
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
	
	$("#eliminarPlanificacionAnual").submit(function(event){
		event.preventDefault();
		ejecutarJson($(this));
	});	
	
</script>
</html>