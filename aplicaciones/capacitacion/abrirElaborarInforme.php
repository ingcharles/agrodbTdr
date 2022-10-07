<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCapacitacion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorEmpleados.php';

$conexion = new Conexion();
$ce = new ControladorCapacitacion();
$cc = new ControladorCatalogos();
$cu = new ControladorUsuarios();
$cem = new ControladorEmpleados();

$idRequerimiento=$_POST['id'];
$identificador=$_SESSION['usuario'];

$resCapacitacion = $ce->obtenerRequerimientos($conexion,'','','',$idRequerimiento,'','','','');
$capacitacion = pg_fetch_assoc($resCapacitacion);

$qNombreAprobador = $cem->obtenerFichaEmpleado($conexion, $capacitacion['identificador_distrital_a']);
$nombreAprobador = pg_fetch_assoc($qNombreAprobador);

$qNombreArea = $cu->obtenerAreaUsuario($conexion, $capacitacion['identificador_distrital_a']);
$nombreArea = pg_fetch_assoc($qNombreArea);

$qNombreResponsable = $cem->obtenerFichaEmpleado($conexion, $capacitacion['identificador']);
$nombreResponsable = pg_fetch_assoc($qNombreResponsable);

$cantones= $cc->listarSitiosLocalizacion($conexion,'CANTONES');
$res = $cc->listarLocalizacion($conexion, 'PAIS');

$usuario = $cu->obtenerUsuariosXarea($conexion);

while($fila = pg_fetch_assoc($usuario)){
	$ocupante[]= array(identificador=>$fila['identificador'], apellido=>$fila['apellido'], nombre=>$fila['nombre'], area=>$fila['id_area']);
}

?>
<header>
	<h1>Elaboración de informe de capacitación </h1>
</header>

