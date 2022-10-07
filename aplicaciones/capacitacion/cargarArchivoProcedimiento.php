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

$idRequerimiento=$_POST['id'];
$identificador=$_SESSION['usuario'];

$resCapacitacion = $ce->obtenerRequerimientos($conexion,'','','',$idRequerimiento,'','','','');
$capacitacion = pg_fetch_assoc($resCapacitacion);
$cantones= $cc->listarSitiosLocalizacion($conexion,'CANTONES');
$res = $cc->listarLocalizacion($conexion, 'PAIS');
$fecha= md5(time());
$archivoReplicacion = pg_fetch_assoc($ce->ObtenerFuncionarioReplicacionArchivo($conexion, $idRequerimiento, $identificador));

?>
<header>
	<h1>Visualizar informe de capacitación</h1>
</header>

<form id="cargarArchivoProcedimiento" data-rutaAplicacion="capacitacion" data-opcion="guardarArchivoProcedimiento" data-accionEnExito="ACTUALIZAR">

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
						$tipoEvento = $cc->listarTiposCapacitacion($conexion);
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
			<label>Horas</label> 
			<input type="text" id="horas" name="horas" size="4" value="<?php echo $capacitacion['horas']?>" data-inputmask="'mask': '9[99]'" data-er="[0-9]{1,2}" title="99" readonly="readonly" />
		</div>
		
		<div data-linea="5">
			<label>Capacitación Interna</label> 
			<select name="capacitacionInterna" id="capacitacionInterna" disabled="disabled" class="desabilitado">
				<option value="">Seleccione....</option>
				<option value="SI">SI</option>
				<option value="NO">NO</option>
			</select>
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
			<label id="etiquetaCanton">Canton</label> 
				<select id="canton" name="canton" disabled="disabled" class="desabilitado"></select>
		</div>
		
		<div data-linea="3">
			<label id="etiquetaCiudad">Ciudad</label> 
				<input type="text" id="ciudad" name="ciudad" value="<?php echo $capacitacion['ciudad']?>" readonly="readonly" class="desabilitado" />
		</div>
		
	</fieldset>
	
	<fieldset>
		<legend>Justificación del evento</legend>
		<label>Descripción:</label>
		
		<div data-linea="1">
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
				case 0: $tituloEstado="Solicitud rechazada por el director"; break;
				case 1: $tituloEstado="Solicitud rechazada por talento humano"; break;
				case 6: $tituloEstado="Solicitud ingresada"; break;
				case 8: $tituloEstado="Solicitud devuelta por talento humano"; break;
				case 11: $tituloEstado="Solicitud aprobada por el director"; break;
				case 12: $tituloEstado="Solicitud aprobada para certificación financiera"; break;
				case 13: $tituloEstado="Solicitud con certificación financiera"; break;
				case 14: $tituloEstado="Solicitud por asignar replicación"; break;
				case 19: $tituloEstado="Solicitud para generar procedimiento/manual"; break;
				case 20: $tituloEstado="Solicitud con procedimiento/manual"; break;
			}
						
			echo $tituloEstado;?>" readonly="readonly" class="desabilitado" />
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
			<label>Nombre de la partida</label> 
				<input type="text" 	id="nombre_certificacion" name="nombre_certificacion" value="<?php echo $capacitacion['nombre_certificacion']?>" readonly="readonly" />
		</div>
		
		<div data-linea="2">
			<label>fecha de la partida</label> 
				<input type="text" id="fecha_partida" name="fecha_partida" 	value="<?php echo $capacitacion['fecha_partida']?>" readonly="readonly" />
		</div>
		
		<div data-linea="2">
			<label>No. Certificación</label> 
				<input type="text" id="numero_certificacion" name="numero_certificacion" value="<?php echo $capacitacion['numero_certificacion']?>" readonly="readonly" />
		</div>
		
		<div data-linea="3">
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
			<?php echo ($capacitacion['ruta_informe']==''? '<span class="alerta">No se ha generado el informe</span>':'<a href='.$capacitacion['ruta_informe'].' target="_blank" class="archivo_cargado">Informe Generado</a>')?>
		</div>
	</fieldset>
		
	<fieldset>
		<legend>Archivo de procedimiento/manual:</legend>
		
		<?php 
			if($archivoReplicacion['estado_replica']=='rechazado'){
				echo '<div data-linea="1">
						<label>Estado: </label>'.$archivoReplicacion['estado_replica'].'
					</div>
					<div data-linea="2">
						<label>Observación: </label>'.$archivoReplicacion['observacion_replica'].'
					</div>';			
			}
		?>
		
		
		<div data-linea="3">
			<label>Modo de presentación del procedimiento/manual: </label><?php echo $capacitacion['modo_replica'];?>
		</div>
		
		<div data-linea="4">
			<label>Descripción de la réplica</label>
		</div>		
		
		<div data-linea="5">
			<textarea rows="3" readonly="readonly" style="resize:none"><?php echo $capacitacion['descripcion_replica'];?></textarea>	
		</div>
		
		<div data-linea="6">
			<?php echo ($archivoReplicacion['archivo_replica']!=''? '<label>Archivo de procedimiento/manual </label><a href='.$archivoReplicacion['archivo_replica'].' target="_blank" class="archivo_cargado">Archivo Cargado</a>':'')?>
		</div>		
		
		<div data-linea="7" id="cargado">
			<input type="file" class="archivo" name="informe" id="informe" accept="application/pdf"/>
			<input type="hidden" class="rutaArchivo" name="archivo" id="archivo" value="0"/>
			<div class="estadoCarga">En espera de archivo... (Tamaño máximo; <?php echo ini_get('upload_max_filesize');?>B)</div>
			<button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/capacitacion/archivosProcedimiento" >Subir archivo</button>
			<input type="hidden" id="fecha" name="fecha" value="<?php echo $fecha;?>"/>
		</div>
		
	</fieldset>

	<p>
		<button id="actualizar" type="submit" class="guardar">Guardar</button>
	</p>
</form>


<script type="text/javascript">
var array_canton= <?php echo json_encode($cantones); ?>;
var array_ocupante= <?php echo json_encode($ocupante); ?>;
var usuario = <?php echo json_encode($identificador);?>;
var archivoReplica = <?php echo json_encode($archivoReplicacion['archivo_replica']);?>;

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

	if($('#eventoPagado').val() == 'SI'){
		$('#informacionFinanciera').show();
	}else{
		$('#informacionFinanciera').hide();
	}

	if(archivoReplica!=null){
		$('#cargado').hide();
		$('.guardar').hide();
	}
	
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

function chequearCampos(form){
	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;
	
	if($("#archivo").val()==0){
		error = true;
		$("#informe").addClass("alertaCombo");
	}
	
	if (error){
		$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
	}else{
		ejecutarJson(form);
	}
}

$("#cargarArchivoProcedimiento").submit(function(event){
	event.preventDefault();	
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
            , usuario+'_'+$("#fecha_partida").val().replace(/[_\W]+/g, "-")
            , boton.attr("data-rutaCarga")
            , rutaArchivo
            , new carga(estado, archivo, boton)
        );
        
    } else {
        estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
        archivo.val("");
    }
    
    
});

</script>