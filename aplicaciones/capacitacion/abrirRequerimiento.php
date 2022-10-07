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
$area = $ca->obtenerAreasDireccionesTecnicas($conexion, "('Planta Central','Oficina Técnica')", "(3,4,1)");

?>
<header>
	<h1><span class="noModificable">Modificar</span> Capacitación </h1>
</header>

<form id="modificarRequerimiento" data-rutaAplicacion="capacitacion" data-opcion="gestionRequerimiento" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" id="opcion" name="opcion" value="Actualizar" />
	<input type="hidden" id="estadoAprobacion" name="estadoAprobacion" value="6" />  
	<input type="hidden" id="idRequerimiento" name="idRequerimiento" value="<?php echo $idRequerimiento;?>" />
	<input type="hidden" id="categoriaArea" name="categoriaArea" />
	<div id="mostrarBotones">
		<p>
			<button id="modificar" type="button" class="editar" >Editar</button>
			<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
		</p>
	</div>
	<div id="estado"></div>
	<fieldset>
		<legend>Información empleado</legend>
		<div data-linea="1">
			<label>Tipo de evento</label> 
			<select name="tipoEvento" id="tipoEvento">
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
			<select name="tipoCertificado" id="tipoCertificado">
				<option value="" >Seleccione....</option>
				<option value="Asistencia">Asistencia</option>
				<option value="Aprobacion">Aprobación</option>
			 </select>
		</div>
		<div data-linea="2">
			<label>Nombre del evento</label>
			<input type="text" name="nombreEvento" id="nombreEvento" value="<?php echo $capacitacion['nombre_evento']?>" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$"/>	 
		</div>
		<div data-linea="3">
			<label>Empresa capacitadora</label> 
			<input type="text" id="empresaCapacitadora" name="empresaCapacitadora" value="<?php echo $capacitacion['empresa_capacitadora']?>" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$"/>
		</div>
		<div data-linea="4">
			<label>Fecha inicio</label> 
			<input type="text" id="fechaInicio" name="fechaInicio" value="<?php echo date('d/m/Y',strtotime($capacitacion['fecha_inicio']));?>"  />
		</div>
		<div data-linea="4">
			<label>Fecha fin</label> 
			<input type="text" id="fechaFin" name="fechaFin" value="<?php echo date('d/m/Y',strtotime( $capacitacion['fecha_fin']));?>"  />
		</div>
		<div data-linea="5">
			<label>Horas</label> 
			<input type="text" id="horas" name="horas" size="4" value="<?php echo $capacitacion['horas']?>" data-inputmask="'mask': '9[99]'" data-er="[0-9]{1,2}" title="99"  />
		</div>
			
		<div data-linea="5">
			<label>Capacitación Interna</label> 
			<select name="capacitacionInterna" id="capacitacionInterna">
				<option value="" >Seleccione....</option>
				<option value="SI">SI</option>
				<option value="NO">NO</option>
			</select>
		</div>
		<div data-linea="6">
			<label>Es evento pagado?</label> 
			<select name="eventoPagado" id="eventoPagado">
				<option value="" >Seleccione....</option>
				<option value="SI">Si</option>
				<option value="NO">No</option>
		   </select>
		</div>
		<div data-linea="6">
			<label id="etiquetaCosto">Costo total</label> 
				<input type="text" id="costoUnitario" maxlength="7"  name="costoUnitario" value="<?php echo $capacitacion['costo_unitario']?>" data-er="^[0-9]+(\.[0-9]{1,2})?$" />
		</div>
	</fieldset>	
	
	<fieldset>
		<legend>Lugar del evento</legend>
		<div data-linea="1">
			<label>Localidad</label> 
			<select name="localizacion" id="localizacion">
				<option value="" >Seleccione....</option>
				<option value="Nacional">Nacional</option>
				<option value="Internacional">Internacional</option>
		   </select>
		</div>		
		<div data-linea="1">
			<label>País</label> 
			<select name="pais" id="pais">
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
			<select id="provincia" name="provincia" >
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
				<select id="canton" name="canton" disabled="disabled" >
				</select>
		</div>
		<div data-linea="3">
			<label id="etiquetaCiudad">Ciudad</label> 
			<input type="text" id="ciudad" name="ciudad" value="<?php echo $capacitacion['ciudad']?>"/>
		</div>
	</fieldset>	
							
	<fieldset>
		<legend>Justificación del Evento</legend>
			<div data-linea="1">
				<label>Descripción</label>
			</div>
			<div data-linea="2">						 
				<textarea rows="4" id="justificacion" name="justificacion" class='readonly' style="resize:none" ><?php echo $capacitacion['justificacion']?></textarea>
			</div>
	</fieldset>
