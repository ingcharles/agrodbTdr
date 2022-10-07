<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAreas.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$ca = new ControladorAreas();

$identificador=$_SESSION['usuario'];

$cantones= $cc->listarSitiosLocalizacion($conexion,'CANTONES');
$res = $cc->listarLocalizacion($conexion, 'PAIS');
$area = $ca->obtenerAreasDireccionesTecnicas($conexion, "('Planta Central','Oficina Técnica')", "(3,4,1)");

?>

<header>
	<h1>Nueva Capacitación</h1>
</header>

<form id="nuevoRequerimiento" data-rutaAplicacion="capacitacion" data-opcion="gestionRequerimiento" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" id="opcion" name="opcion" value="Nuevo" />
	<input type="hidden" id="opcionFuncionario" name="opcionFuncionario"/>
	<input type="hidden" id="categoriaArea" name="categoriaArea" />
	<div id="estado"></div>
	
	<fieldset>
		<legend>Información capacitación</legend>
		<div data-linea="1">
			<label>Tipo de evento</label> <select name="tipoEvento"
				id="tipoEvento">
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
			<label>Tipo de certificado</label> <select name="tipoCertificado"
				id="tipoCertificado">
				<option value="">Seleccione....</option>
				<option value="Asistencia">Asistencia</option>
				<option value="Aprobacion">Aprobación</option>
			</select>
		</div>
		<div data-linea="2">
			<label>Nombre del evento</label> <input type="text"
				name="nombreEvento" id="nombreEvento"
				data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$" />
		</div>
		<div data-linea="3">
			<label>Empresa capacitadora</label> <input type="text"
				id="empresaCapacitadora" name="empresaCapacitadora"
				data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$" />
		</div>
		<div data-linea="4">
			<label>Fecha inicio</label> <input type="text" id="fechaInicio"
				name="fechaInicio" readonly="readonly" required="required" />
		</div>
		<div data-linea="4">
			<label>Fecha fin</label> <input type="text" id="fechaFin"
				name="fechaFin" readonly="readonly" required="required" />
		</div>
		<div data-linea="5">
			<label>Horas</label> 
			<input type="text" id="horas" name="horas" size="4" placeholder="Ej.40" data-inputmask="'mask': '9[99]'" data-er="[0-9]{1,2}" title="99" />
		</div>
		<div data-linea="5">
			<label>Capacitación Interna</label> 
			<select	name="capacitacionInterna" id="capacitacionInterna">
				<option value="">Seleccione....</option>
				<option value="SI">SI</option>
				<option value="NO">NO</option>
			</select>
		</div>
		<div data-linea="6">
			<label>Es evento pagado?</label> <select name="eventoPagado"
				id="eventoPagado">
				<option value="">Seleccione....</option>
				<option value="SI">Si</option>
				<option value="NO">No</option>
			</select>
		</div>
		<div data-linea="6">
			<label id="etiquetaCosto">Costo total</label> <input type="text"
				class="numeric" id="costoUnitario"  maxlength="7" readonly	name="costoUnitario" data-er="^[0-9]+(\.[0-9]{1,2})?$" />
		</div>
	</fieldset>

	<fieldset>
		<legend>Lugar del evento</legend>
		<div data-linea="1">
			<label>Localidad</label> <select name="localizacion"
				id="localizacion">
				<option value="">Seleccione....</option>
				<option value="Nacional">Nacional</option>
				<option value="Internacional">Internacional</option>
			</select>
		</div>
		<div data-linea="1">
			<label>País</label> 
			<select name="pais" id="pais">
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
			<select	id="provincia" name="provincia">
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
				<select id="canton" name="canton" disabled="disabled"></select>
		</div>
		<div data-linea="3">
			<label id="etiquetaCiudad">Ciudad</label> 
				<input type="text" id="ciudad" name="ciudad"/>
		</div>
	</fieldset>

	<fieldset>
		<legend>Justificación del evento</legend>

		<label>Descripción</label>
		<div data-linea="1">
			<textarea rows="4" id="justificacion" name="justificacion" style="resize:none"></textarea>
		</div>
	</fieldset>
	
	<fieldset>
		<legend>Seleccionar funcionarios asistentes a la capacitación</legend>
		<div data-linea="1">
			<label>Área pertenece</label>
			<select id="area" name="area">
				<option value="" >Seleccione....</option>
				<?php 
					while($fila = pg_fetch_assoc($area)){
						echo '<option value="' . $fila['id_area'] . '" data-categoria="' . $fila['categoria_area'] . '" >' . $fila['nombre'] . '</option>';
					}
				?>
			</select>
		</div>
		<div id="resultadoFuncionario" data-linea="2"></div>
		<button type="button" id="agregarOcupante" class="mas">Agregar funcionario</button>
	</fieldset>
	<fieldset>
		<legend>Funcionarios agregados</legend>
		<table id="tabla" style="width:100%">
			<tbody id="ocupantes">
			</tbody>
		</table>
	</fieldset>		
	<p>
		<button id="actualizar" type="submit" class="guardar">Guardar</button>
	</p>
</form>

<script type="text/javascript">
var array_canton= <?php echo json_encode($cantones); ?>;

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
		yearRange: "c:c+2",
		changeMonth: true,
		changeYear: true,
		onClose: function (selectedDate) {
			$("#fechaInicio").datepicker("option", "maxDate", selectedDate);
		}
	});

	distribuirLineas();
	$("#etiquetaCiudad").hide();
	$("#ciudad").hide();
	llenarCanton();
	construirValidador();
	$("#costoUnitario").numeric();
});
 
