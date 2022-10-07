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

$refPres = 0;
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
  
<form id="enviarAprobacion" data-rutaAplicacion="reformaPresupuestaria" data-opcion="ejecutarReformaGF" data-accionEnExito="ACTUALIZAR" >
	<input type='hidden' id='identificador' name='identificador' value="<?php echo $identificador;?>" />
	
	<p>La <b>Reforma Presupuestaria</b> será registrada en el PAP y PAC con el <b>número de CUR</b>: </p>
 	
	<?php
		if($elementosPlanificacionAnual != 0){
			for ($i = 0; $i < count ($elementosPlanificacionAnual); $i++) {
				$planificacionAnual = pg_fetch_assoc($crp->abrirProgramacionAnualRevisionTemporal($conexion, $elementosPlanificacionAnual[$i]));
	
				$total = pg_fetch_result($crp->numeroPresupuestosYCostoTotalTemporal($conexion, $planificacionAnual['id_planificacion_anual']), 0, 'num_presupuestos');
				$presupuestosRevisados = pg_fetch_result($crp->numeroPresupuestosXEstadoTemporal($conexion, $planificacionAnual['id_planificacion_anual'], 'enviadoRevisorGF'), 0, 'num_presupuestos_revisados');
				$presupuestosAprobados = pg_fetch_result($crp->numeroPresupuestosXEstadoTemporal($conexion, $planificacionAnual['id_planificacion_anual'], 'aprobado'), 0, 'num_presupuestos_revisados');
				
				if($total == 0){
					$total = -1;
				}
				
				if(($planificacionAnual['estado'] == 'enviadoRevisorGF')){
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
					
					$refPres = 1;
				}else{
					echo '<fieldset>
							<legend>Código N° </label>' .$planificacionAnual['id_planificacion_anual'].'</legend>
								<div data-linea="1">
									<label>Debe finalizar la revisión de todos los elementos para realizar el envío</label>
								</div>
						</fieldset>';
					
					$refPres = 0;
				}
			}		
		}	
	?>
			
	<fieldset id="refPres">
		<legend>Ejecutar Reforma Presupuestaria</legend>
			<div data-linea="1">
				<label>Número de CUR: </label>
					<input type="text" id="numeroCur" name="numeroCur" /> 
			</div>
			<div data-linea="2">
				<label>Observaciones: </label>
					<input type="text" id="observaciones" name="observaciones" /> 
			</div>
			
			<!--input type="hidden" name="id[]" value="'.$planificacionAnual['id_planificacion_anual'].'"/-->
	</fieldset>
	
	<button id="detalle" type="submit" class="guardar" >Enviar</button>
	
</form>
</body>

<script type="text/javascript">
var array_planificacion_anual= <?php echo json_encode($elementosPlanificacionAnual); ?>;
var refPres= <?php echo json_encode($refPres); ?>;

	$(document).ready(function(){
		distribuirLineas();
		if(array_planificacion_anual == 0){
			$("#detalleItem").html('<div class="mensajeInicial">Seleccione uno o varios elementos para continuar.</div>');
		}	

		if(refPres == 0){
			$("#refPres").hide();
		}else{
			$("#refPres").show();
		}
	});
	
	$("#enviarAprobacion").submit(function(event){
		event.preventDefault();
		ejecutarJson($(this));
	});	
	
</script>
</html>