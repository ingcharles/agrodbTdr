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
	<h1>Aprobar requerimiento capacitación </h1>
</header>

<fieldset>
	<legend>Información empleado</legend>
	
	<div data-linea="1">
		<label>Tipo de evento</label> 
			<select name="tipoEvento" id="tipoEvento" disabled="disabled">
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
		<select name="tipoCertificado" id="tipoCertificado" disabled="disabled">
			<option value="" >Seleccione....</option>
			<option value="Asistencia">Asistencia</option>
			<option value="Aprobacion">Aprobación</option>
	   </select>
	</div>
	
	<div data-linea="2">
		<label>Nombre del evento</label>
		<input type="text" name="nombreEvento" id="nombreEvento" value="<?php echo $capacitacion['nombre_evento'];?>" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9.#\- ]+$" readonly="readonly" />	 
	</div>
	
	<div data-linea="3">
		<label>Empresa capacitadora</label> 
		<input type="text" id="empresaCapacitadora" name="empresaCapacitadora" value="<?php echo $capacitacion['empresa_capacitadora'];?>" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$" readonly="readonly" />
	</div>
	
	<div data-linea="4">
		<label>Fecha inicio</label> 
		<input type="text" id="fechaInicio" name="fechaInicio" required="required" value="<?php echo date('d/m/Y',strtotime($capacitacion['fecha_inicio']));?>" readonly="readonly" />
	</div>
	
	<div data-linea="4">
		<label>Fecha fin</label> 
		<input type="text" id="fechaFin" name="fechaFin" required="required" value="<?php echo date('d/m/Y',strtotime( $capacitacion['fecha_fin']));?>" readonly="readonly" />
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
		<select name="eventoPagado" id="eventoPagado" disabled="disabled">
			<option value="" >Seleccione....</option>
			<option value="SI">Si</option>
			<option value="NO">No</option>
	   </select>
	</div>
	
	<div data-linea="6">
		<label id="etiquetaCosto">Costo total</label> 
		<input type="text" id="costoUnitario" name="costoUnitario" value="<?php echo $capacitacion['costo_unitario']?>" data-er="^[0-9]+(\.[0-9]{1,2})?$" readonly="readonly" />
	</div>	
</fieldset>	
				
<fieldset>
	<legend>Lugar del evento</legend>
	<div data-linea="1">
		<label>Localidad</label> 
		<select name="localizacion" id="localizacion" disabled="disabled">
			<option value="" >Seleccione....</option>
			<option value="Nacional">Nacional</option>
			<option value="Internacional">Internacional</option>
	   </select>
	</div>
	
	<div data-linea="1">
		<label>País</label> 
		<select name="pais" id="pais" disabled="disabled">
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
		<select id="provincia" name="provincia" disabled="disabled" >
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
		<input type="text" id="ciudad" name="ciudad" value="<?php echo $capacitacion['ciudad']?>" readonly="readonly" />
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
	
<form id="nuevoDetalleParticipantes" data-rutaAplicacion="capacitacion" data-opcion="guardarNuevoParticipante" >
	<input type="hidden" id="opcionFuncionario" name="opcionFuncionario"/> 
	<input type="hidden" id="nombreFuncionario" name="nombreFuncionario"/> 
	<input type="hidden" id="idRequerimiento" name="idRequerimiento" value="<?php echo $idRequerimiento;?>" />
	
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
	<input type="hidden" id="opcion" name="opcion" value="actualizarEstado" /> 
	<input type="hidden" id="asignarDirector" name="asignarDirector" value="SI" />
	<input type="hidden" id="idRequerimiento" name="idRequerimiento" value="<?php echo $idRequerimiento;?>" />
	
	<div id="estado"></div>

		
		<fieldset>
			<legend>Aprobación del requerimiento</legend>
			<div data-linea="1">
				<label>Estado</label> 
					<select class="inhabilitar" name="estadoAprobacion" id="estadoAprobacion">
						<option value="" >Seleccione....</option>
						<option value="11">Aprobado</option>
						<option value="0">Rechazado</option>
				   </select>
			</div>	
			
			<label>Observación</label>
			<div data-linea="2">			
				<textarea class="readonly" rows="4" id="observacion" name="observacion" style="resize:none" ><?php echo $capacitacion['observacion'];?></textarea>
			</div>
			
			<div data-linea="3" style="text-align:center">
				<button type="submit" class="guardar inhabilitar">Guardar</button>
			</div>
		</fieldset>						

		

</form>