$("#agregarOcupante").click(function(event){

	$(".alertaCombo").removeClass("alertaCombo");
    var error = false;

    if($("#area").val() == "" ){	
    	error = true;		
    	$("#area").addClass("alertaCombo");
    	$("#estado").html('Por favor seleccione el área a donde pertenece el funcionario.').addClass("alerta");
    }

    if($("#ocupante").val() == "" ){	
    	error = true;		
    	$("#ocupante").addClass("alertaCombo");
    	$("#estado").html('Por favor seleccione a los funcionarios .').addClass("alerta");
    }
    	
    if($("#ocupante option:selected").attr('data-bloqueo')=="1"){	
    	error = true;		
    	$("#estado").html('El empleado se encuentra inhabilitado para participar en capacitaciones por no culminar el anterior proceso de capacitación.').addClass("alerta");
    }

    if($("#ocupante option:selected").text()=="Todos"){
		$("#ocupante option").each(function(){
		    if(!($(this).val() =="" || $(this).val() == 'Todos')){
			    if($(this).attr('data-bloqueo')=="1"){
			    	error = true;	
			    	$("#estado").html('Uno de los empleados se encuentra inhabilitado para participar en capacitaciones por no culminar el anterior proceso de capacitación.').addClass("alerta");
			    }
			}	   						
		});
	}


    if($("#ocupantes #r_"+$("#ocupante").val()).length==1){
    	error = true;
    	$("#ocupantes #r_"+$("#ocupante").val()).addClass("alertaCombo");		
    	$("#estado").html('El empleado se encuentra inhabilitado para participar en capacitaciones por no culminar el anterior proceso de capacitación.').addClass("alerta");
    }
	
	
	if (!error){
		if($("#ocupante option:selected").text()=="Todos"){
			$("#ocupante option").each(function(){
			    if(!($(this).val() =="" || $(this).val() == 'Todos')){
			    	if($("#ocupantes #r_"+$(this).val()).length==0)
			    	$("#ocupantes").append("<tr id='r_"+$(this).val()+"'><td width='100%'  >"+$(this).text()+"<input id='ocupante_id'  name='ocupante_id[]' value='"+$(this).val()+"' type='hidden'><input name='ocupante_nombre[]' value='"+$(this).text()+"' type='hidden'></td><td><button type='button' onclick='quitarOcupantes(\"#r_"+$(this).val()+"\")' class='menos'>Quitar</button></td></tr>");
				}	   						
			});
		}else{
			if($("#ocupantes #r_"+$("#ocupante").val()).length==0)
				if($("#ocupante").val()!='')
					$("#ocupantes").append("<tr id='r_"+$("#ocupante").val()+"'><td width='100%'>"+$("#ocupante  option:selected").text()+"<input id='ocupante_id'  name='ocupante_id[]' value='"+$("#ocupante").val()+"' type='hidden'><input name='ocupante_nombre[]' value='"+$("#ocupante  option:selected").text()+"' type='hidden'></td><td><button type='button' onclick='quitarOcupantes(\"#r_"+$("#ocupante").val()+"\")' class='menos'>Quitar</button></td></tr>");
		}
	}
});

function quitarOcupantes(fila){
	$("#ocupantes tr").eq($(fila).index()).remove();
}

$("#provincia").change(function(){
	llenarCanton();
});

$("#eventoPagado").change(function(){
	if($("#eventoPagado option:selected").val()=="NO"){
		$("#etiquetaCosto").hide();
		$("#costoUnitario").hide();
		$("#costoUnitario").val("");
	}else{
		$("#costoUnitario").removeAttr("readonly");
		$("#etiquetaCosto").show();
		$("#costoUnitario").show();
	}
});

$("#localizacion").change(function(){
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
		$("#pais option[value=Ecuador]").attr('disabled','disabled');
		$("#pais").val("");
		$("#pais").removeAttr("disabled");
		$("#etiquetaCiudad").show();
		$("#ciudad").show();
	}
});

function llenarCanton() {
	$('#nombreProvincia').val($("#provincia option:selected").text());
 	scanton = '<option value="">Seleccione...</option>';
    for(var i=0;i<array_canton.length;i++){
	    if ($("#provincia").val()==array_canton[i]['padre']){
	    	scanton += '<option value="'+array_canton[i]['codigo']+'">'+array_canton[i]['nombre']+'</option>';
		    }
   		}
    $('#canton').html(scanton);
    $("#canton").removeAttr("disabled");
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
		if($("#nombreEvento").val()=="" || !esCampoValido("#nombreEvento")){
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
		if($("#capacitacionInterna").val()==""){
			error = true;
			$("#capacitacionInterna").addClass("alertaCombo");
		}
		if($("#localizacion").val()==""){
			error = true;
			$("#localizacion").addClass("alertaCombo");
		}else if($("#localizacion").val()=="Nacional"){
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
			$("#nuevoRequerimiento").attr('data-opcion', 'gestionRequerimiento');
			$("#nuevoRequerimiento").removeAttr('data-destino');
			ejecutarJson(form);
		}	 
 }

 $("#nuevoRequerimiento").submit(function(event){
	 event.preventDefault();
	 chequearCampos(this);
 });

 $('#area').change(function(event){
	 $("#categoriaArea").val($('#area option:selected').attr('data-categoria'));
	 $("#nuevoRequerimiento").attr('data-opcion', 'accionesCapacitacion');
	 $("#nuevoRequerimiento").attr('data-destino', 'resultadoFuncionario');
	 $("#opcionFuncionario").val('funcionario');
	 abrir($("#nuevoRequerimiento"), event, false); 
					 
});
</script>