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
	<h1>Aprobar requerimiento capacitación </h1>
</header>
<div id="estado"></div>
	
<fieldset>
	<legend>Información empleado</legend>
	<div data-linea="1">
		<label>Tipo de evento</label> 
			<select class="inhabilitado" name="tipoEvento" id="tipoEvento" disabled="disabled" >
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
				<select  name="tipoCertificado" id="tipoCertificado" disabled="disabled" >
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
			<input type="text" id="empresaCapacitadora" name="empresaCapacitadora"  value="<?php echo $capacitacion['empresa_capacitadora']?>" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$" readonly="readonly"/>
	</div>
	<div data-linea="4">
		<label>Fecha inicio</label> 
			<input type="text" id="fechaInicio" name="fechaInicio" required="required" value="<?php echo date('d/m/Y',strtotime($capacitacion['fecha_inicio']));?>" readonly="readonly"  />
	</div>
	<div data-linea="4">
		<label>Fecha fin</label> 
				<input type="text" id="fechaFin" name="fechaFin" required="required"  value="<?php echo date('d/m/Y',strtotime( $capacitacion['fecha_fin']));?>" readonly="readonly" />
	</div>
	<div data-linea="5">
		<label>Horas</label> 
		<input type="text" id="horas" name="horas" size="4" value="<?php echo $capacitacion['horas']?>" data-inputmask="'mask': '9[99]'" data-er="[0-9]{1,2}" title="99" readonly="readonly" />
	</div>
	<div data-linea="5">
		<label>Capacitación interna</label> 
		<select name="capacitacionInterna" id="capacitacionInterna" disabled="disabled" >
			<option value="" >Seleccione....</option>
			<option value="SI">SI</option>
			<option value="NO">NO</option>
	   </select>
	</div>	
	<div data-linea="6">
		<label>Es evento pagado?</label> 
		<select name="eventoPagado" id="eventoPagado" disabled="disabled" >
			<option value="" >Seleccione....</option>
			<option value="SI">Si</option>
			<option value="NO">No</option>
	   </select>
	</div>
	<div data-linea="6">
		<label id="etiquetaCosto">Costo total</label> 
		<input type="text" id="costoUnitario" name="costoUnitario" readonly="readonly"  value="<?php echo $capacitacion['costo_unitario']?>" data-er="^[0-9]+(\.[0-9]{1,2})?$" />
	</div>				
</fieldset>	
					
<fieldset>
	<legend>Lugar del evento</legend>
	<div data-linea="8">
		<label>Localidad</label> 
		<select name="localizacion" id="localizacion" disabled="disabled" class="desabilitado">
			<option value="" >Seleccione....</option>
			<option value="Nacional">Nacional</option>
			<option value="Internacional">Internacional</option>
	   </select>
	</div>

	<div data-linea="8">
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
	<div data-linea="9">
		<label id="etiquetaProvincia">Provincia</label>
		<select id="provincia" name="provincia" disabled="disabled" class="desabilitado" >
			<option value="">Provincia....</option>
				<?php 	
					$provincias = $cc->listarSitiosLocalizacion($conexion,'PROVINCIAS');
					foreach ($provincias as $provincia){
						echo '<option value="' . $provincia['codigo'] . '">' . $provincia['nombre'] . '</option>';
					}
				?>
		</select> 
	</div>				
	<div data-linea="9">
		<label id="etiquetaCanton">Canton</label>
		<select id="canton" name="canton" disabled="disabled" class="desabilitado" ></select>
	</div>
	<div data-linea="10">
		<label id="etiquetaCiudad">Ciudad</label> 
		<input type="text" id="ciudad" name="ciudad" value="<?php echo $capacitacion['ciudad']?>" readonly="readonly" class="desabilitado"/>
	</div>
</fieldset>	
							
<fieldset>
	<legend>Justificación del evento</legend>
	<label>Descripción</label>
	<div data-linea="10">						 
		<textarea rows="4" id="justificacion" readonly="readonly" name="justificacion" style="resize:none"><?php echo $capacitacion['justificacion']?></textarea>
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

<form id="modificarRequerimiento" data-rutaAplicacion="capacitacion" data-opcion="gestionRequerimiento" >
	<input type="hidden" id="opcion" name="opcion" value="actualizarAprobacionTH" /> 
	<input type="hidden" id="opcionFuncionario" name="opcionFuncionario"/> 
	<input type="hidden" id="idRequerimiento" name="idRequerimiento" value="<?php echo $idRequerimiento;?>" /> 
	<input type="hidden" id="categoriaArea" name="categoriaArea" /> 
		
	<fieldset>
		<legend>Aprobación del requerimiento</legend>
		<div data-linea="1">
			<label>Estado del requerimiento por el director</label> 
			<input type="text" value="<?php
			switch ($capacitacion['estado_requerimiento']){
				case 0: $titulo_estado="Solicitud rechazado por el director"; break;
				case 1: $titulo_estado="Solicitud rechazada por talento humano"; break;
				case 6: $titulo_estado="Solicitud enviada para aprobación director"; break;
				case 8: $titulo_estado="Solicitud devuelta por talento humano"; break;
				case 11: $titulo_estado="Solicitud aprobada por el director"; break;
				case 12: $titulo_estado="Solicitud aprobada para certificación financiera"; break;
			}
			
			
			echo $titulo_estado;?>" readonly="readonly" class="desabilitado" />	
		</div>
		
		<div data-linea="2">
			<label>Observación del director</label>	
		</div>
		<div data-linea="3">							
			<textarea rows="3" id="observacion" name="observacion" readonly="readonly" class="desabilitado" style="resize:none"><?php echo $capacitacion['observacion']?></textarea>
		</div>
		<div data-linea="4">
			<label>Estado</label> 
			<select class="inhabilitar" name="estadoAprobacion" id="estadoAprobacion" >
				<option value="" >Seleccione....</option>
				<option value="0">Rechazado</option>
				<option value="8">Devolver</option>
		   </select>
		</div>	
		<div data-linea="4">
			<label>Capacitación programada</label> 
			<select class="inhabilitar"  name="capacitacionProgramada" id="capacitacionProgramada">
				<option value="" >Seleccione....</option>
				<option value="SI">SI</option>
				<option value="NO">NO</option>
		   </select>
		</div>		
		<div data-linea="5">	
			<label>Observación talento humano</label> 
		</div>  
		<div data-linea="6">				
			<textarea class="readonly"  rows="3" id="observacionTH" name="observacionTH" style="resize:none"><?php echo $capacitacion['observacion_talento_humano'];?></textarea>
		</div>
		
		<div data-linea="7" style="text-align:center">
				<button class="guardar inhabilitar" type="submit">Guardar</button>
		</div>
	</fieldset>			
