<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCapacitacion.php';

$conexion = new Conexion();
$ce = new ControladorCapacitacion();

$idRequerimiento=$_POST['id'];
$resCapacitacion = $ce->listarReplicacionUsuario($conexion,null,$idRequerimiento,null,null,1,20);

while($capacitacion=pg_fetch_assoc($resCapacitacion)){

	$calificacion = $ce->listarCalificacion($conexion,$capacitacion['id_funcionarios_replicados']);
	$resCalificacion = pg_fetch_assoc($calificacion);
?>
<header>
	<h1><?php echo $capacitacion['nombre_replicado']?></h1>
</header>

<input type="hidden" id="idRequerimiento" name="idRequerimiento" value="<?php echo $idRequerimiento;?>" /> 
<input type="hidden" id="idFuncionariosReplicados" name="idFuncionariosReplicados" value="<?php echo $capacitacion['id_funcionarios_replicados'];?>" /> 	
<div id="estado"></div>
<fieldset>
	<legend>Información de la réplica</legend>
			
	<div data-linea="1">
		<label>Nombre del evento</label>
		<input type="text" name="nombreEvento" disabled="disabled" id="nombreEvento" value="<?php echo $capacitacion['nombre_evento']?>" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$"/>	 
	</div>
	
	<div data-linea="2">
		<label>Nombre del capacitador</label>
		<input type="text" name="nombreCapacitador" disabled="disabled" id="nombreCapacitador" value="<?php echo $capacitacion['nombre_replicado']?>" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$"/>	 
	</div>
</fieldset>		
			
<fieldset>
	<legend>Evaluación del evento de réplica</legend>
	<div data-linea="1">
		<label>Por favor seleccione el número que corresponda, considerando 1 el menor cumplimiento o desempeño  y 5 el mayor cumplimiento o desempeño,  de acuerdo a su criterio</label> 
		
	</div>
	<div data-linea="2">
		<label>Conocimiento del tema</label> 
		<div>
		<input type="radio" name="conocimiento_tema_<?php echo $capacitacion['identificador_replicado']?>" value="1">1 
		<input type="radio" name="conocimiento_tema_<?php echo $capacitacion['identificador_replicado']?>" value="2">2
		<input type="radio" name="conocimiento_tema_<?php echo $capacitacion['identificador_replicado']?>" value="3">3 
		<input type="radio" name="conocimiento_tema_<?php echo $capacitacion['identificador_replicado']?>" value="4">4
		<input type="radio" name="conocimiento_tema_<?php echo $capacitacion['identificador_replicado']?>" value="5">5
		</div>
	</div>
	<div data-linea="3">
		<label>Respuestas a inquietudes</label>
		 <div>
		<input type="radio" name="respuesta_inquietudes_<?php echo $capacitacion['identificador_replicado']?>" value="1">1 
		<input type="radio" name="respuesta_inquietudes_<?php echo $capacitacion['identificador_replicado']?>" value="2">2
		<input type="radio" name="respuesta_inquietudes_<?php echo $capacitacion['identificador_replicado']?>" value="3">3 
		<input type="radio" name="respuesta_inquietudes_<?php echo $capacitacion['identificador_replicado']?>" value="4">4
		<input type="radio" name="respuesta_inquietudes_<?php echo $capacitacion['identificador_replicado']?>" value="5">5
		</div>
	</div>
	<div data-linea="4">
		<label>Manejo del grupo</label> 
		<div>
		<input type="radio" name="manejo_grupo_<?php echo $capacitacion['identificador_replicado']?>" value="1">1 
		<input type="radio" name="manejo_grupo_<?php echo $capacitacion['identificador_replicado']?>" value="2">2
		<input type="radio" name="manejo_grupo_<?php echo $capacitacion['identificador_replicado']?>" value="3">3 
		<input type="radio" name="manejo_grupo_<?php echo $capacitacion['identificador_replicado']?>" value="4">4
		<input type="radio" name="manejo_grupo_<?php echo $capacitacion['identificador_replicado']?>" value="5">5
		</div>
	</div>
	<div data-linea="5">
		<label>Cumplimiento de la agenda programada</label>
		<div> 
		<input type="radio" name="cumplimiento_agenda_<?php echo $capacitacion['identificador_replicado']?>" value="1">1 
		<input type="radio" name="cumplimiento_agenda_<?php echo $capacitacion['identificador_replicado']?>" value="2">2
		<input type="radio" name="cumplimiento_agenda_<?php echo $capacitacion['identificador_replicado']?>" value="3">3 
		<input type="radio" name="cumplimiento_agenda_<?php echo $capacitacion['identificador_replicado']?>" value="4">4
		<input type="radio" name="cumplimiento_agenda_<?php echo $capacitacion['identificador_replicado']?>" value="5">5
		</div>
	</div>
</fieldset>

<fieldset>
	<legend>Sobre conocimientos adquiridos</legend>
	<div data-linea="1">
		<label>Conteste a todas las preguntas seleccionando SI o NO según su opinión.</label> 
		
	</div>
	<div data-linea="2">
		<label>¿Están en relación a la función que desempeña?</label> 
		<input type="radio" name="conocimientos_relacionados_<?php echo $capacitacion['identificador_replicado']?>" value="SI">SI 
		<input type="radio" name="conocimientos_relacionados_<?php echo $capacitacion['identificador_replicado']?>" value="NO">NO
	</div>
	<div data-linea="3">
		<label>¿Los aplicará en su gestión institucional?</label> 
		<input type="radio" name="aplicara_institucion_<?php echo $capacitacion['identificador_replicado']?>" value="SI">SI 
		<input type="radio" name="aplicara_institucion_<?php echo $capacitacion['identificador_replicado']?>" value="NO">NO
	</div>
	
	<div data-linea="5">
		<label>¿Serán de utilidad para asesorar internamente en su Institución?</label> 
		<input type="radio" name="asesoria_interna_<?php echo $capacitacion['identificador_replicado']?>" value="SI">SI 
		<input type="radio" name="asesoria_interna_<?php echo $capacitacion['identificador_replicado']?>" value="NO">NO
	</div>
	
</fieldset>
	
	
<script type="text/javascript">
$(document).ready(function(){
	distribuirLineas();

	$('input[name=conocimiento_tema_'+<?php echo $capacitacion['identificador_replicado']?>+'][value="<?php echo $resCalificacion['conocimiento_tema'];?>"]').prop('checked', true);
	 $('input[name=respuesta_inquietudes_'+<?php echo $capacitacion['identificador_replicado']?>+'][value="<?php echo $resCalificacion['respuesta_inquietudes'];?>"]').prop('checked', true);
	 $('input[name=manejo_grupo_'+<?php echo $capacitacion['identificador_replicado']?>+'][value="<?php echo $resCalificacion['manejo_grupo'];?>"]').prop('checked', true);
	 $('input[name=cumplimiento_agenda_'+<?php echo $capacitacion['identificador_replicado']?>+'][value="<?php echo $resCalificacion['cumplimiento_agenda_programada'];?>"]').prop('checked', true);

	 $('input[name=conocimientos_relacionados_'+<?php echo $capacitacion['identificador_replicado']?>+'][value="<?php echo $resCalificacion['conocimientos_relacionados_funcion_desempeniada'];?>"]').prop('checked', true);
	 $('input[name=aplicara_institucion_'+<?php echo $capacitacion['identificador_replicado']?>+'][value="<?php echo $resCalificacion['conocimientos_aplicados_gestion_institucion'];?>"]').prop('checked', true);
	 $('input[name=asesoria_interna_'+<?php echo $capacitacion['identificador_replicado']?>+'][value="<?php echo $resCalificacion['conocimientos_utiles_asesorar_internamente'];?>"]').prop('checked', true);
	
}); 
</script>
<?php 
}
?>