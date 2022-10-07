<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCapacitacion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAreas.php';

$conexion = new Conexion();
$ce = new ControladorCapacitacion();
$cat = new ControladorCatalogos();
$cc = new ControladorCatalogos();
$ca = new ControladorAreas();

$idRequerimiento=$_POST['id'];
$resCapacitacion = $ce->obtenerRequerimientos($conexion,'','','',$idRequerimiento,'','','','');
$capacitacion = pg_fetch_assoc($resCapacitacion);

$identificador=$_SESSION['usuario'];

$cantones= $cc->listarSitiosLocalizacion($conexion,'CANTONES');
$res = $cc->listarLocalizacion($conexion, 'PAIS');

$archivoReplicacionCapacitado = $ce->obtenerArchivoReplicaRevision($conexion, $idRequerimiento, 'cargado');


?>
<header>
	<h1>Visualizar informe de capacitación</h1>
</header>

<form id="cargarArchivoProcedimiento" data-rutaAplicacion="capacitacion" data-opcion="guardarArchivoProcedimiento">

	<input type="hidden" id="idRequerimiento" name="idRequerimiento" value="<?php echo $idRequerimiento;?>" /> 
	<input type="hidden" id="identificador" name="identificador" value="<?php echo $identificador;?>" /> 

	<div id="estado"></div>

	<fieldset>
		<legend>Información empleado</legend>

		<div data-linea="1">
			<label>Tipo de evento</label> 
			<select name="tipoEvento" id="tipoEvento" disabled="disabled" class="desabilitado">
				<option value="">Seleccione....</option>
					<?php 							  	
						$tipoEvento = $cat->listarTiposCapacitacion($conexion);
							while($fila = pg_fetch_assoc($tipoEvento)){
								echo '<option value="' . $fila['codigo'] . '">' . $fila['nombre'].' </option>';
							}
					?>
			</select>
		</div>
		
		<div data-linea="1">
			<label>Tipo de certificado</label> 
			<select name="tipoCertificado" id="tipoCertificado" disabled="disabled" class="desabilitado">
				<option value="">Seleccione....</option>
				<option value="Asistencia">Asistencia</option>
				<option value="Aprobacion">Aprobación</option>
			</select>
		</div>
		
		<div data-linea="2">
			<label>Nombre del evento</label> 
			<input type="text" name="nombre_evento" id="nombre_evento" readonly="readonly" value="<?php echo $capacitacion['nombre_evento']?>" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$" />
		</div>
		

		<div data-linea="3">
			<label>Empresa capacitadora</label> 
			<input type="text" id="empresaCapacitadora" name="empresaCapacitadora" class="desabilitado" value="<?php echo $capacitacion['empresa_capacitadora']?>" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$" readonly="readonly" />
		</div>
		
		<div data-linea="4">
			<label>Fecha inicio</label> 
			<input type="text" id="fechaInicio" name="fechaInicio" required="required" class="desabilitado" value="<?php echo date('d/m/Y',strtotime($capacitacion['fecha_inicio']));?>" readonly="readonly" />
		</div>
		
		<div data-linea="4">
			<label>Fecha fin</label> 
			<input type="text" id="fechaFin" name="fechaFin" required="required" class="desabilitado" value="<?php echo date('d/m/Y',strtotime( $capacitacion['fecha_fin']));?>" readonly="readonly" />
		</div>
		
		
		<div data-linea="5">
			<label>Capacitación interna</label> 
			<select name="capacitacionInterna" id="capacitacionInterna" disabled="disabled" class="desabilitado">
				<option value="">Seleccione....</option>
				<option value="SI">SI</option>
				<option value="NO">NO</option>
			</select>
		</div>
		
		<div data-linea="5">
			<label>Horas</label> 
			<input type="text" id="horas" name="horas" size="4" value="<?php echo $capacitacion['horas']?>" data-inputmask="'mask': '9[99]'" data-er="[0-9]{1,2}" title="99" readonly="readonly" />
		</div>
		
		<div data-linea="6">
			<label>Es evento pagado?</label> 
				<select name="eventoPagado" id="eventoPagado" disabled="disabled" class="desabilitado">
					<option value="">Seleccione....</option>
					<option value="SI">Si</option>
					<option value="NO">No</option>
				</select>
		</div>
		
		<div data-linea="6">
			<label id="etiquetaCosto">Costo total</label> 
			<input type="text" 	id="costoUnitario" name="costoUnitario" readonly="readonly" value="<?php echo $capacitacion['costo_unitario']?>" data-er="^[0-9]+(\.[0-9]{1,2})?$" />
		</div>
		
	</fieldset>
	
	<fieldset>
		<legend>Lugar del evento</legend>
		
		<div data-linea="1">
			<label>Localidad</label> 
			<select name="localizacion" id="localizacion" disabled="disabled" class="desabilitado">
				<option value="">Seleccione....</option>
				<option value="Nacional">Nacional</option>
				<option value="Internacional">Internacional</option>
			</select>
		</div>
		
		<div data-linea="1">
			<label>País</label>	
			<select name="pais" id="pais" disabled="disabled" class="desabilitado">
				<option value="">Seleccione....</option>
					<?php
						while($pais = pg_fetch_assoc($res)){
							echo '<option value="'.$pais['nombre'].'">'.$pais['nombre'].'</option>';
						}
					?>
			</select>
		</div>
		
		<div data-linea="2">
			<label id="etiquetaProvincia">Provincia</label> 
			<select id="provincia" name="provincia" disabled="disabled" class="desabilitado"> 
				<option value="">Seleccione....</option>
					<?php 	
						$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
							foreach ($provincias as $provincia){
								echo '<option value="' . $provincia['codigo'] . '">' . $provincia['nombre'] . '</option>';
							}
					?>
			</select>
		</div>
		
		<div data-linea="2">
			<label id="etiquetaCanton">Cantón</label> 
			<select id="canton" name="canton" disabled="disabled" class="desabilitado"></select>
		</div>
		
		<div data-linea="3">
			<label id="etiquetaCiudad">Ciudad</label> 
			<input type="text" id="ciudad" name="ciudad" value="<?php echo $capacitacion['ciudad']?>" readonly="readonly" class="desabilitado" />
		</div>
		
	</fieldset>
	
	<fieldset>
		<legend>Justificación del evento</legend>
		<div data-linea="1">
			<label>Descripción</label>
		</div>
		<div data-linea="2">
			<textarea rows="4" id="justificacion" readonly="readonly" name="justificacion" class="desabilitado" style="resize:none"><?php echo $capacitacion['justificacion']?></textarea>
		</div>
		
	</fieldset>
	
	<fieldset>
		<legend>Aprobación del requerimiento</legend>
		<div data-linea="1">
			<label>Estado del requerimiento</label>
			<input type="text"
				value="<?php
			switch ($capacitacion['estado_requerimiento']){
				case 0: $titulo_estado="Rechazado jefe inmediato"; break;
				case 1: $titulo_estado="Rechazado talento humano"; break;
				case 6: $titulo_estado="Ingresado"; break;
				case 7: $titulo_estado="Modificado"; break;
				case 8: $titulo_estado="Devuelto por talento humano"; break;
				case 11: $titulo_estado="Aprobado jefe inmediato"; break;
				case 12: $titulo_estado="Aceptado para certificar"; break;
				case 13: $titulo_estado="Con certificación financiera "; break;
				case 14: $titulo_estado="Con informe favorable"; break;
				case 19: $titulo_estado="Cargar archivo de procedimiento o manual"; break;
				case 20: $titulo_estado="Archivo cargado con procedimiento o manual"; break;
			}
						
			echo $titulo_estado;?>" readonly="readonly" class="desabilitado" />
		</div>
		
		<label>Observación del director</label>
		
		<div data-linea="2">
			<textarea rows="3" id="observacion" name="observacion"	readonly="readonly" class="desabilitado" style="resize:none"><?php echo $capacitacion['observacion']?></textarea>
		</div>
		
		<label>Observación talento humano</label>
		<div data-linea="4">			
			<textarea rows="3" id="observacionTH" name="observacionTH" readonly="readonly" style="resize:none"><?php echo $capacitacion['observacion_talento_humano']?></textarea>
		</div>
	</fieldset>

	<fieldset>
		<legend>Certificación financiera</legend>

		<div data-linea="1">
			<label>Nombre de la partida</label> 
				<input type="text" 	id="nombre_certificacion" name="nombre_certificacion" value="<?php echo $capacitacion['nombre_certificacion']?>" readonly="readonly" />
		</div>
		
		<div data-linea="2">
			<label>Fecha de la partida</label> 
				<input type="text" id="fecha_partida" name="fecha_partida" 	value="<?php echo $capacitacion['fecha_partida']?>" readonly="readonly" />
		</div>
		
		<div data-linea="2">
			<label>No. certificación</label> 
				<input type="text" id="numero_certificacion" name="numero_certificacion" value="<?php echo $capacitacion['numero_certificacion']?>" readonly="readonly" />
		</div>
		
		<div data-linea="5">
			<label>Archivo certificación</label>
			<?php echo ($capacitacion['archivo']==''? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$capacitacion['archivo'].' target="_blank" class="archivo_cargado">Archivo Cargado</a>')?>
		</div>
	
	</fieldset>
	
	<fieldset>
		<legend>Información para el informe de  talento humano</legend>
		
		<div data-linea="1">
			<label>Objetivo del curso</label>
		</div>
	
		<div data-linea="2">
			<textarea rows="3" id="objetivoCurso" name="objetivoCurso" readonly="readonly" style="resize:none"><?php echo $capacitacion['objetivo_curso']?></textarea>
		</div>
		
		<div data-linea="3">
			<label>Justificación de recursos humanos</label>
		</div>
		
		<div data-linea="4">
			<textarea rows="3" id="justificacionTH" name="justificacionTH"	readonly="readonly" style="resize:none"><?php echo $capacitacion['justificacion_th']?></textarea>
		</div>
		<div data-linea="5">
			<label>Informe talento humano</label>
			<?php echo ($capacitacion['ruta_informe']==''? '<span class="alerta">No se ha generado el informe</span>':'<a href='.$capacitacion['ruta_informe'].' target="_blank" class="archivo_cargado">Informe Cargado</a>')?>
		</div>
	</fieldset>
		
