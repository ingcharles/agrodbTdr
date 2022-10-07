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

?>
<header>
	<h1>Aprobación capacitación </h1>
</header>

<div id="estado"></div>
<fieldset>
	<legend>Información empleado</legend>
	
	<div data-linea="1">
		<label>Tipo de evento</label> 
		<select name="tipoEvento" id="tipoEvento" disabled="disabled" class="desabilitado">
			<option value="" >Seleccione....</option>							
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
			<option value="" >Seleccione....</option>
			<option value="Asistencia">Asistencia</option>
			<option value="Aprobacion">Aprobación</option>
	   </select>
	</div>
	<div data-linea="2">
		<label>Nombre del evento</label>
		<input type="text" name="nombreEvento" id="nombreEvento" readonly="readonly" value="<?php echo $capacitacion['nombre_evento']?>" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$"/>	 
	</div>
	<div data-linea="3">
		<label>Empresa capacitadora</label> 
			<input type="text" id="empresaCapacitadora" name="empresaCapacitadora" class="desabilitado"  value="<?php echo $capacitacion['empresa_capacitadora']?>" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$" readonly="readonly"/>
	</div>
	<div data-linea="4">
		<label>Fecha inicio</label> 
			<input type="text" id="fechaInicio" name="fechaInicio" required="required" class="desabilitado" value="<?php echo date('d/m/Y',strtotime($capacitacion['fecha_inicio']));?>" readonly="readonly"  />
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
		<select name="capacitacionInterna" id="capacitacionInterna" disabled="disabled">
			<option value="" >Seleccione....</option>
			<option value="SI">SI</option>
			<option value="NO">NO</option>
	   </select>
	</div>		
	<div data-linea="6">
		<label>Es evento pagado?</label> 
		<select name="eventoPagado" id="eventoPagado" disabled="disabled" class="desabilitado">
			<option value="" >Seleccione....</option>
			<option value="SI">Si</option>
			<option value="NO">No</option>
	   </select>
	</div>
	<div data-linea="6">
		<label id="etiquetaCosto">Costo total</label> 
		<input type="text" id="costo" name="costo" readonly="readonly"  value="<?php echo $capacitacion['costo_unitario']."+IVA"?>" data-er="^[0-9]+(\.[0-9]{1,2})?$" />
	</div>
</fieldset>	
<fieldset>
	<legend>Lugar del evento</legend>
	<div data-linea="1">
		<label>Localidad</label> 
		<select name="localizacion" id="localizacion" disabled="disabled" class="desabilitado">
			<option value="" >Seleccione....</option>
			<option value="Nacional">Nacional</option>
			<option value="Internacional">Internacional</option>
	   </select>
	</div>
	<div data-linea="1">
		<label>País</label> 
		<select name="pais" id="pais" disabled="disabled" class="desabilitado">
			<option value="" >Seleccione....</option>
			<?php
				while($pais = pg_fetch_assoc($res)){
					echo '<option value="'.$pais['nombre'].'">'.$pais['nombre'].'</option>';
				}
			?>
		</select>
	</div>
	<div data-linea="2">
		<label id="etiquetaProvincia">Provincia</label>
		<select id="provincia" name="provincia" disabled="disabled" class="desabilitado" >
			<option value="">Seleccione....</option>
				<?php 	
					$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
					foreach ($provincias as $provincia){
						echo '<option value="' . $provincia['codigo'] . '">' . $provincia['nombre'] . '</option>';
					}
				?>
		</select> 
	</div>				
	<div data-linea="4">
		<label id="etiquetaCanton">Canton</label>
			<select id="canton" name="canton" disabled="disabled" class="desabilitado" >
			</select>
	</div>
	<div data-linea="5">
		<label id="etiquetaCiudad">Ciudad</label> 
		<input type="text" id="ciudad" name="ciudad" value="<?php echo $capacitacion['ciudad']?>" readonly="readonly" class="desabilitado"/>
	</div>
</fieldset>	
<fieldset>
	<legend>Justificación del evento</legend>
	<div data-linea="1">
		<label>Descripción</label> 
	</div>
	<div data-linea="2">
		<textarea rows="4" id="justificacion" readonly="readonly" name="justificacion" style="resize:none"><?php echo $capacitacion['justificacion']?></textarea>
	</div>