</form>

<script type="text/javascript">
var array_canton= <?php echo json_encode($cantones); ?>;
var estado= <?php echo json_encode($capacitacion['estado_requerimiento']); ?>;

$(document).ready(function(){
	construirValidador();
	distribuirLineas();
	cargarValorDefecto("tipoEvento","<?php echo $capacitacion['tipo_evento']?>");
	cargarValorDefecto("tipoCertificado","<?php echo $capacitacion['tipo_certificado']?>");
	cargarValorDefecto("eventoPagado","<?php echo $capacitacion['evento_pagado']?>");
	cargarValorDefecto("localizacion","<?php echo $capacitacion['localizacion']?>");
	cargarValorDefecto("pais","<?php echo $capacitacion['pais']?>");
	cargarValorDefecto("provincia","<?php echo $capacitacion['provincia']?>");
	localizacion();
	llenarCanton();
	cargarValorDefecto("canton","<?php echo $capacitacion['canton']?>");
	cargarValorDefecto("capacitacionInterna","<?php echo $capacitacion['capacitacion_interna']?>");
	cargarValorDefecto("capacitacionProgramada","<?php echo $capacitacion['capacitacion_programada']?>");

	if($('#eventoPagado').val() == 'SI'){
		$('#estadoAprobacion').append('<option value="12">Para certificar</option>');
	}else{
		$('#estadoAprobacion').append('<option value="13">Para generar informe</option>');
	}
	
	cargarValorDefecto("estadoAprobacion","<?php echo $capacitacion['estado_requerimiento']?>");
	
	if(estado!=11){
		$(".inhabilitar").attr('disabled','disabled');
		$(".readonly").attr('readonly','readonly');
		$(".guardar").hide();
	}
	if(estado!=6 && estado!=7){
		$(".menos").attr('disabled','disabled');
		$(".mas").attr('disabled','disabled');
		$("#seccionSeleccionFuncionarios").hide();	
	}
});

$('#area').change(function(event){
	$("#categoriaArea").val($('#area option:selected').attr('data-categoria'));
	$("#nuevoDetalleParticipantes").attr('data-opcion', 'accionesCapacitacion');
	$("#nuevoDetalleParticipantes").attr('data-destino', 'resultadoFuncionario');
	$("#opcionFuncionario").val('funcionario');
	abrir($("#nuevoDetalleParticipantes"), event, false); 				 
});

function quitarOcupantes(fila){
	$("#ocupantes tr").eq($(fila).index()).remove();
}

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

	if($("#tipoEvento").val()==""){
		error = true;
		$("#tipoEvento").addClass("alertaCombo");
	}
	if($("#tipoCertificado").val()==""){
		error = true;
		$("#tipoCertificado").addClass("alertaCombo");
	}
	if($("#nombreEvento").val()==""){
		error = true;
		$("#nombreEvento").addClass("alertaCombo");
	}
	if($("#empresaCapacitadora").val()==""){
		error = true;
		$("#empresaCapacitadora").addClass("alertaCombo");
	}
	if($("#fechaInicio").val()==""){
		error = true;
		$("#fechaInicio").addClass("alertaCombo");
	}
	if($("#fechaFin").val()==""){
		error = true;
		$("#fechaFin").addClass("alertaCombo");
	}
	if(($("#eventoGratuito").val()=="NO")&& ($("#costoUnitario").val()=="" || !esCampoValido("#costoUnitario"))){
    	error = true;
		$("#costoUnitario").addClass("alertaCombo");
   	}
	if($("#horas").val()=="" || !esCampoValido("#horas")){
		error = true;
		$("#horas").addClass("alertaCombo");
	}
	if($("#estadoAprobacion").val()==""){
		error = true;
		$("#estadoAprobacion").addClass("alertaCombo");
	}
	if($("#observacionTH").val()==""){
		error = true;
		$("#observacionTH").addClass("alertaCombo");
	}
	if($("#capacitacionProgramada").val()==""){
		error = true;
		$("#capacitacionProgramada").addClass("alertaCombo");
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
	$("#modificarRequerimiento").attr('data-opcion', 'gestionRequerimiento');
	$("#modificarRequerimiento").removeAttr('data-destino');
	chequearCampos(this);  
});

$("#nuevoDetalleParticipantes").submit(function(event){
	$('#nuevoDetalleParticipantes').attr('data-opcion','guardarNuevoParticipante');   
});

acciones("#nuevoDetalleParticipantes","#ocupantes");

</script>