</form>

<?php 
$contador=1;
	while ($fila = pg_fetch_assoc($archivoReplicacionCapacitado)){

		if($fila['estado_replica']!='cargado'){
			$estado=$fila['estado_replica'];
		}
		echo '<fieldset>
				<legend>'.$fila['nombre_completo'].'</legend>
					<div data-linea="1">
						<label>Archivo procedimiento/manual </label>';
					echo ($fila['archivo_replica']==''? '<span class="alerta">No se ha cargado ningún archivo</span>':'<a href='.$fila['archivo_replica'].' target="_blank" class="archivo_cargado">Archivo Cargado</a>');
					echo '</div>
				
				<form class="actualizarReplicaFuncionario" data-rutaAplicacion="capacitacion" data-opcion="actualizarEstadoProcedimiento">
					<input type="hidden" name="idRequerimiento" value="'.$fila['id_requerimiento'].'" />
					<input type="hidden" name="identificadorReplicado" value="'.$fila['identificador_replicado'].'" />
					<div data-linea="2">
						<label>Estado</label>
						<select name="estadoReplicacion">
							<option value="" >Seleccione...</option>
							<option value="aprobado" data-seleccion="'.$estado.'">Aprobado</option>
							<option value="rechazado" data-seleccion="'.$estado.'">Rechazado</option>
						</select>
					</div>
			
					<div data-linea="3">
						<label>Observación</label>
					<div>
					<div data-linea="4">
						<textarea rows="3" class= "observacionReplica" name="observacionReplica" style="resize:none"></textarea>
					</div>
					<div data-linea="5" style="text-align:center">
						<button type="submit" class="guardar">Guardar</button>
					</div>
				</form>
		
		</fieldset>';
					$contador++;
	}
	
