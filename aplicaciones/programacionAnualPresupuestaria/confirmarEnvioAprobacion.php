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
  
<form id="enviarAprobacion" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="enviarAprobacion" data-accionEnExito="ACTUALIZAR" >
	<input type='hidden' id='identificador' name='identificador' value="<?php echo $identificador;?>" />
	
	<p>La siguiente <b>Planificación Anual</b> será enviada para aprobación en la Dirección General de Planificación y Gestión Estratégica: </p>
 	
	<?php
		for ($i = 0; $i < count ($elementosPlanificacionAnual); $i++) {
			$planificacionAnual = pg_fetch_assoc($cpp->abrirProgramacionAnualRevision($conexion, $elementosPlanificacionAnual[$i]));

			$total = pg_fetch_result($cpp->numeroPresupuestosYCostoTotal($conexion, $planificacionAnual['id_planificacion_anual']), 0, 'num_presupuestos');
			$presupuestosRevisados = pg_fetch_result($cpp->numeroPresupuestosRevisados($conexion, $planificacionAnual['id_planificacion_anual'], 'revisado'), 0, 'num_presupuestos_revisados');
			
			if($total == 0){
				$total = -1;
			}
			
			if(($planificacionAnual['estado'] == 'revisado') && ($total == $presupuestosRevisados)){
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
							<div data-linea="6">
								<label>Presupuestos asignados: </label>' .$total.'
							</div>
							
							<input type="hidden" name="id[]" value="'.$planificacionAnual['id_planificacion_anual'].'"/>
					</fieldset>';
			}else if(($planificacionAnual['estado'] == 'revisado') && ($planificacionAnual['id_area_aprobador'] != null)){
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
							<div data-linea="6">
								<label>Presupuestos asignados: </label>' .$total.'
							</div>
							
							<input type="hidden" name="id[]" value="'.$planificacionAnual['id_planificacion_anual'].'"/>
					</fieldset>';
			}else{
				echo '<fieldset>
						<legend>Código N° </label>' .$planificacionAnual['id_planificacion_anual'].'</legend>
							<div data-linea="1">
								<label>Debe finalizar la revisión de todos los elementos para realizar el envío</label>
							</div>
					</fieldset>';
			}
		}			
	?>
			
	<button id="detalle" type="submit" class="guardar" >Enviar</button>
	
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
	
	$("#enviarAprobacion").submit(function(event){
		event.preventDefault();
		ejecutarJson($(this));
	});	
	
</script>
</html>