<form id="modificarRequerimiento" data-rutaAplicacion="capacitacion" data-opcion="gestionarInforme" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" id="opcion" name="opcion" value="actualizarEstadoEI" />  
	<input type="hidden" id="idRequerimiento" name="idRequerimiento" value="<?php echo $idRequerimiento;?>" />
	<input type="hidden" id="nombreDirector" name="nombreDirector" value="<?php echo $nombreAprobador['apellido'].' '.$nombreAprobador['nombre'];?>" /> 
	<input type="hidden" id="nombreResponsable" name="nombreResponsable" value="<?php echo $nombreResponsable['apellido'].' '.$nombreResponsable['nombre'];?>" /> 
	<input type="hidden" id="nombreArea" name="nombreArea" value="<?php echo $nombreArea['nombre'];?>" /> 
	<input type="hidden" id="nombreTipoEvento" name="nombreTipoEvento" value="0" />
	<input type="hidden" id="nombreCanton" name="nombreCanton" value="0" />
	<input type="hidden" id="fechaSolicitud" name="fechaSolicitud" value="<?php echo $capacitacion['fecha_modificacion'];?>" />
	<input type="hidden" id="estadoAprobacion" name="estadoAprobacion" value="14" />
	<div id="estado"></div>
	
	<fieldset>
		<legend>Información empleado</legend>
		<div data-linea="1">
			<label>Tipo de evento</label> 
			<select name="tipoEvento" id="tipoEvento" class="desabilitado">
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
			<label>Es evento pagado?</label> 
				<select name="eventoPagado" id="eventoPagado" disabled="disabled" class="desabilitado">
					<option value="" >Seleccione....</option>
					<option value="SI">Si</option>
					<option value="NO">No</option>
			   </select>
		</div>
		<div data-linea="5">
			<label id="etiquetaCosto">Costo total</label> 
				<input type="text" id="costoUnitario" name="costoUnitario" readonly="readonly"  value="<?php echo $capacitacion['costo_unitario']?>" data-er="^[0-9]+(\.[0-9]{1,2})?$" />
		</div>
		<div data-linea="6">
			<label>Horas</label> 
			<input type="text" id="horas" name="horas" size="4" value="<?php echo $capacitacion['horas']?>" data-inputmask="'mask': '9[99]'" data-er="[0-9]{1,2}" title="99" readonly="readonly" />
		</div>
		<div data-linea="6">
			<label>Capacitación interna</label> 
				<select name="capacitacionInterna" id="capacitacionInterna">
					<option value="" >Seleccione....</option>
					<option value="SI">SI</option>
					<option value="NO">NO</option>
			   </select>
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
		<div data-linea="2">
			<label id="etiquetaCanton">Cantón</label>
				<select id="canton" name="canton" disabled="disabled" class="desabilitado" >
				</select>
		</div>
		<div data-linea="3">
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
			<textarea rows="4" id="justificacion" readonly="readonly" name="justificacion" class="desabilitado" style="resize:none"><?php echo $capacitacion['justificacion']?></textarea>
		</div>
	</fieldset>
	
	<fieldset>
		<legend>Aprobación del requerimiento</legend>
		<div data-linea="1">
			<label>Estado del requerimiento</label> 
			<input type="text" value="<?php
			switch ($capacitacion['estado_requerimiento']){
				case 1: $tituloEstado="Solicitud rechazada por el director"; break;
				case 1: $tituloEstado="Solicitud rechazada por talento humano"; break;
				case 6: $tituloEstado="Solicitud ingresada"; break;
				case 8: $tituloEstado="Solicitud devuelta por talento humano"; break;
				case 11: $tituloEstado="Solicitud aprobada por el director"; break;
				case 12: $tituloEstado="Solicitud aprobada para certificación financiera"; break;
				case 13: $tituloEstado="Solicitud con certificación financiera "; break;
				case 14: $tituloEstado="Solicitud por asignar replicación"; break;
			}
			echo $tituloEstado;?>" readonly="readonly" class="desabilitado" />	
		</div>	

		<div data-linea="2">
			<label>Observación del director</label>
		</div>
		<div data-linea="3">
			<textarea rows="3" id="observacion" name="observacion" readonly="readonly" class="desabilitado" style="resize:none"><?php echo $capacitacion['observacion']?></textarea>
		</div>
		<div data-linea="4">
			<label>Observación talento humano</label> 	
		</div>
  		<div data-linea="5">			
			<textarea rows="3" id="observacionTH" name="observacionTH" readonly="readonly" style="resize:none"><?php echo $capacitacion['observacion_talento_humano']?></textarea>
		</div>
	
		</fieldset>	
			<fieldset>
			<legend>Funcionarios agregados</legend>
			<table id="tabla">
				<tbody id="ocupantes">
				<?php
					$resFuncionarios=$ce->obtenerFuncionarios($conexion,$idRequerimiento);
					while ($fila = pg_fetch_assoc($resFuncionarios)){
						echo '<input type="hidden" name="ocupanteNombre[]" value="' . $fila['apellido'].' '.$fila['nombre'] . '" >' ;
						echo $ce->imprimirLineaAsistenteCapacitacion($fila['id_participantes'], $fila['apellido'].' '.$fila['nombre']);
					}
				?>
				</tbody>
			</table>		
		</fieldset>		
		<fieldset id="informacionFinanciera">
				<legend>Certificación financiera</legend>
				<div data-linea="1">
					<label>Nombre de la partida</label> 
					<input type="text" id="nombreCertificacion" name="nombreCertificacion" value="<?php echo $capacitacion['nombre_certificacion']?>" readonly="readonly"/>
				</div>
				<div data-linea="2">
					<label>fecha de la partida</label> 
					<input type="text" id="fechaPartida" name="fechaPartida" value="<?php echo $capacitacion['fecha_partida']?>" readonly="readonly"/>
				</div>
				<div data-linea="2">
					<label>No. certificación</label> 
					<input type="text" id="numeroCertificacion" name="numeroCertificacion" value="<?php echo $capacitacion['numero_certificacion']?>" readonly="readonly"/>
				</div>
				<div data-linea="5">
					<label>Archivo certificación</label> <?php echo ($capacitacion['archivo']==''? '<span class="alerta">No ha subido ningún archivo aún</span>':'<a href='.$capacitacion['archivo'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>')?>
			    </div>
		</fieldset>
		
	
	<fieldset>
		<legend>Información para el informe talento humano</legend>
			<div data-linea="1">
				<label>Objetivo del curso</label>	
			</div>
			<div data-linea="2">
				<textarea rows="3" class="readonly" id="objetivoCurso" name="objetivoCurso" style="resize:none"><?php echo $capacitacion['objetivo_curso']?></textarea>
			</div>
			<div data-linea="3">
				<label>Justificación de recursos humanos</label>
			</div>
		   	<div data-linea="4">
				<textarea rows="3" class="readonly" id="justificacionTH" name="justificacionTH" style="resize:none"><?php echo $capacitacion['justificacion_th']?></textarea>
			</div> 
			<div data-linea="5">
				 <?php echo ($capacitacion['ruta_informe']!=''? '<label>Informe generado</label><a href='.$capacitacion['ruta_informe'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Informe de Capacitación Generado</a>':'')?>
   			</div>   
	</fieldset>
	<p>
		<button class="guardar inhabilitar" type="submit" >Guardar</button>
	</p>
	
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
	localizacion();
	eventoPagado();
	cargarValorDefecto("provincia","<?php echo $capacitacion['provincia']?>");
	llenarCanton();
	cargarValorDefecto("canton","<?php echo $capacitacion['canton']?>");
	cargarValorDefecto("capacitacionInterna","<?php echo $capacitacion['capacitacion_interna']?>");
	$('#nombreCanton').val($("#canton option:selected").text());
	$('#nombreTipoEvento').val($("#tipoEvento option:selected").text());
	 if($('#eventoPagado').val() == 'SI'){
		$('#informacionFinanciera').show();
	 }else{
		$('#informacionFinanciera').hide();
	}

	if(estado!=13){
		$(".inhabilitar").attr('disabled','disabled');
		$(".readonly").attr('readonly','readonly');
		$(".guardar").hide();
	}

	if(estado!=6 && estado!=7){
		$(".menos").attr('disabled','disabled');
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
	if (error){
		$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
	}else{
		ejecutarJson(form);
	}
	 
}

$("#modificarRequerimiento").submit(function(event){
	 event.preventDefault();
	 chequearCampos(this);	 
 });
</script>