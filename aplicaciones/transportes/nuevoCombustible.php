<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorAreas.php';

$conexion = new Conexion();
$cv = new ControladorVehiculos();
$cu = new ControladorUsuarios();
$ca = new ControladorAreas();

//Vehiculo Direccion Ejecutiva
$DIR_EJE = 'PEI-1418';

//Identificador Usuario Administrador o Apoyo de Transportes
$identificadorUsuarioRegistro = $_SESSION['usuario'];

$gasolineras = $cv->abrirDatosGasolineras($conexion, $_SESSION['nombreLocalizacion']);
$vehiculo = $cv->obtenerDatosVehiculos($conexion, $_SESSION['nombreLocalizacion'], "Combustible");
$area = $ca->obtenerAreasDireccionesTecnicas($conexion, "('Planta Central','Oficina Técnica')", "(3,4,1)");

$usuario = $cu->obtenerUsuariosXarea($conexion);
while($fila = pg_fetch_assoc($usuario)){
	$responsable[]= array(identificador=>$fila['identificador'], apellido=>$fila['apellido'], nombre=>$fila['nombre'], area=>$fila['id_area']);
}

$administrador = $cv->buscarAdministrador($conexion, $_SESSION['idLocalizacion']);

$jefeTransportes = pg_fetch_result($administrador, 0, 'nombres_completos');

?>

<header>
	<h1>Orden de Combustible</h1>
</header>

<div id="estado"></div>


<form id="nuevoCombustible" data-rutaAplicacion="transportes" data-opcion="guardarNuevoCombustible" data-destino="detalleItem"  data-accionEnExito='ACTUALIZAR'>
	
	<input type="hidden" name="id_vehiculo" id="id_vehiculo"/>
	<input type='hidden' id='identificadorUsuarioRegistro' name='identificadorUsuarioRegistro' value="<?php echo $identificadorUsuarioRegistro;?>" />
  	<input type='hidden' id='jefeTransportes' name='jefeTransportes' value="<?php echo $jefeTransportes;?>" />
  	
	<fieldset id="datosVehiculo">
	
		<legend>Datos del vehículo</legend>
		
			<div data-linea="1">
			
				<label>Vehículo</label>	
					<select id="vehiculo" name="vehiculo" >
						<option value="">Vehículo....</option>
						<?php 
							while($fila = pg_fetch_assoc($vehiculo)){
								echo '<option value="' . $fila['placa'] . '" data-idVehiculo="'. $fila['id_vehiculo'].'" data-descripcion="Tipo de combustible: '. $fila['combustible'].'" data-kilometraje="Kilometraje actual: '. $fila['kilometraje_actual'].'">' . $fila['marca'] .' '.$fila['modelo'] .' -> '.$fila['placa']. '</option>';					
							}
						?>
					</select>
			
			</div><div data-linea="1">
				
				<label>Kilometraje</label> 
					<input type="number" step="1" name="kilometraje" id="kilometraje" placeholder="Ej: 12345" required="required"/>
					<input type="hidden" name="kilometrajeSolicitud" id="kilometrajeSolicitud" /> 
				
			</div>
	</fieldset>
	
	<fieldset>
		<legend>Conductor</legend>
		
		<div data-linea="3">	
	
		<label>Área</label>
				<select id="area" name="area" >
					<option value="">Áreas....</option>
					<?php 
						while($fila = pg_fetch_assoc($area)){
								echo '<option value="' . $fila['id_area'] . '" data-categoria="' . $fila['categoria_area'] . '" >' . $fila['nombre'] . '</option>';
							}
					?>
				</select>
				
				<input type="hidden" id="categoriaArea" name="categoriaArea" />
				
		</div><div data-linea="3">
				
			<div id="dSubOcupante"></div>
		</div>
	
	</fieldset>
		
								
	<fieldset>
		<legend>Datos de la gasolinera</legend>
		
		<div data-linea="1">
		
			<label>Nombre</label> 
					<select id="gasolinera" name="gasolinera" >
						<option value="">Gasolineras....</option>
						<?php 
							while($fila = pg_fetch_assoc($gasolineras)){
								echo '<option value="' . $fila['id_gasolinera'] . '" data-extra="' . $fila['extra'] . '" data-super="' . $fila['super'] . '" data-diesel="' . $fila['diesel'] . '" data-ecopais="' . $fila['ecopais'] . '">' . $fila['nombre'] . '</option>';					
							}
						?>
					</select>
					
		</div><div data-linea="1">
				
			<label>Tipo Combustible</label> 
				<select id="combustible" name="combustible"	>
					<option value="" selected="selected" >Tipo combustible....</option>
				</select>	
				
		</div>	
		
		<div data-linea="2">
				
			<label>Monto solicitado $</label> 
				<input id="montoSolicitado" step="0.01" name="montoSolicitado" type="number" required="required"/>		
		</div>
		
		<div data-linea="2">
				
			<label>Galones a cargar</label> 
				<input id="galonesSolicitados" name="galonesSolicitados" type="number" readonly="readonly"/>		
		</div>
		
		<div data-linea="3">
				
			<label>Fecha de despacho</label> 
				<input id="fechaDespacho" name="fechaDespacho" type="text" required="required" readonly="readonly"/>		
		</div>
		
	</fieldset>	

	<button type="submit" class="guardar">Guardar Orden</button>
	
