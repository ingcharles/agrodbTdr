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
$qArea=pg_fetch_all($area);

$fecha= md5(time());
?>
<header>
	<h1>Visualizar informe de capacitación</h1>
</header>
	
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
		<select name="tipoCertificado"	id="tipoCertificado" disabled="disabled" class="desabilitado">
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
		<input type="text" id="empresaCapacitadora" name="empresaCapacitadora" class="desabilitado"	value="<?php echo $capacitacion['empresa_capacitadora']?>" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$" readonly="readonly" />
	</div>
	<div data-linea="4">
		<label>Fecha inicio</label>
		<input type="text" id="fechaInicio"	name="fechaInicio" required="required" class="desabilitado" value="<?php echo date('d/m/Y',strtotime($capacitacion['fecha_inicio']));?>" readonly="readonly" />
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
		<label>Capacitación interna</label>
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
		<select	id="provincia" name="provincia" disabled="disabled" class="desabilitado">
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
		<label id="etiquetaCanton">Cantón</label>
		<select id="canton"	name="canton" disabled="disabled" class="desabilitado">	</select>
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
		<input type="text"	value="<?php
		switch ($capacitacion['estado_requerimiento']){
			case 0: $tituloEstado="Solicitud rechazada por el director"; break;
			case 1: $tituloEstado="Solicitud rechazada por talento humano"; break;
			case 6: $tituloEstado="Solicitud ingresada"; break;
			case 8: $tituloEstado="Solicitud devuelta por talento humano"; break;
			case 11: $tituloEstado="Solicitud aprobada por el director"; break;
			case 12: $tituloEstado="Solicitud aprobada para certificación financiera"; break;
			case 13: $tituloEstado="Solicitud con certificación financiera"; break;
			case 14: $tituloEstado="Solicitud por asignar replicación"; break;
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
		<label>Nombre de la partida</label>
		<input type="text" id="nombreCertificacion" name="nombreCertificacion" value="<?php echo $capacitacion['nombre_certificacion']?>"
			readonly="readonly" />
	</div>
	<div data-linea="2">
		<label>fecha de la partida</label> 
		<input type="text" id="fechaPartida" name="fechaPartida" value="<?php echo $capacitacion['fecha_partida']?>" readonly="readonly" />
	</div>
	<div data-linea="2">
		<label>No. certificación</label>
		<input type="text" id="numeroCertificacion" name="numeroCertificacion"	value="<?php echo $capacitacion['numero_certificacion']?>"	readonly="readonly" />
	</div>
	<div data-linea="3">
		<label>Archivo certificación</label>
		<?php echo ($capacitacion['archivo']==''? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$capacitacion['archivo'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>')?>
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
		<label>Informe generado</label>
		<?php echo ($capacitacion['ruta_informe']==''? '<span class="alerta">No se ha generado el informe</span>':'<a href='.$capacitacion['ruta_informe'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Informe de Capacitación Generado</a>')?>
	</div>
</fieldset>

<fieldset>
	<legend>Tipo de replicación</legend>

	<div data-linea="1">
		<label>Tipo </label> 
		<select id="tipoReplica" name="tipoReplica">
			<option value="">Seleccione..</option>
			<option value="replica">Retroalimentación a compañeros - Réplica</option>
			<option value="procedimiento">Nuevo procedimiento sobre lo aprendido</option>
			<option value="manual">Nuevo manual / instructivo sobre lo aprendido</option>
			<option value="noReplica">Informe de no réplica</option>
		</select>
	</div>
</fieldset>
	
<div id="fReplicacion">

<?php 
	$resFuncionarios=$ce->obtenerFuncionarios($conexion,$idRequerimiento);

	while($filaFuncionario = pg_fetch_assoc($resFuncionarios)){
		echo '<fieldset>
				<legend>Funcionario '.$filaFuncionario['apellido'].' '.$filaFuncionario['nombre'].'</legend>
				<form  id="nuevoDetalleParticipantes_'.$filaFuncionario['identificador'].'" data-rutaAplicacion="capacitacion" data-opcion="guardarNuevoReplicado" >
					<input type="hidden" id="idRequerimiento" name="idRequerimiento" value="'.$idRequerimiento.'" /> 
					<input type="hidden" id="opcionFuncionario_'.$filaFuncionario['identificador'].'" name="opcionFuncionario" />
				 	<input type="hidden" id="identificadorReplicador_'.$filaFuncionario['identificador'].'" name="identificadorReplicador" />
					<input type="hidden" id="identificadorReplicado_'.$filaFuncionario['identificador'].'" name="identificadorReplicado" />
					<input type="hidden" id="nombreReplicado_'.$filaFuncionario['identificador'].'" name="nombreReplicado" >
					<input type="hidden" id="categoriaArea_'.$filaFuncionario['identificador'].'" name="categoriaArea" >
	
					<div data-linea="1">
						<label id="r_'.$filaFuncionario['identificador'].'">Nombres: '.$filaFuncionario['apellido'].' '.$filaFuncionario['nombre'].'</label>
					</div>';
		
					echo '<div data-linea="2">
					<label>Área pertenece</label>
					<select id="area_'.$filaFuncionario['identificador'].'" name="area" class="listadoArea" codigo="'.$filaFuncionario['identificador'].'">
					<option value="" selected="selected">Área....</option>';
					foreach ($qArea as $fila){
						echo '<option data-categoria="' . $fila['categoria_area'] . '" value="' . $fila['id_area'] . '">' . $fila['nombre'] . '</option>';
					}
	 				
					echo '</select></div>
					<div id="resultadoFuncionario_'.$filaFuncionario['identificador'].'" data-linea="3"></div>
					<button type="submit" onclick="agregarReplicante(\'#ocupantes_'.$filaFuncionario['identificador'].'\')" class="mas">Agregar funcionario</button>
				</form>
				<table style="width:100%">
				<thead>
				<tr>
				<th colspan="2">Funcionarios agregados para recibir replicas</th>
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

<form id="modificarRequerimiento" data-rutaAplicacion="capacitacion" data-opcion="gestionarReplicas" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" id="idRequerimiento" name="idRequerimiento" value="<?php echo $idRequerimiento;?>" /> 
	<input type="hidden" id="estadoAprobacion" name="estadoAprobacion" value="14" />
	<input type="hidden" id="tipoReplicacion" name="tipoReplicacion" value="0" /> 
	
	<fieldset id="fProcedimiento">
		<legend>Indicaciones</legend>
		<div data-linea="1">
			<label>Modo de replicación:</label>
			<select id="modoReplica" name="modoReplica">
				<option value="">Seleccione..</option>
				<option value="individual">Individual</option>
				<option value="grupal">Grupal</option>
			</select>
		</div>
		<div data-linea="2">
		<label>Observacion para los funcionario</label>
		</div>
		<div data-linea="3">
			<textarea rows="3" id="descripcionReplica" name="descripcionReplica" style="resize:none"></textarea>
		</div>
	</fieldset>

	<fieldset id="fNoReplica">
		<legend>Archivo de respaldo:</legend>
		<div data-linea="4">
			<input type="file" class="archivo" name="informe" id="informe" accept="application/pdf"/>
			<input type="hidden" class="rutaArchivo" name="archivo" id="archivo" value="0"/>
			<div class="estadoCarga">En espera de archivo... (Tamaño máximo;	<?php echo ini_get('upload_max_filesize');?>B)</div>
				<button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/capacitacion/respaldoNoReplica" >Subir archivo</button>
				<input type="hidden" id="fecha" name="fecha" value="<?php echo $fecha;?>"/>
			</div>
	</fieldset>

	<p>
		<button id="actualizar" type="submit" class="guardar">Guardar</button>
	</p>
</form>

<script type="text/javascript">
var array_canton= <?php echo json_encode($cantones); ?>;
var usuario = <?php echo json_encode($identificador);?>;

$(document).ready(function(){
 	construirValidador();
	distribuirLineas();
	cargarValorDefecto("tipoEvento","<?php echo $capacitacion['tipo_evento']?>");
	 $('#nombreTipoEvento').val($("#tipoEvento option:selected").text());
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
	 $('#nombreCanton').val($("#canton option:selected").text());

	 $("#fReplicacion").hide();
	 $("#fProcedimiento").hide();
	 $("#fNoReplica").hide();

	 if($('#eventoPagado').val() == 'SI'){
		$('#informacionFinanciera').show();
	 }else{
		$('#informacionFinanciera').hide();
	}

	fecha=new Date();    
	day=("00" + fecha.getDate()).slice (-2); 
	month=("00" + (fecha.getMonth()+1)).slice (-2); 
	year=fecha.getFullYear();
	fechaActual = year+""+month+""+day;

	var fechaUno = $('#fechaFin').val().split("/");
	var fechaFinCapacitacion= fechaUno[2]+''+fechaUno[1] +''+fechaUno[0];

	if(fechaFinCapacitacion < fechaActual){
		$('button').removeAttr('disabled');
	}else{
		$('button').attr('disabled','disabled');
		$("#estado").html("No se permite la asignación de replicación porque la fecha de finalización es superior o igual a la actual.").addClass('alerta');
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

			if($("#modoReplica").val()==""){
				error = true;
				$("#modoReplica").addClass("alertaCombo");
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
	$("#modificarRequerimiento").attr('data-opcion', 'gestionarReplicas');
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
        
    } else {
        estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
        archivo.val("");
    }
});


$('#tipoReplica').change(function(){
	if($("#tipoReplicacion").val()!=""){
		$("#tipoReplicacion").val($("#tipoReplica option:selected").val());
		switch ($("#tipoReplica").val()){
			case 'replica':
				$("#fReplicacion").show();
				$("#fProcedimiento").hide();
				$("#fNoReplica").hide();
				distribuirLineas();
			break;
	
			case 'procedimiento':
			case 'manual':
				$("#fReplicacion").hide();
				$("#fProcedimiento").show();
				$("#fNoReplica").hide();
			break;
	
			case 'noReplica':
				$("#fReplicacion").hide();
				$("#fProcedimiento").hide();
				$("#fNoReplica").show();
			break;
		}
	}				 
});


$('.listadoArea').change(function(event){
	 event.stopImmediatePropagation();
	 $("#categoriaArea_"+$(this).attr("codigo")).val($("option:selected",this).attr("data-categoria"));
	 $("#identificadorReplicador_"+$(this).attr("codigo")).val($(this).attr("codigo"));
	 $("#nuevoDetalleParticipantes_"+$(this).attr("codigo")).attr('data-opcion', 'accionesCapacitacion');
	 $("#nuevoDetalleParticipantes_"+$(this).attr("codigo")).attr('data-destino', 'resultadoFuncionario_'+$(this).attr("codigo"));
	 $("#opcionFuncionario_"+$(this).attr("codigo")).val('funcionarioReplica');
	 event.preventDefault();
	 abrir($("#nuevoDetalleParticipantes_"+$(this).attr("codigo")), event, false); 				 
});

function agregarReplicante(codigoEtiqueta){		
	event.stopImmediatePropagation();
	var identificador=codigoEtiqueta.split('_')[1];
	$('#nuevoDetalleParticipantes_'+identificador).attr('data-opcion','guardarNuevoReplicado');   
	acciones("#nuevoDetalleParticipantes_"+identificador,"#ocupantes_"+identificador);
}

function quitarReplicante(identificador){	
	event.stopImmediatePropagation();
	$('#nuevoDetalleParticipantes_'+identificador).attr('data-opcion','eliminarReplicado');   
	acciones("#nuevoDetalleParticipantes_"+identificador,"#ocupantes_"+identificador);
}
</script>
