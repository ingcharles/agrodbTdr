<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCapacitacion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorAreas.php';
require_once '../../clases/ControladorUsuarios.php';

$conexion = new Conexion();
$ce = new ControladorCapacitacion();
$cc = new ControladorCatalogos();
$cu = new ControladorUsuarios();
$ca = new ControladorAreas();

$id_requerimiento=$_POST['id'];
$resCapacitacion = $ce->obtenerRequerimientos($conexion,'','','',$id_requerimiento,'','','','');


$capacitacion = pg_fetch_assoc($resCapacitacion);

$identificador=$_SESSION['usuario'];

$cantones= $cc->listarSitiosLocalizacion($conexion,'CANTONES');
$res = $cc->listarLocalizacion($conexion, 'PAIS');


$usuario = $cu->obtenerUsuariosXarea($conexion);

while($fila = pg_fetch_assoc($usuario)){
	$ocupante[]= array(identificador=>$fila['identificador'], apellido=>$fila['apellido'], nombre=>$fila['nombre'], area=>$fila['id_area']);
}

?>
<header>
	<h1>Modificar requerimiento capacitación </h1>
</header>



<form id="modificarRequerimiento" data-rutaAplicacion="capacitacion" data-opcion="gestionRequerimiento" >
	<input type="hidden" id="opcion" name="opcion" value="Actualizar" />
	<input type="hidden" id="estadoAprobacion" name="estadoAprobacion" value="7" />  
	<input type="hidden" id="id_requerimiento" name="id_requerimiento" value="<?php echo $id_requerimiento;?>" /> 
	
	
	<div id="estado"></div>
	<table class="soloImpresion">
		<tr>
			<td></td>
			<td>
				<fieldset>
					<legend>Información Empleado</legend>
					
					<div data-linea="1">
						<label>Tipo de evento</label> 
							<select name="tipoEvento" id="tipoEvento" disabled="disabled" >
								<option value="" >Seleccione....</option>							
						  			 <?php 							  	
											$tipoEvento = $cc->listarTiposCapacitacion($conexion);
                                            while($fila = pg_fetch_assoc($tipoEvento)){
										echo '<option value="' . $fila['codigo'] . '">' . $fila['nombre'].' </option>';					
									}?>
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
						<input type="text" name="nombre_evento" disabled="disabled" id="nombre_evento" value="<?php echo $capacitacion['nombre_evento']?>" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$"/>	 
					</div>
					<div data-linea="3">
						<label>Capacitación Interna</label> 
							<select name="capacitacionInterna" id="capacitacionInterna">
								<option value="" >Seleccione....</option>
								<option value="SI">SI</option>
								<option value="NO">NO</option>
						   </select>
					</div>
					<div data-linea="3">
						<label></label> 
					</div>
					<div data-linea="4">
						<label>Empresa capacitadora</label> 
							<input type="text" id="empresaCapacitadora" disabled="disabled" name="empresaCapacitadora" value="<?php echo $capacitacion['empresa_capacitadora']?>" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü0-9#\- ]+$"/>
					</div>
					
					<div data-linea="5">
						<label>Fecha inicio</label> 
								<input type="text" id="fechaInicio" name="fechaInicio" disabled="disabled" required="required" value="<?php echo date('d/m/Y',strtotime($capacitacion['fecha_inicio']));?>"  />
					</div>
					<div data-linea="5">
						<label>Fecha fin</label> 
								<input type="text" id="fechaFin" name="fechaFin" disabled="disabled" required="required" value="<?php echo date('d/m/Y',strtotime( $capacitacion['fecha_fin']));?>"  />
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
							<input type="text" id="costoUnitario" name="costoUnitario" disabled="disabled" value="<?php echo $capacitacion['costo_unitario']?>" data-er="^[0-9]+(\.[0-9]{1,2})?$" />
					</div>
					
					<div data-linea="7">
						<label>Horas</label> 
						<input type="text" id="horas" name="horas" size="4" disabled="disabled" value="<?php echo $capacitacion['horas']?>" data-inputmask="'mask': '9[99]'" data-er="[0-9]{1,2}" title="99"  />
					</div>
						
					<div data-linea="7">
						<label></label> 
					</div>		
					</fieldset>	
					
					<fieldset>
					<legend>Lugar del evento</legend>
					<div data-linea="8">
						<label>Localidad</label> 
							<select name="localizacion" id="localizacion" disabled="disabled">
								<option value="" >Seleccione....</option>
								<option value="Nacional">Nacional</option>
								<option value="Internacional">Internacional</option>
						   </select>
					</div>
					
					<div data-linea="8">
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
					<div data-linea="9">
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
					<div data-linea="9">
						<label id="etiquetaCanton">Canton</label>
							<select id="canton" name="canton" disabled="disabled" >
							</select>
					</div>
				
					<div data-linea="10">
						<label id="etiquetaCiudad">Ciudad</label> 
						<input type="text" id="ciudad" name="ciudad" disabled="disabled" value="<?php echo $capacitacion['ciudad']?>"/>
					</div>
				</fieldset>	
							
				<fieldset>
					<legend>Justificación del Evento</legend>
					<div data-linea="10">
						<label>Descripción:</label> 
						<textarea rows="4" id="justificacion" disabled="disabled" name="justificacion"><?php echo $capacitacion['justificacion']?></textarea>
					</div>
				</fieldset>
				<fieldset>
		<legend>Asistentes</legend>
		
		<div data-linea="2">
			<div id="opcion_ocupante">
				<label>Observación</label>
					<input type="text" name="observacion_ocupante" id="observacion_ocupante" disabled="disabled" value="<?php echo $capacitacion['observacion']?>" />
			</div>
		</div>
		
			<table>
				<thead>
					<tr>
						<th colspan="2">Funcionarios seleccionados</th>
					<tr>
				</thead>
				<tbody id="ocupantes">
				<?php
				$resFuncionarios=$ce->obtenerFuncionarios($conexion,$id_requerimiento);
				while($filaFuncionario = pg_fetch_assoc($resFuncionarios)){
					if($filaFuncionario['nombre']==''&& $filaFuncionario['apellido']=='')
					{
						if((strcmp ($filaFuncionario['identificador'],'Todos')== 0)){
							$nombre="Agrocalidad";
							$apellido="Todos los funcionarios de ";
						}else{ 
						$area2 = $ca->listarAreas($conexion);
						while($filaArea = pg_fetch_assoc($area2)){

							if($filaArea['id_area']==$filaFuncionario['identificador']){
								$nombre=$filaArea['nombre'];
								$apellido="Todos los funcionarios de ";
							}
						}
						}
					}
					else{
						$nombre=$filaFuncionario['nombre'];
						$apellido=$filaFuncionario['apellido'];
					}	
					echo "<tr id='r_".$filaFuncionario['identificador']."'><td></td><td>".$apellido.' '.$nombre."<input id='ocupante_id'  name='ocupante_id[]' value='".$filaFuncionario['identificador']."' type='hidden'><input name='ocupante_nombre[]' value='".$apellido.' '.$nombre."' type='hidden'></td></tr>";
				
				}
										
				?>
				</tbody>
			</table>		
	</fieldset>
								
			</td>
		</tr>
	</table>
	
	
</form>

<script type="text/javascript">
var array_canton= <?php echo json_encode($cantones); ?>;
var array_ocupante= <?php echo json_encode($ocupante); ?>;

$(document).ready(function(){
 	 	
	$("#fechaInicio").datepicker({
    	yearRange: "c-10:c+1",
    	changeMonth: true,
    	changeYear: true
    });
	$( "#fechaFin" ).datepicker({
	      yearRange: "c-10:c+1",
	      changeMonth: true,
	      changeYear: true
	});
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
	    $("#canton").removeAttr("disabled");
}

$("#eventoPagado").change(function(){
	eventoPagado();
});

function eventoPagado(){

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
</script>