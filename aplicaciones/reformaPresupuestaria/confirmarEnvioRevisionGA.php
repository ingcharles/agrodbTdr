<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorReformaPresupuestaria.php';

$conexion = new Conexion();
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

if($_POST['elementos']==''){
	$elementosPlanificacionAnual = 0;
}else{
	$elementosPlanificacionAnual = explode(",",$_POST['elementos']);
}

//$elementosPlanificacionAnual = explode(",",$_POST['elementos']);
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
  
<form id="enviarAprobacion" data-rutaAplicacion="reformaPresupuestaria" data-opcion="enviarRevisionGA" data-accionEnExito="ACTUALIZAR" >
	<input type='hidden' id='identificador' name='identificador' value="<?php echo $identificador;?>" />
	
	<p>La siguiente <b>Reforma Presupuestaria</b> será enviada para aprobación en la Dirección General Administrativa Financiera: </p>
 	
	<?php
		if($elementosPlanificacionAnual != 0){
			for ($i = 0; $i < count ($elementosPlanificacionAnual); $i++) {
				$planificacionAnual = pg_fetch_assoc($crp->abrirProgramacionAnualRevisionTemporal($conexion, $elementosPlanificacionAnual[$i]));
	
				$total = pg_fetch_result($crp->numeroPresupuestosYCostoTotalTemporal($conexion, $planificacionAnual['id_planificacion_anual']), 0, 'num_presupuestos');
				$presupuestosRevisados = pg_fetch_result($crp->numeroPresupuestosRevisadosTemporal($conexion, $planificacionAnual['id_planificacion_anual'], 'revisadoDGPGE'), 0, 'num_presupuestos_revisados');
				$presupuestosAprobados = pg_fetch_result($crp->numeroPresupuestosXEstadoTemporal($conexion, $planificacionAnual['id_planificacion_anual'], 'aprobado'), 0, 'num_presupuestos_revisados');
				
				if($total == 0){
					$total = -1;
				}
				
				if(($planificacionAnual['estado'] == 'revisadoDGPGE') && (($total-$presupuestosAprobados) == $presupuestosRevisados)){
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
									<label>Presupuestos asignados: </label>' .$presupuestosRevisados.'
								</div>
								
								<input type="hidden" name="id[]" value="'.$planificacionAnual['id_planificacion_anual'].'"/>
						</fieldset>';
				}else if(($planificacionAnual['estado'] == 'revisadoDGPGE') && ($planificacionAnual['id_area_aprobador'] != null)){
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
		}
	?>
			
	<button id="detalle" type="submit" class="guardar" >Enviar</button>
	
</form>
</body>

<script type="text/javascript">
var array_planificacion_anual= <?php echo json_encode($elementosPlanificacionAnual); ?>;

	$(document).ready(function(){
		distribuirLineas();
		if(array_planificacion_anual == 0){
			$("#detalleItem").html('<div class="mensajeInicial">Seleccione uno o varios elementos para continuar.</div>');
		}		
	});
	
	$("#enviarAprobacion").submit(function(event){
		event.preventDefault();
		ejecutarJson($(this));
	});	
	
</script>
</html>