<script type="text/javascript">
var array_canton= <?php echo json_encode($cantones); ?>;
var estado= <?php echo json_encode($capacitacion['estado_requerimiento']); ?>;
$(document).ready(function(){
	 	
	construirValidador();
	distribuirLineas();
	
	cargarValorDefecto("tipoEvento","<?php echo $capacitacion['tipo_evento'];?>");
	cargarValorDefecto("tipoCertificado","<?php echo $capacitacion['tipo_certificado'];?>");
	cargarValorDefecto("eventoPagado","<?php echo $capacitacion['evento_pagado'];?>");
	cargarValorDefecto("localizacion","<?php echo $capacitacion['localizacion'];?>");
	cargarValorDefecto("pais","<?php echo $capacitacion['pais'];?>");
	cargarValorDefecto("provincia","<?php echo $capacitacion['provincia'];?>");
	localizacion();
	//eventoPagado();
	llenarCanton();
	cargarValorDefecto("canton","<?php echo $capacitacion['canton'];?>");
	cargarValorDefecto("capacitacionInterna","<?php echo $capacitacion['capacitacion_interna'];?>");
	cargarValorDefecto("estadoAprobacion","<?php echo $capacitacion['estado_requerimiento'];?>");
	
	if(estado!=6 ){
		$(".readonly").attr('readonly','readonly');
		$(".inhabilitar").attr('disabled','disabled');
		$(".guardar").hide();
	}

	$(".menos").attr('disabled','disabled');
	

	if($("#eventoPagado").val()=="NO"){
		$("#etiquetaCosto").hide();
		$("#costoUnitario").hide();
		}else{
			$("#etiquetaCosto").show();
			$("#costoUnitario").show();
	}

	
});

$('#area').change(function(event){
	$("#categoriaArea").val($('#area option:selected').attr('data-categoria'));
	$("#nuevoDetalleParticipantes").attr('data-opcion', 'accionesCapacitacion');
	$("#nuevoDetalleParticipantes").attr('data-destino', 'resultadoFuncionario');
	$("#opcionFuncionario").val('funcionario');
	abrir($("#nuevoDetalleParticipantes"), event, false); 				 
});


/*$("#area").change(function(){
	socupante ='0';
	socupante = '<option value="">Apellido, Nombre...</option>';
	socupante += '<option value="Todos">Todos</option>';
	for(var i=0;i<array_ocupante.length;i++){
    	if ($("#area").val()==array_ocupante[i]['area']){
    		socupante += '<option value="'+array_ocupante[i]['identificador']+'" data-bloqueo="'+array_ocupante[i]['bloqueo']+'">'+array_ocupante[i]['apellido']+', '+array_ocupante[i]['nombre']+'</option>';
	    }
		}
	
	$('#ocupante').html(socupante);
	$('#ocupante').removeAttr("disabled");
});*/


/*
function agregarOcupante(){
	if($("#ocupante  option:selected").attr('data-bloqueo')=="1")
		alert("El empleado se encuentra inabilitado para participar en capacitaciones por no culminar el anterior proceso de capacitación.");
	else{
		if($("#ocupante  option:selected").text()=="Todos"){
			//for(var i=0;i<array_ocupante.length;i++){
		    	//if ($("#area").val()==array_ocupante[i]['area']){
		    		//$("#ocupantes").append("<tr id='r_"+array_ocupante[i]['identificador']+"'><td><button type='button' onclick='quitarOcupantes(\"#r_"+array_ocupante[i]['identificador']+"\")' class='menos'>Quitar</button></td><td>"+array_ocupante[i]['apellido']+', '+array_ocupante[i]['nombre']+"<input id='ocupante_id'  name='ocupante_id[]' value='"+array_ocupante[i]['identificador']+"' type='hidden'><input name='ocupante_nombre[]' value='"+array_ocupante[i]['apellido']+', '+array_ocupante[i]['nombre']+"' type='hidden'></td></tr>");
		    		$("#ocupante option").each(function(){
				    		if(!($(this).val() == '' || $(this).val() == 'Todos')){
				    			$("#ocupantes").append("<tr id='r_"+$(this).val()+"'><td><button type='button' onclick='quitarOcupantes(\"#r_"+$(this).val()+"\")' class='menos'>Quitar</button></td><td>"+$(this).text()+"<input id='ocupante_id'  name='ocupante_id[]' value='"+$(this).val()+"' type='hidden'><input name='ocupante_nombre[]' value='"+$(this).text()+"' type='hidden'></td></tr>");
					    	}	
					});		    		
			    //}
	   		//}
		}else{
			if($("#ocupantes #r_"+$("#ocupante").val()).length==0)
				if($("#ocupante").val()!='')
					$("#ocupantes").append("<tr id='r_"+$("#ocupante").val()+"'><td><button type='button' onclick='quitarOcupantes(\"#r_"+$("#ocupante").val()+"\")' class='menos'>Quitar</button></td><td>"+$("#ocupante  option:selected").text()+"<input id='ocupante_id'  name='ocupante_id[]' value='"+$("#ocupante").val()+"' type='hidden'><input name='ocupante_nombre[]' value='"+$("#ocupante  option:selected").text()+"' type='hidden'></td></tr>");
			}
		}
	}

	
	function quitarOcupantes(fila){
		$("#ocupantes tr").eq($(fila).index()).remove();
	}
*/