</form>

<form id="nuevoDetalleParticipantes" data-rutaAplicacion="capacitacion" data-opcion="guardarNuevoParticipante" >
	<input type="hidden" id="opcionFuncionario" name="opcionFuncionario"/> 
	<input type="hidden" id="nombreFuncionario" name="nombreFuncionario"/> 
	<input type="hidden" id="idRequerimiento" name="idRequerimiento" value="<?php echo $idRequerimiento;?>" />
	
	<fieldset>
		<legend>Seleccione a los Funcionarios Asistentes</legend>
		<div data-linea="1">
			<label>Área pertenece</label> 
			<select id="area" name="area" <?php echo 'required';?> >
				<option value="" selected="selected">Área....</option>
				<?php 
					while($fila = pg_fetch_assoc($area)){
						echo '<option value="' . $fila['id_area'] . '">' . $fila['nombre'] . '</option>';
					}
				?>
			</select>
		</div>
	
		<div id="resultadoFuncionario" data-linea="2"></div>
			
		<button type="submit" id="agregarOcupante" class="mas" >Agregar funcionario
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
			
								

<script type="text/javascript">
var array_canton= <?php echo json_encode($cantones); ?>;
var estado= <?php echo json_encode($capacitacion['estado_requerimiento']); ?>;
$(document).ready(function(){
	 	
$("#fechaInicio").datepicker({
	yearRange: "c-10:c+2",
    changeMonth: true,
    changeYear: true,
    onClose: function (selectedDate) {
    	$("#fechaFin").datepicker('setDate', null);
		$("#fechaFin").datepicker("option", "minDate", selectedDate);
    }
});

$("#fechaFin").datepicker({
	yearRange: "c-10:c+2",
    changeMonth: true,
    changeYear: true,
    onClose: function (selectedDate) {
		$("#fechaInicio").datepicker("option", "maxDate", selectedDate);
	}
});
	
construirValidador();
distribuirLineas();
cargarValorDefecto("tipoEvento","<?php echo $capacitacion['tipo_evento']?>");
cargarValorDefecto("tipoCertificado","<?php echo $capacitacion['tipo_certificado']?>");
cargarValorDefecto("eventoPagado","<?php echo $capacitacion['evento_pagado']?>");
cargarValorDefecto("localizacion","<?php echo $capacitacion['localizacion']?>");
cargarValorDefecto("pais","<?php echo $capacitacion['pais']?>");
cargarValorDefecto("capacitacionInterna","<?php echo $capacitacion['capacitacion_interna']?>");
localizacion();
eventoPagado();

cargarValorDefecto("provincia","<?php echo $capacitacion['provincia']?>");
llenarCanton();
cargarValorDefecto("canton","<?php echo $capacitacion['canton']?>");
$("#modificarRequerimiento input").attr("readonly","readonly");
$("#modificarRequerimiento textarea").attr("readonly","readonly");
$("#modificarRequerimiento select").attr("disabled","disabled");

if(estado!=6 && estado!=7 && estado!=8){
	$(".inhabilitar").attr('disabled','disabled');
	$("#modificar").hide();
	$("#actualizar").hide();
	$(".noModificable").html('Datos');
	
	$(".mas").attr('disabled','disabled');
	$(".readonly").attr('readonly','readonly');
}

});