?>

<script type="text/javascript">
var array_canton= <?php echo json_encode($cantones); ?>;
var array_ocupante= <?php echo json_encode($ocupante); ?>;
var usuario = <?php echo json_encode($identificador);?>;

$(document).ready(function(){
 	construirValidador();
	distribuirLineas();
	cargarValorDefecto("tipoEvento","<?php echo $capacitacion['tipo_evento']?>");
	 $('#nombre_tipoEvento').val($("#tipoEvento option:selected").text());
	cargarValorDefecto("tipoCertificado","<?php echo $capacitacion['tipo_certificado']?>");
	cargarValorDefecto("eventoPagado","<?php echo $capacitacion['evento_pagado']?>");
	cargarValorDefecto("localizacion","<?php echo $capacitacion['localizacion']?>");
	cargarValorDefecto("pais","<?php echo $capacitacion['pais']?>");
	localizacion();
	eventoPagado();
	cargarValorDefecto("provincia","<?php echo $capacitacion['provincia']?>");
	llenarCanton();
	cargarValorDefecto("canton","<?php echo $capacitacion['canton']?>");
	cargarValorDefecto("capacitacionInterna","<?php echo $capacitacion['capacitacion_interna']?>");
	 $('#nombre_Canton').val($("#canton option:selected").text());

	var contador=1;

	 $('select[name="estadoReplicacion"]').each(function(){
		$(this).find('option').each(function(){
			if($(this).val()==$(this).attr('data-seleccion'))
				$(this).prop("selected","selected");
		});		
		contador++;
	});
		
 });