/*
 $("#provincia").change(function(){
		llenarCanton();
	});*/

 function llenarCanton() {
	// $('#nombreProvincia').val($("#provincia option:selected").text());
	 	scanton = '<option value="">Canton...</option>';
	    for(var i=0;i<array_canton.length;i++){
		    if ($("#provincia").val()==array_canton[i]['padre']){
		    	scanton += '<option value="'+array_canton[i]['codigo']+'">'+array_canton[i]['nombre']+'</option>';
			    }
	   		}
	    $('#canton').html(scanton);   
}

	/*$("#eventoPagado").change(function(){
		eventoPagado();
	});*/

	/*function eventoPagado(){
		$("#costoUnitario").removeAttr("disabled");
		if($("#eventoPagado option:selected").val()=="NO"){
			$("#etiquetaCosto").hide();
			$("#costoUnitario").hide();
			}else{
				$("#etiquetaCosto").show();
				$("#costoUnitario").show();
		}

	}*/

	/*$("#localizacion").change(function(){
		localizacion();
	});*/

	function localizacion(){
		if($("#localizacion").val()=="Nacional"){
			$("#etiquetaProvincia").show();
			$("#provincia").show();
			$("#etiquetaCanton").show();
			$("#canton").show();
			//$("#pais option[value=Ecuador]").attr('selected','selected');
			//$("#pais").attr("disabled","disabled");
			$("#etiquetaCiudad").hide();
			$("#ciudad").hide();
		}else{
			$("#etiquetaProvincia").hide();
			$("#provincia").hide();
			$("#etiquetaCanton").hide();
			$("#canton").hide();
			//$("#pais").val("");
			//$("#pais").removeAttr("disabled");
			$("#etiquetaCiudad").show();
			$("#ciudad").show();
		}
		}
/*
 function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
 }*/

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
		if($("#observacion").val()==""){
			error = true;
			$("#observacion").addClass("alertaCombo");
		}
		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			 /*$("select.desabilitado").removeAttr('disabled');
				$("input.desabilitado").removeAttr('disabled');
				$("textarea.desabilitado").removeAttr('disabled');
				$('.desabilitado').removeAttr('disabled');
				$('.desabilitado').attr('disabled',false);
				$( "select" ).prop( "disabled", false );*/
			ejecutarJson(form);
			if($('#estado').html()=='Los datos han sido ingresados satisfactoriamente')
				$('#_actualizar').click();
		}
	 
 }

$("#modificarRequerimiento").submit(function(event){
	event.preventDefault();
	/*$("select.desabilitado").removeAttr('disabled');
	$("input.desabilitado").removeAttr('disabled');
	$("textarea.desabilitado").removeAttr('disabled');
	$('.desabilitado').removeAttr('disabled');
	$('.desabilitado').attr('disabled',false);*/
	
	$("#modificarRequerimiento").attr('data-opcion', 'gestionRequerimiento');
	 $("#modificarRequerimiento").removeAttr('data-destino');
	
	 chequearCampos(this);
	 
});

$("#nuevoDetalleParticipantes").submit(function(event){
	$('#nuevoDetalleParticipantes').attr('data-opcion','guardarNuevoParticipante');   
});
 acciones("#nuevoDetalleParticipantes","#ocupantes");
 /*$('#area').change(function(event){
	 $("#categoriaArea").val($('#area option:selected').attr('data-categoria'));
	 $("#modificarRequerimiento").attr('data-opcion', 'accionesCapacitacion');
	 $("#modificarRequerimiento").attr('data-destino', 'resultadoFuncionario');
	 $("#opcionFuncionario").val('funcionario');
	 abrir($("#modificarRequerimiento"), event, false); 
					 
});*/
 
</script>