$("#modificar").click(function(){
	$("input").removeAttr("readonly");
	$("select").removeAttr("disabled");
	$("textarea").removeAttr("readonly");
	$("#actualizar").removeAttr("disabled");
	$(this).attr("disabled","disabled");
});  


$("#nuevoDetalleParticipantes").submit(function(event){
	$('#nuevoDetalleParticipantes').attr('data-opcion','guardarNuevoParticipante');   
});
acciones("#nuevoDetalleParticipantes","#ocupantes");

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
    $("#canton").removeAttr("disabled");
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
			$("#pais").removeAttr("disabled");
			$("#etiquetaCiudad").show();
			$("#ciudad").show();
		}
		}

 function chequearCampos(form){
	 $(".alertaCombo").removeClass("alertaCombo");
		var error = false;
		var errorTabla = false;
		
		if($("#tipoEvento").val()==""){
			error = true;
			$("#tipoEvento").addClass("alertaCombo");
		}
		if($("#tipoCertificado").val()==""){
			error = true;
			$("#tipoCertificado").addClass("alertaCombo");
		}
		if($("#nombre_evento").val()==""){
			error = true;
			$("#nombre_evento").addClass("alertaCombo");
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
		if($("#eventoPagado").val()==""){
			error = true;
			$("#eventoPagado").addClass("alertaCombo");
		}
		else if (($("#eventoPagado").val()=="SI")&& ($("#costoUnitario").val()=="" || !esCampoValido("#costoUnitario"))){
    	   error = true;
		   $("#costoUnitario").addClass("alertaCombo");
        }
		if($("#horas").val()=="" || !esCampoValido("#horas")){
			error = true;
			$("#horas").addClass("alertaCombo");
		}
		if($("#justificacion").val()==""){
			error = true;
			$("#justificacion").addClass("alertaCombo");
		}
		if($("#localizacion").val()==""){
			error = true;
			$("#localizacion").addClass("alertaCombo");
		}
		else if($("#localizacion").val()=="Nacional"){
			if($("#provincia").val()==""){
				error = true;
				$("#provincia").addClass("alertaCombo");
			}
			if($("#canton").val()==""){
				error = true;
				$("#canton").addClass("alertaCombo");
			}
		}else{
			if($("#pais").val()==""){
				error = true;
				$("#pais").addClass("alertaCombo");
			}
			if($("#ciudad").val()==""){
				error = true;
				$("#ciudad").addClass("alertaCombo");
			}
		}
				
		if ($('#ocupantes >tr').length == 0 && !error){
			 error = true;
			 errorTabla = true;	
			 $("#tabla").addClass("alertaCombo");
		}
		
		if (error){
			if(errorTabla)
				 $("#estado").html('Por favor agregre al menos a un funcionario.').addClass("alerta");
			else
				$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else if (!error && !errorTabla){
			if($("#eventoPagado").val()=="NO"){
				$("#costoUnitario").val("");
			}
			$("#modificarRequerimiento").attr('data-opcion', 'gestionRequerimiento');
			ejecutarJson(form);
		}
 }

 $("#modificarRequerimiento").submit(function(event){
	 event.preventDefault();
	 chequearCampos(this);
 });

$('#area').change(function(event){
	$("#categoriaArea").val($('#area option:selected').attr('data-categoria'));
	$("#nuevoDetalleParticipantes").attr('data-opcion', 'accionesCapacitacion');
	$("#nuevoDetalleParticipantes").attr('data-destino', 'resultadoFuncionario');
	$("#opcionFuncionario").val('funcionario');
	abrir($("#nuevoDetalleParticipantes"), event, false); 				 
});
 
</script>