$("#provincia").change(function(){
	llenarCanton();
});

function llenarCanton() {
	$('#nombreProvincia').val($("#provincia option:selected").text());
 	scanton = '<option value="">Canton...</option>';
    for(var i=0;i<array_canton.length;i++){
	    if ($("#provincia").val()==array_canton[i]['padre']){
	    	scanton += '<option value="'+array_canton[i]['codigo']+'">'+array_canton[i]['nombre']+'</option>';
		    }
   		}
    $('#canton').html(scanton);
}

$("#eventoPagado").change(function(){
	eventoPagado();
});

function eventoPagado(){
		$("#costoUnitario").removeAttr("disabled");
		if($("#eventoPagado option:selected").val()=="NO"){
			$("#etiquetaCosto").hide();
			$("#costoUnitario").hide();
			}else{
				$("#etiquetaCosto").show();
				$("#costoUnitario").show();
		}
}

$("#localizacion").change(function(){
	localizacion();
});

function localizacion(){
	if($("#localizacion option:selected").val()=="Nacional"){
		$("#etiquetaProvincia").show();
		$("#provincia").show();
		$("#etiquetaCanton").show();
		$("#canton").show();
		$("#pais option[value=Ecuador]").attr('selected','selected');
		$("#pais").attr("disabled","disabled");
		$("#etiquetaCiudad").hide();
		$("#ciudad").hide();
	}else{
		$("#etiquetaProvincia").hide();
		$("#provincia").hide();
		$("#etiquetaCanton").hide();
		$("#canton").hide();
		$("#etiquetaCiudad").show();
		$("#ciudad").show();
	}
}

$(".actualizarReplicaFuncionario").submit(function(event){		
	 event.preventDefault();	

	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if($(this).find('select').val()==''){
		error = true;
		$($(this).find('select')).addClass("alertaCombo");
	}
	if($(this).find('select').val()=='rechazado'){
		if($(this).find('.observacionReplica').val()==""){
			error = true;
			$(this).find('.observacionReplica').addClass("alertaCombo");
		}		
	}
	
	if (error){
		$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
	}else{
		ejecutarJson($(this));
		if($('#estado').html()=='Los datos han sido ingresados satisfactoriamente'){
			$('#_actualizar').click();
		}
	}		
 });

</script>