<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCapacitacion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAreas.php';

$conexion = new Conexion();
$ce = new ControladorCapacitacion();
$cc = new ControladorCatalogos();
$ca = new ControladorAreas();

$identificador=$_SESSION['usuario'];
$idRequerimiento=$_POST['id'];

$resCapacitacion = $ce->obtenerRequerimientos($conexion,'','','',$idRequerimiento,'','','','');
$capacitacion = pg_fetch_assoc($resCapacitacion);

$cantones= $cc->listarSitiosLocalizacion($conexion,'CANTONES');
$res = $cc->listarLocalizacion($conexion, 'PAIS');

$area = $ca->obtenerAreasDireccionesTecnicas($conexion, "('Planta Central','Oficina Técnica')", "(3,4,1)");

$fecha= md5(time());

if($capacitacion['tipo_replica']=='noReplica'){
	$qParticipantes=$ce->obtenerFuncionarios($conexion,$idRequerimiento);
	$fila=pg_fetch_assoc($qParticipantes);
	$archivo=$fila['archivo_firmado'];
}

?>
<header>
	<h1>Réplica funcionarios</h1>
</header>

<form id="modificarRequerimiento" data-rutaAplicacion="capacitacion" data-opcion="actualizarGestionarReplicas" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" id="opcion" name="opcion" value="Actualizar" /> 
	<input type="hidden" id="idRequerimiento" name="idRequerimiento" value="<?php echo $idRequerimiento;?>" /> 
	<input type="hidden" id="identificadorFuncionarioReplica" name="identificadorFuncionarioReplica" />
	<div id="estado"></div>

	<fieldset>
		<legend>Información Empleado</legend>

		<div data-linea="1">
			<label>Tipo de evento</label> 
			<select name="tipoEvento" id="tipoEvento" disabled="disabled" class="desabilitado">
				<option value="">Seleccione....</option>
				<?php 							  	
					$tipoEvento = $cc->listarTiposCapacitacion($conexion);
					while($fila = pg_fetch_assoc($tipoEvento)){
						echo '<option value="' . $fila['codigo'] . '">' . $fila['nombre'].' </option>';
					}
				?>
			</select>
		</div>
		<div data-linea="1">
			<label>Tipo de certificado</label>
			<select name="tipoCertificado"	id="tipoCertificado" disabled="disabled" class="desabilitado">
				<option value="">Seleccione....</option>
				<option value="Asistencia">Asistencia</option>
				<option value="Aprobacion">Aprobación</option>
			</select>
		</div>
		<div data-linea="2">
			<label>Nombre del evento</label>
			<input type="text"	name="nombre_evento" id="nombre_evento" readonly="readonly" value="<?php echo $capacitacion['nombre_evento']?>" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$" />
		</div>
		<div data-linea="3">
			<label>Empresa capacitadora</label> <input type="text"
				id="empresaCapacitadora" name="empresaCapacitadora"
				class="desabilitado"
				value="<?php echo $capacitacion['empresa_capacitadora']?>"
				data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$" readonly="readonly" />
		</div>
		<div data-linea="4">
			<label>Fecha inicio</label> <input type="text" id="fechaInicio"
				name="fechaInicio" required="required" class="desabilitado"
				value="<?php echo date('d/m/Y',strtotime($capacitacion['fecha_inicio']));?>"
				readonly="readonly" />
		</div>
		<div data-linea="4">
			<label>Fecha fin</label> <input type="text" id="fechaFin" name="fechaFin" required="required" class="desabilitado" value="<?php echo date('d/m/Y',strtotime( $capacitacion['fecha_fin']));?>" readonly="readonly" />
		</div>
		<div data-linea="5">
			<label>Horas</label> 
			<input type="text" id="horas" name="horas" size="4" value="<?php echo $capacitacion['horas']?>" data-inputmask="'mask': '9[99]'" data-er="[0-9]{1,2}" title="99" readonly="readonly" />
		</div>
		<div data-linea="5">
			<label>Capacitación Interna</label> 
			<select	name="capacitacionInterna" id="capacitacionInterna" disabled="disabled" class="desabilitado">
				<option value="">Seleccione....</option>
				<option value="SI">SI</option>
				<option value="NO">NO</option>
			</select>
		</div>
		<div data-linea="6">
			<label>Es evento pagado?</label>
			<select name="eventoPagado"	id="eventoPagado" disabled="disabled" class="desabilitado">
				<option value="">Seleccione....</option>
				<option value="SI">Si</option>
				<option value="NO">No</option>
			</select>
		</div>
		<div data-linea="6">
			<label id="etiquetaCosto">Costo total</label>
			<input type="text" id="costoUnitario" name="costoUnitario" readonly="readonly" value="<?php echo $capacitacion['costo_unitario']?>" data-er="^[0-9]+(\.[0-9]{1,2})?$" />
		</div>
	</fieldset>
	<fieldset>
		<legend>Lugar del evento</legend>
		<div data-linea="1">
			<label>Localidad</label> 
			<select name="localizacion"	id="localizacion" disabled="disabled" class="desabilitado">
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
			<select id="provincia" name="provincia" disabled="disabled"	class="desabilitado">
				<option value="">Provincia....</option>
				<?php 	
					$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
					foreach ($provincias as $provincia){
						echo '<option value="' . $provincia['codigo'] . '">' . $provincia['nombre'] . '</option>';
					}
				?>
			</select>
		</div>
		<div data-linea="2">
			<label id="etiquetaCanton">Canton</label>
			<select id="canton"	name="canton" disabled="disabled" class="desabilitado"></select>
		</div>
		<div data-linea="3">
			<label id="etiquetaCiudad">Ciudad</label>
			<input type="text" id="ciudad" name="ciudad" value="<?php echo $capacitacion['ciudad']?>" readonly="readonly" class="desabilitado" />
		</div>
	</fieldset>
	<fieldset>
		<legend>Justificación del Evento</legend>
		<label>Descripción:</label>
		<div data-linea="1">
			<textarea rows="4" id="justificacion" readonly="readonly"name="justificacion" class="desabilitado" style="resize:none"><?php echo $capacitacion['justificacion']?></textarea>
		</div>
	</fieldset>
	<fieldset>
		<legend>Aprobación del requerimiento</legend>
		<div data-linea="1">
			<label>Estado del requerimiento</label>
			<input type="text"
				value="<?php
			switch ($capacitacion['estado_requerimiento']){
				case 0: $tituloEstado="Solicitud rechazada por el director"; break;
				case 1: $tituloEstado="Solicitud rechazada por talento humano"; break;
				case 6: $tituloEstado="Solicitud enviada para aprobación director"; break;
				case 7: $tituloEstado="Solicitud enviada para aprobacion talento humano"; break;
				case 8: $tituloEstado="Solicitud devuelta por talento humano"; break;
				case 11: $tituloEstado="Solicitud aprobada por el director"; break;
				case 12: $tituloEstado="Solicitud aprobada para certificación financiera"; break;
				case 13: $tituloEstado="Solicitud con certificación financiera "; break;
				case 14: $tituloEstado="Solicitud con replicación asignada"; break;
				case 15: $tituloEstado="Solicitud para notificación de replicación"; break;
				case 18: $tituloEstado="Solicitud finalizada"; break;
				case 19: $tituloEstado="Solicitud para notificación de replicación"; break;
			}
						
			echo $tituloEstado;?>"	readonly="readonly" class="desabilitado" />
		</div>
		
		<div data-linea="2">
			<label>Observación del director</label>
		</div>
		<div data-linea="3">
			<textarea rows="3" id="observacion" name="observacion"	readonly="readonly" class="desabilitado" style="resize:none"><?php echo $capacitacion['observacion']?></textarea>
		</div>
		<div data-linea="4">
			<label>Observación talento humano</label>
		</div>
		<div data-linea="5">			
			<textarea rows="3" id="observacionTH" name="observacionTH" readonly="readonly" style="resize:none"><?php echo $capacitacion['observacion_talento_humano']?></textarea>
		</div>
	</fieldset>

	<fieldset id="informacionFinanciera">
		<legend>Certificación financiera</legend>

		<div data-linea="1">
			<label>Nombre de la partida:</label>
			<input type="text"	id="nombreCertificacion" name="nombreCertificacion" value="<?php echo $capacitacion['nombre_certificacion']?>" readonly="readonly" />
		</div>
		<div data-linea="2">
			<label>fecha de la partida:</label>
			<input type="text"	id="fechaPartida" name="fechaPartida" value="<?php echo $capacitacion['fecha_partida']?>" readonly="readonly" />
		</div>
		<div data-linea="2">
			<label>No. Certificación</label> <input type="text"
				id="numero_certificacion" name="numero_certificacion"
				value="<?php echo $capacitacion['numero_certificacion']?>"
				readonly="readonly" />
		</div>
		<div data-linea="5">
			<label>Archivo certificación</label>
			<?php echo ($capacitacion['archivo']==''? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$capacitacion['archivo'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>')?>
		</div>
	</fieldset>
	<fieldset>
		<legend>Información para el informe de  Talento Humano</legend>
		
		<label>Objetivo del curso</label>
		<div data-linea="3">
			<textarea rows="3" id="objetivoCurso" name="objetivoCurso" readonly="readonly" style="resize:none"><?php echo $capacitacion['objetivo_curso']?></textarea>
		</div>
		
		<label>Justificación de Recursos Humanos:</label>
		<div data-linea="4">
			<textarea rows="3" id="justificacionTH" name="justificacionTH"	readonly="readonly" style="resize:none"><?php echo $capacitacion['justificacion_th']?></textarea>
		</div>
		<div data-linea="5">
			<label>Informe Generado</label>
			<?php echo ($capacitacion['ruta_informe']==''? '<span class="alerta">No se ha generado el informe</span>':'<a href='.$capacitacion['ruta_informe'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Informe de Capacitación Generado</a>')?>
		</div>
	</fieldset>

	<fieldset>
		<legend>Información de replicación</legend>
		<div data-linea="1">
			<label>Tipo replicación</label> 
			<select id="tipoReplica" name="tipoReplica" style="pointer-events: none;" >
				<option value="">Seleccione..</option>
				<option value="replica">Retroalimentación a compañeros - Réplica</option>
				<option value="procedimiento">Nuevo procedimiento sobre lo aprendido</option>
				<option value="manual">Nuevo manual / instructivo sobre lo aprendido</option>
				<option value="noReplica">Informe de no réplica</option>
			</select>
		</div>
		<div data-linea="2" id="archivoNoReplica">
		<label>Archivo de respaldo cargado</label> 
		<?php echo '<a href='.$archivo.' target="_blank" class="archivo_cargado" id="archivo_cargado">Informe de no réplica</a>'; ?>
		</div>
	</fieldset>
	<div id="fReplicacion">
	<?php

	$resFuncionarios=$ce->obtenerFuncionarios($conexion,$idRequerimiento);
	
	while($filaFuncionario = pg_fetch_assoc($resFuncionarios)){

		echo '<fieldset>
		<legend>Funcionario '.$filaFuncionario['apellido'].' '.$filaFuncionario['nombre'].'</legend>';
	
		echo '<div data-linea="2">
			<table>
			<thead>
			<tr>
			<th colspan="2">Funcionarios agregados para recibir replicación</th>
			<tr>
			</thead>
			<tbody id="ocupantes_'.$filaFuncionario['identificador'].'">';
		
			$qFuncionariosReplicados=$ce->obtenerFuncionariosReplicados($conexion,$idRequerimiento,$filaFuncionario['identificador']);
			while ($fila = pg_fetch_assoc($qFuncionariosReplicados)){
				echo $ce->imprimirLineaReplicado($fila['id_funcionarios_replicados'], $fila['apellido'].' '.$fila['nombre'],$filaFuncionario['identificador']);
			}
		echo '</tbody></table></fieldset>';
	}
	

	?>
	</div>
	<fieldset id="fProcedimiento">
		<legend>Indicaciones</legend>
			<textarea class="readonly" rows="3" id="descripcionReplica" name="descripcionReplica" style="resize:none"><?php echo $capacitacion['descripcion_replica']?></textarea>
	</fieldset>
	
	<fieldset id="fNoReplica">
		<legend>Archivo de respaldo:</legend>
		<div data-linea="4">
			<input type="file" class="archivo" name="informe" id="informe" accept="application/pdf"/>
			<input type="hidden" class="rutaArchivo" name="archivo" id="archivo" value="0"/>
			<div class="estadoCarga">
				En espera de archivo... (Tamaño máximo;	<?php echo ini_get('upload_max_filesize');?>B)</div>
				<button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/capacitacion/respaldoNoReplica" >Subir archivo</button>
				<input type="hidden" id="fecha" name="fecha" value="<?php echo $fecha;?>"/>
			</div>
		
	</fieldset>


</form>


<script type="text/javascript">
var array_canton= <?php echo json_encode($cantones); ?>;
var array_ocupante= <?php echo json_encode($ocupante); ?>;
var usuario = <?php echo json_encode($identificador);?>;
var estado= <?php echo json_encode($capacitacion['estado_requerimiento']); ?>;

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
	cargarValorDefecto("tipoReplica","<?php echo $capacitacion['tipo_replica']?>");
	 $('#nombre_Canton').val($("#canton option:selected").text());

	 $("#fReplicacion").hide();
	 $("#fProcedimiento").hide();
	 $("#fNoReplica").hide();

	 if($('#eventoPagado').val() == 'SI'){
		$('#informacionFinanciera').show();
	 }else{
		$('#informacionFinanciera').hide();
	}

	switch ($('#tipoReplica').val()){
		case 'replica':
			$("#fReplicacion").show();
			$("#fProcedimiento").hide();
			$("#fNoReplica").hide();
			$("#archivoNoReplica").hide();
			distribuirLineas();
		break;
		case 'procedimiento':
		case 'manual':
			$("#fReplicacion").hide();
			$("#fProcedimiento").show();
			$("#fNoReplica").hide();
			$("#archivoNoReplica").hide();
		break;
		case 'noReplica':
			$("#fReplicacion").hide();
			$("#fProcedimiento").hide();
			$("#fNoReplica").show();
			$("#archivoNoReplica").show();
		break;
	}		

	if(estado!=14){
		$('.menos').attr('disabled','disabled');
		$(".readonly").attr('readonly','readonly');
		$("#fNoReplica").hide();
	}		 

});
	
$('.listado_area').change(function(event){
	$("#modificarRequerimiento").attr('data-opcion', 'accionesCapacitacion');
	$("#modificarRequerimiento").attr('data-destino', 'resultadoFuncionario_'+$(this).attr("codigo"));
 	$("#opcionFuncionario").val('funcionarioReplica');
 	$("#identificadorFuncionarioReplica").val($(this).attr("codigo"));
 	abrir($("#modificarRequerimiento"), event, false); 				 
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

function chequearCampos(form){
	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if($("#objetivoCurso").val()==""){
		error = true;
		$("#objetivoCurso").addClass("alertaCombo");
	}
	if($("#justificacionTH").val()==""){
		error = true;
		$("#justificacionTH").addClass("alertaCombo");
	}

	if($("#tipoReplica").val()==""){
		error = true;
		$("#tipoReplica").addClass("alertaCombo");
	}

	switch ($("#tipoReplica").val()){
		case 'procedimiento':
		case 'manual':				
			if($("#descripcionReplica").val()==""){
				error = true;
				$("#descripcionReplica").addClass("alertaCombo");
			}			
		break;
		case 'noReplica':			
			if($("#archivo").val()==0){
				error = true;
				$("#informe").addClass("alertaCombo");
			}
		break;
	}		
		
	if (error){
		$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
	}else{
		ejecutarJson(form);
		if($('#estado').html()=='Los datos han sido ingresados satisfactoriamente')
			$('#_actualizar').click();
	}
}

$("#modificarRequerimiento").submit(function(event){
	event.preventDefault();
 	$('modificarRequerimiento.desabilitado').prop('disabled', false);		
 	$("#modificarRequerimiento").attr('data-opcion', 'actualizarGestionarReplicas');
	$("#modificarRequerimiento").removeAttr('data-destino');
 	chequearCampos(this);
});

$('button.subirArchivo').click(function (event) {
    var boton = $(this);
    var archivo = boton.parent().find(".archivo");
    var rutaArchivo = boton.parent().find(".rutaArchivo");
    var extension = archivo.val().split('.');
    var estado = boton.parent().find(".estadoCarga");
       
    if (extension[extension.length - 1].toUpperCase() == 'PDF') {
        subirArchivo(
            archivo
            , usuario+'_'+$("#fechaPartida").val().replace(/[_\W]+/g, "-")
            , boton.attr("data-rutaCarga")
            , rutaArchivo
            , new carga(estado, archivo, boton)
        );
        
    }else {
        estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
        archivo.val("");
    }  
});
</script>