</form>


<script type="text/javascript">

var array_responsable= <?php echo json_encode($responsable); ?>;
var placaDirEje= <?php echo json_encode($DIR_EJE); ?>;
var vehiculoDirEje = 0;
					
$("#area").change(function(event){
	$("#categoriaArea").val($('#area option:selected').attr('data-categoria'));
	$("#nuevoCombustible").attr('data-opcion', 'combosOcupante');
    $("#nuevoCombustible").attr('data-destino', 'dSubOcupante');
    abrir($("#nuevoCombustible"), event, false); //Se ejecuta ajax, busqueda de sub tipo producto
    
    $('#ocupante').html(socupante);
    $('#ocupante').removeAttr("disabled");
 });

$("#gasolinera").change(function(){

	 $('#combustible').html(null);
	 $('<option value="">Tipo combustible....</option>').appendTo('#combustible');
	 
	if ($("#gasolinera option:selected").attr("data-extra")!="0")
		$('<option value="Extra" data-precio="'+$("#gasolinera option:selected").attr("data-extra")+'">Extra</option>').appendTo('#combustible');	

	//Desactivado el 14 de octubre por solicitud de la Dra. Ana Vintimilla por motivo de austeridad
	/*if($("#gasolinera option:selected").attr("data-super")!="0")
		$('<option value="Super" data-precio="'+$("#gasolinera option:selected").attr("data-super")+'">Super</option>').appendTo('#combustible');
	*/
		
	if($("#gasolinera option:selected").attr("data-diesel")!="0") 
	    $('<option value="Diesel" data-precio="'+$("#gasolinera option:selected").attr("data-diesel")+'">Diesel</option>').appendTo('#combustible');

	if($("#gasolinera option:selected").attr("data-ecopais")!="0") 
	    $('<option value="Ecopais" data-precio="'+$("#gasolinera option:selected").attr("data-ecopais")+'">Ecopaís</option>').appendTo('#combustible');
});