</fieldset>
<fieldset>
	<legend>Aprobación del requerimiento</legend>
	<div data-linea="1">
		<label>Estado del requerimiento</label> 
		<input type="text" value="<?php
		switch ($capacitacion['estado_requerimiento']){
			case 0: $tituloEstado="Solicitud rechazada por el director"; break;
			case 1: $tituloEstado="Solicitud rechazada por talento humano"; break;
			case 6: $tituloEstado="Solicitud ingresada"; break;
			case 8: $tituloEstado="Solicitud devuelta por talento humano"; break;
			case 11: $tituloEstado="Solicitud aprobada por el director"; break;
			case 12: $tituloEstado="Solicitud aprobada para certificación financiera"; break;
			case 13: $tituloEstado="Solicitud con certificación financiera"; break;
		}
					
		echo $tituloEstado;?>" readonly="readonly" class="desabilitado" />	
	</div>	
	<div data-linea="2" >
		<label>Observación del director</label>
	</div>
	<div data-linea="3" id="opcion_ocupante">
		<textarea rows="3" id="observacion" name="observacion" readonly="readonly" style="resize:none"><?php echo $capacitacion['observacion']?></textarea>
	</div>
	<div data-linea="4">
		<label>Observación talento humano</label>
	</div>
	<div data-linea="5">
		<textarea rows="3" id="observacionTH" name="observacionTH" readonly="readonly" style="resize:none"><?php echo $capacitacion['observacion_talento_humano']?></textarea>
	</div>
		
	
	</fieldset>		
<form id="nuevoDetalleParticipantes" data-rutaAplicacion="capacitacion" data-opcion="guardarNuevoParticipante" >
	<input type="hidden" id="opcionFuncionario" name="opcionFuncionario"/> 
	<input type="hidden" id="nombreFuncionario" name="nombreFuncionario"/> 
	<input type="hidden" id="idRequerimiento" name="idRequerimiento" value="<?php echo $idRequerimiento;?>" />
	
	<fieldset id="seccionSeleccionFuncionarios">
		<legend>Selección de funcionarios</legend>
		<div data-linea="1">
			<label>Área pertenece</label> 
			<select class="inhabilitar" id="area" name="area" <?php echo 'required';?> >
				<option value="" selected="selected">Área....</option>
				<?php 
					while($fila = pg_fetch_assoc($area)){
						echo '<option value="' . $fila['id_area'] . '">' . $fila['nombre'] . '</option>';
					}
				?>
			</select>
		</div>
	
		<div id="resultadoFuncionario" data-linea="2"></div>
			
		<button type="submit" id="agregarOcupante" class="mas inhabilitar" >Agregar funcionario
		</button>
	</fieldset>
</form>	

<fieldset>
	<legend>Funcionarios agregados</legend>
	<table id="tabla">
		<tbody id="ocupantes">
		<?php
			$resFuncionarios=$ce->obtenerFuncionarios($conexion,$idRequerimiento);
			while ($fila = pg_fetch_assoc($resFuncionarios)){
				echo $ce->imprimirLineaAsistenteCapacitacion($fila['id_participantes'], $fila['apellido'].' '.$fila['nombre']);
			}
		?>
		</tbody>
	</table>	
</fieldset>	
	
<form id="modificarRequerimiento" data-rutaAplicacion="capacitacion" data-opcion="gestionRequerimiento" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" id="opcionFuncionario" name="opcionFuncionario"/> 
	<input type="hidden" id="opcion" name="opcion" value="actualizarEstadoFinanciero" />  
	<input type="hidden" id="idRequerimiento" name="idRequerimiento" value="<?php echo $idRequerimiento;?>" />
	<input type="hidden" id="archivo" name="archivo" value="0" />  
	<input type="hidden" id="costoUnitario" name="costoUnitario" value="<?php echo $capacitacion['costo_unitario']?>" />
	<input type="hidden" id="categoriaArea" name="categoriaArea" /> 
	
	<fieldset>
		<legend>Certificación financiera</legend>
		<div data-linea="1">
			<label>Estado</label> 
			<select class="inhabilitar" name="estadoAprobacion" id="estadoAprobacion" class="desabilitado">
				<option value="">Seleccione....</option>
				<option value="13">Certificado</option>
				<option value="0">Rechazado</option>
				<option value="8">Devolver</option>
		   </select>
		</div>	
		<div data-linea="1">
			<label>No. certificación</label> 
			<input class="readonly" type="text" id="numeroCertificacion" name="numeroCertificacion" value="<?php echo $capacitacion['numero_certificacion']?>"/>
		</div>
		<div data-linea="2">
			<label>Nombre de la partida</label> 
			<input class="readonly"  type="text" id="nombreCertificacion" name="nombreCertificacion" value="<?php echo $capacitacion['nombre_certificacion']?>"/>
		</div>
		<div data-linea="3">
			<label>Fecha de la partida</label> 
			<input class="readonly" type="text" id="fechaPartida" name="fechaPartida" readonly value="<?php echo $capacitacion['fecha_partida']?>"/>
		</div>
		<div data-linea="4" id="cargado">
			<label>Archivo certificación</label> 
	
		<a href="<?php echo $capacitacion['archivo']?>" target="_blank">Archivo Cargado</a>
		</div>
		<div data-linea="5" id="cargar">
			<label>Archivo Capacitación</label> 
			<input type="file" class="archivo inhabilitar" name="informe" accept="application/pdf" />
			<input type="hidden" class="rutaArchivo" name="archivo" value="0"/>
			<div class="estadoCarga">En espera de archivo... (Tamaño máximo;
			<?php echo ini_get('upload_max_filesize');?>B)</div>
			<button type="button" class="subirArchivo adjunto inhabilitar" data-rutaCarga="aplicaciones/capacitacion/generados" >Subir archivo</button>
			<input type="hidden" id="fecha" name="fecha" value="'.$fecha.'"/>
		</div>
		 <div data-linea="6" style="text-align:center">
			<button type="submit" class="guardar inhabilitar">Guardar</button>
		</div>
	</fieldset>
