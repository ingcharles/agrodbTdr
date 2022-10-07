<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCapacitacion.php';

$conexion = new Conexion();
$ce = new ControladorCapacitacion();

$id_requerimiento=$_POST['id'];
$resCapacitacion = $ce->listarReplicacionUsuario($conexion,$titulo_capacitacion,$id_requerimiento,$_POST['nombre_replicador'],$_SESSION['usuario'],$estado_inicio,$estado_fin);
$capacitacion = pg_fetch_assoc($resCapacitacion);



?>
<header>
	<h1>Calificar réplica</h1>
</header>

<form id="calificarReplica" data-rutaAplicacion="capacitacion" data-opcion="gestionCalificacionReplica" >
	<input type="hidden" id="idRequerimiento" name="idRequerimiento" value="<?php echo $id_requerimiento;?>" /> 
	<input type="hidden" id="idFuncionarioReplicado" name="idFuncionarioReplicado" value="<?php echo $capacitacion['id_funcionarios_replicados'];?>" /> 	
	<div id="estado"></div>
	<fieldset>
		<legend>Información de la réplica</legend>
				
		<div data-linea="1">
			<label>Nombre del evento</label>
			<input type="text" name="nombre_evento" disabled="disabled" id="nombre_evento" value="<?php echo $capacitacion['nombre_evento']?>" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$"/>	 
		</div>
		
		<div data-linea="2">
			<label>Nombre del capacitador</label>
			<input type="text" name="nombre_capacitador" disabled="disabled" id="nombre_capacitador" value="<?php echo $capacitacion['nombre_replicante']?>" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$"/>	 
		</div>
	</fieldset>		
				
	<fieldset>
		<legend>Evaluación del evento de réplica</legend>
		<div data-linea="1">
			<label>Por favor seleccione el número que corresponda, considerando 1 el menor cumplimiento o desempeño  y 5 el mayor cumplimiento o desempeño,  de acuerdo a su criterio</label> 
			
		</div>
		<div data-linea="2">
			<label class="conocimientoTema">Conocimiento del tema</label> 
			<div>
			<input type="radio" name="conocimientoTema" value="1" class="conocimientoTema">1 
			<input type="radio" name="conocimientoTema" value="2" class="conocimientoTema">2
			<input type="radio" name="conocimientoTema" value="3" class="conocimientoTema">3 
			<input type="radio" name="conocimientoTema" value="4" class="conocimientoTema">4
			<input type="radio" name="conocimientoTema" value="5" class="conocimientoTema">5
			</div>
		</div>
		<div data-linea="3">
			<label class="respuestaInquietudes">Respuestas a inquietudes</label>
			 <div>
			<input type="radio" name="respuestaInquietudes" value="1" class="respuestaInquietudes">1 
			<input type="radio" name="respuestaInquietudes" value="2" class="respuestaInquietudes">2
			<input type="radio" name="respuestaInquietudes" value="3" class="respuestaInquietudes">3 
			<input type="radio" name="respuestaInquietudes" value="4" class="respuestaInquietudes">4
			<input type="radio" name="respuestaInquietudes" value="5" class="respuestaInquietudes">5
			</div>
		</div>
		<div data-linea="4">
			<label class="manejoGrupo">Manejo del grupo</label> 
			<div>
			<input type="radio" name="manejoGrupo" value="1" class="manejoGrupo">1 
			<input type="radio" name="manejoGrupo" value="2" class="manejoGrupo">2
			<input type="radio" name="manejoGrupo" value="3" class="manejoGrupo">3 
			<input type="radio" name="manejoGrupo" value="4" class="manejoGrupo">4
			<input type="radio" name="manejoGrupo" value="5" class="manejoGrupo">5
			</div>
		</div>
		<div data-linea="5">
			<label class="cumplimientoAgenda">Cumplimiento de la agenda programada</label>
			<div> 
			<input type="radio" name="cumplimientoAgenda" value="1" class="cumplimientoAgenda">1 
			<input type="radio" name="cumplimientoAgenda" value="2" class="cumplimientoAgenda">2
			<input type="radio" name="cumplimientoAgenda" value="3" class="cumplimientoAgenda">3 
			<input type="radio" name="cumplimientoAgenda" value="4" class="cumplimientoAgenda">4
			<input type="radio" name="cumplimientoAgenda" value="5" class="cumplimientoAgenda">5
			</div>
		</div>
		
	</fieldset>
	
	<fieldset>
		<legend>Sobre conocimientos adquiridos</legend>
		<div data-linea="1">
			<label>Conteste a todas las preguntas seleccionando SI o NO según su opinión.</label> 
			
		</div>
		<div data-linea="2">
			<label class="conocimientosRelacionados">¿Están en relación a la función que desempeña?</label> 
			<input type="radio" name="conocimientosRelacionados" value="SI" class="conocimientosRelacionados">SI 
			<input type="radio" name="conocimientosRelacionados" value="NO" class="conocimientosRelacionados">NO
		</div>
		<div data-linea="3">
			<label class="aplicaraInstitucion">¿Los aplicará en su gestión institucional?</label> 
			<input type="radio" name="aplicaraInstitucion" value="SI" class="aplicaraInstitucion">SI 
			<input type="radio" name="aplicaraInstitucion" value="NO" class="aplicaraInstitucion">NO
		</div>
		
		<div data-linea="5">
			<label class="asesoriaInterna">¿Serán de utilidad para asesorar internamente en su Institución?</label> 
			<input type="radio" name="asesoriaInterna" value="SI" class="asesoriaInterna">SI 
			<input type="radio" name="asesoriaInterna" value="NO" class="asesoriaInterna">NO
		</div>
		
	</fieldset>
		
	<p>
		<button id="actualizar" type="submit" class="guardar">Guardar</button>
	</p>
	
</form>

<script type="text/javascript">

$(document).ready(function(){
 	construirValidador();
	distribuirLineas();
}); 

$("#calificarReplica").submit(function(event){
	event.preventDefault();
 	$('calificarReplica.desabilitado').prop('disabled', false);
 	chequearCampos(this);	 
});

function chequearCampos(form){
	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;
	if(!$("#calificarReplica input[name='conocimientoTema']:radio").is(':checked')) {
		error = true;
		$(".conocimientoTema").addClass("alertaCombo");
	}
	if(!$("#calificarReplica input[name='respuestaInquietudes']:radio").is(':checked')) {
		error = true;
		$(".respuestaInquietudes").addClass("alertaCombo");
	}
	if(!$("#calificarReplica input[name='manejoGrupo']:radio").is(':checked')) {
		error = true;
		$(".manejoGrupo").addClass("alertaCombo");
	}
	if(!$("#calificarReplica input[name='cumplimientoAgenda']:radio").is(':checked')) {
		error = true;
		$(".cumplimientoAgenda").addClass("alertaCombo");
	}
	if(!$("#calificarReplica input[name='conocimientosRelacionados']:radio").is(':checked')) {
		error = true;
		$(".conocimientosRelacionados").addClass("alertaCombo");
	}
	if(!$("#calificarReplica input[name='aplicaraInstitucion']:radio").is(':checked')) {
		error = true;
		$(".aplicaraInstitucion").addClass("alertaCombo");
	}
	if(!$("#calificarReplica input[name='asesoriaInterna']:radio").is(':checked')) {
		error = true;
		$(".asesoriaInterna").addClass("alertaCombo");
	}
	if (error){
		$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
	}else{
		ejecutarJson(form);
		if($('#estado').html()=='Los datos han sido ingresados satisfactoriamente'){
			$('#_actualizar').click();
		}
	} 
}
	
</script>