$("#montoSolicitado").change(function(){
	$("#montoSolicitado").removeClass("alertaCombo");
	$("#combustible").removeClass("alertaCombo");

	if($("#combustible option:selected").val() != ''){
		if($("#montoSolicitado").val() != ''){
			if($("#vehiculo option:selected").val() != placaDirEje){
				if(($("#montoSolicitado").val() > 0) && ($("#montoSolicitado").val() <= 25)){
					$("#galonesSolicitados").val(($("#montoSolicitado").val()/($("#combustible option:selected").attr("data-precio"))).toFixed(2));
				}else if($("#montoSolicitado").val() <= 0){
					alert('No pueden generarse órdenes por un valor de $0 o menor');
					$("#montoSolicitado").addClass("alertaCombo");
					$("#montoSolicitado").val("");
				}else{
					alert('No pueden generarse órdenes por un valor mayor a $25');
					$("#montoSolicitado").addClass("alertaCombo");
					$("#montoSolicitado").val("");
				}
			}else{
				if($("#montoSolicitado").val() > 0){
					$("#galonesSolicitados").val(($("#montoSolicitado").val()/($("#combustible option:selected").attr("data-precio"))).toFixed(2));
				}else if($("#montoSolicitado").val() <= 0){
					alert('No pueden generarse órdenes por un valor de $0 o menor');
					$("#montoSolicitado").addClass("alertaCombo");
					$("#montoSolicitado").val("");
				}
			}
		}else{
			alert('Por favor ingrese un valor en el monto de combustible solicitado');
			$("#montoSolicitado").addClass("alertaCombo");
			$("#montoSolicitado").val("");
		}

	}else{
		alert('Por favor seleccione un tipo de combustible');
		$("#combustible").addClass("alertaCombo");
		$("#montoSolicitado").val("");
	}
});

$("#nuevoCombustible").submit(function(event){

	$("#nuevoCombustible").attr('data-opcion', 'guardarNuevoCombustible');
    $("#nuevoCombustible").attr('data-destino', 'detalleItem');

	event.preventDefault();

	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if($("#vehiculo").val()==""){
		error = true;
		$("#vehiculo").addClass("alertaCombo");
	}

	if($("#gasolinera").val()==""){
		error = true;
		$("#gasolinera").addClass("alertaCombo");
	}

	if($("#area").val()==""){
		error = true;
		$("#area").addClass("alertaCombo");
	}
	
	if($("#ocupante").val()==null || $("#ocupante").val()=='' || $("#ocupante").val()=="Otro"){
		error = true;
		$("#ocupante").addClass("alertaCombo");
		$("#estado").html("Debe seleccionar a un funcionario de Agrocalidad").addClass("alerta");
	}

	if($("#combustible").val()==""){
		error = true;
		$("#combustible").addClass("alertaCombo");
	}

	if($("#montoSolicitado").val()=="" || $("#montoSolicitado").val() < 1){
		error = true;
		$("#montoSolicitado").addClass("alertaCombo");
	}

	if($("#galonesSolicitados").val()=="" || $("#galonesSolicitados").val()==0){
		error = true;
		$("#galonesSolicitados").addClass("alertaCombo");
	}

	if (!error){
	
		var km = $("#vehiculo  option:selected").attr("data-kilometraje");
		var km_str = km.split(" ");  
	
		var combustible = $("#vehiculo  option:selected").attr("data-descripcion");
		var combustible_str = combustible.split(" ");
		
		if($("#kilometraje").val() >= Number(km_str[2])){
			//if($("#combustible").val()!= combustible_str[3]){
			//	$("#estado").html("El combustible seleccionado es distinto al combustible del vehiculo, por favor verificar.").addClass("alerta");	
			//}else{
				//abrir($(this),event,false);
			//}
			ejecutarJson(this);
		}else{
			$("#estado").html("El kilometraje ingresado en inferior al actual, por favor verificar.").addClass("alerta");
		}
	}
});


$("#vehiculo").change(function(){
	var vehiculo = $("#vehiculo  option:selected").attr("data-descripcion") + ' - ' + $("#vehiculo  option:selected").attr("data-kilometraje");
	$("#estado").html(vehiculo).addClass("exito");	
	$('#id_vehiculo').val($("#vehiculo  option:selected").attr("data-idVehiculo"));

	var actualKm = $("#vehiculo  option:selected").attr("data-kilometraje");
	var kmActual = actualKm.split(" ");  

	$("#kilometraje").val(Number(kmActual[2]));
	$("#kilometrajeSolicitud").val(Number(kmActual[2]));

	if($("#vehiculo option:selected").val() == placaDirEje){
		vehiculoDirEje = 1;
	}else{
		vehiculoDirEje = 0;
	}
});

$(document).ready(function(){
	distribuirLineas();
	construirValidador();

	$( "#fechaDespacho" ).datepicker({
	      changeMonth: true,
	      changeYear: true
	 });
	 
});

</script>