</form>
<script type="text/javascript">
var array_canton= <?php echo json_encode($cantones); ?>;
var usuario = <?php echo json_encode($identificador);?>;
var estado= <?php echo json_encode($capacitacion['estado_requerimiento']); ?>;

$(document).ready(function(){
 	construirValidador();
	distribuirLineas();
	cargarValorDefecto("tipoEvento","<?php echo $capacitacion['tipo_evento']?>");
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
	$("#actualizar").attr("disabled","disabled");
	cargarValorDefecto("estadoAprobacion","<?php echo $capacitacion['estado_requerimiento']?>");
	
	if(estado!=12){
		$("#cargar").hide();
		$(".inhabilitar").attr('disabled','disabled');
		$(".readonly").attr('readonly','readonly');
		$(".guardar").hide();
	}else{
		$("#cargado").hide();
	}
	if(estado!=6 && estado!=7){
		$(".menos").attr('disabled','disabled');
		$(".mas").attr('disabled','disabled');
		$("#seccionSeleccionFuncionarios").hide();
	}
 });

$( "#fechaPartida" ).datepicker({
    changeMonth: true,
    changeYear: true,
    yearRange: '-10:+1'
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
            , usuario+'_'+$("#fechaPartida").val().replace(/[_\W]+/g, "-")+'_'+$("#numeroCertificacion").val().replace(/ /g,'')
            , boton.attr("data-rutaCarga")
            , rutaArchivo
            , new carga(estado, archivo, boton)
        );
        
    } else {
        estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
        archivo.val("");
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

	if($("#nombreCertificacion").val()==""){
		error = true;
		$("#nombreCertificacion").addClass("alertaCombo");
		$("#estado").html("El campo nombre de la partida es requerido.").addClass('alerta');
	}
	
	if($("#fechaPartida").val()==""){
		error = true;
		$("#fechaPartida").addClass("alertaCombo");
		$("#estado").html("El campo fecha de partida es requerido.").addClass('alerta');
	}

	if($("#fechaPartida").val() > $("#fechaInicio").val()){
		error = true;
		$("#fechaPartida").addClass("alertaCombo");
		$("#estado").html("La fecha de la partida no puede ser mayor a la fecha de inicio de la capacitación.").addClass('alerta');
	}
	
	if($("#numeroCertificacion").val()==""){
		error = true;
		$("#numeroCertificacion").addClass("alertaCombo");
		$("#estado").html("El campo número de certificación es requerido.").addClass('alerta');
	}

	if($("#estadoAprobacion").val()==""){
		error = true;
		$("#estadoAprobacion").addClass("alertaCombo");
		$("#estado").html("El campo estado es requerido.").addClass('alerta');
	}
	
	if (!error){
		ejecutarJson(form);
	}	 
}

$("#modificarRequerimiento").submit(function(event){
	event.preventDefault();
	$("#modificarRequerimiento").attr('data-opcion', 'gestionRequerimiento');
	$("#modificarRequerimiento").removeAttr('data-destino');
	chequearCampos(this); 	 
 });
 
</script>