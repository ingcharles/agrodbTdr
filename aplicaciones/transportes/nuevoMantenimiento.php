<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAreas.php';
require_once '../../clases/ControladorVehiculos.php';
require_once '../../clases/ControladorUsuarios.php';

$conexion = new Conexion();
$ca = new ControladorAreas();
$cv = new ControladorVehiculos();
$cu = new ControladorUsuarios();

//Identificador Usuario Administrador o Apoyo de Transportes
$identificadorUsuarioRegistro = $_SESSION['usuario'];

$talleres = $cv->abrirDatosTalleres($conexion, $_SESSION['nombreLocalizacion']);
$vehiculo = $cv->obtenerDatosVehiculos($conexion, $_SESSION['nombreLocalizacion'],"Otro");
$area = $ca->obtenerAreasDireccionesTecnicas($conexion, "('Planta Central','Oficina Técnica')", "(3,4,1)");
$usuario = $cu->obtenerUsuariosXarea($conexion);


while($fila = pg_fetch_assoc($usuario)){
	$responsable[]= array(identificador=>$fila['identificador'], apellido=>$fila['apellido'], nombre=>$fila['nombre'], area=>$fila['id_area']);
}

$administrador = $cv->buscarAdministrador($conexion, $_SESSION['idLocalizacion']);

$jefeTransportes = pg_fetch_result($administrador, 0, 'nombres_completos');
?>

<header>
	<h1>Nuevo Mantenimiento</h1>
</header>

<div id="estado"></div><br/>

<form id="nuevoMantenimiento" data-rutaAplicacion="transportes" data-opcion="guardarNuevoMantenimiento" data-destino="detalleItem" data-accionEnExito='ACTUALIZAR'>

  	<input type='hidden' id='identificadorUsuarioRegistro' name='identificadorUsuarioRegistro' value="<?php echo $identificadorUsuarioRegistro;?>" />
  	<input type="hidden" name="id_vehiculo" id="id_vehiculo"/>
  	<input type='hidden' id='jefeTransportes' name='jefeTransportes' value="<?php echo $jefeTransportes;?>" />

	<fieldset>
		<legend>Orden de Mantenimiento</legend>
		
		<div data-linea="1">
		
			<label>Tipo Mantenimiento</label> 
				<select id="tipo" name="tipo" >
					<option value="" selected="selected">Tipo....</option>
					<option value="Preventivo" >Preventivo</option>
					<option value="Correctivo" >Correctivo</option>
				</select>
		
		</div><div data-linea="2">
						
			 <label>Descripción</label> 
				<input type="text" name="motivo" id="motivo" placeholder="Ej: Cambio de aceite" data-er="^[A-Za-z0-9.,/ ]+$"/> 
				
		</div>
		
	</fieldset>
		
	<fieldset id="datosVehiculo">
		<legend>Datos generales</legend>
		
		
		<div data-linea="1">	
		
			<label>Vehículo</label>	
				<select id="vehiculo" name="vehiculo" >
					<option value="">Vehículo....</option>
					<?php 
						while($fila = pg_fetch_assoc($vehiculo)){
							echo '<option value="' . $fila['placa'] . '" data-idVehiculo="'. $fila['id_vehiculo'].'" data-kilometraje="Kilometraje actual: '. $fila['kilometraje_actual'].'">' . $fila['marca'] .' '.$fila['modelo'] .' -> '.$fila['placa']. '</option>';					
						  //echo '<option value="' . $fila['placa'] . '" data-kilometraje="Kilometraje actual: '. $fila['kilometraje_actual'].'">' . $fila['marca'] .' '.$fila['modelo'] .' -> '.$fila['placa'].' ( '.($fila['kilometraje_actual']>='4600'?'<div id="">Mantenimiento</div>':'<div id="">No necesita amentenimiento</div>'</option>';
						}
					?>
				</select>
				
				
			</div><div data-linea="2">	

				<label>Kilometraje</label> 
					<input type="text"  name="kilometraje" id="kilometraje" placeholder="Ej: 10.34" data-er="^[0-9]+(\.[0-9]{1,2})?$"/>
				
			</div><div data-linea="2">	
				<label>Taller</label>
					<select id="taller" name="taller" >
						<option value="">Taller....</option>
						<?php 
							while($fila = pg_fetch_assoc($talleres)){
								echo '<option value="' . $fila['id_taller'] . '">' . $fila['nombretaller'] . '</option>';					
							}
						?>
					</select>
				
			</div>
			
	</fieldset>
	
	<fieldset>
		<legend>Conductor Responsable</legend>
			
			<div data-linea="3">	
				
				<label>Área pertenece</label>
					<select id="area" name="area" >
						<option value="">Áreas....</option>
						<?php 
							while($fila = pg_fetch_assoc($area)){
									echo '<option value="' . $fila['id_area'] . '" data-categoria="' . $fila['categoria_area'] . '" >' . $fila['nombre'] . '</option>';
								}
						?>
					</select>
					
					<input type="hidden" id="categoriaArea" name="categoriaArea" />
			</div>
			
		<div data-linea="3">	
			<div id="dSubOcupante"></div>
		
		</div>			
				 
	</fieldset>
	
	<button type="submit" class="guardar">Guardar mantenimiento</button>
	
</form>

<script type="text/javascript">

var array_responsable= <?php echo json_encode($responsable); ?>;

$("#area").change(function(event){
	$("#categoriaArea").val($('#area option:selected').attr('data-categoria'));
	$("#nuevoMantenimiento").attr('data-opcion', 'combosOcupante');
    $("#nuevoMantenimiento").attr('data-destino', 'dSubOcupante');
    abrir($("#nuevoMantenimiento"), event, false); //Se ejecuta ajax, busqueda de sub tipo producto
    
    $('#ocupante').html(socupante);
    $('#ocupante').removeAttr("disabled");
 });

$("#nuevoMantenimiento").submit(function(event){
	$("#nuevoMantenimiento").attr('data-opcion', 'guardarNuevoMantenimiento');
    $("#nuevoMantenimiento").attr('data-destino', 'detalleItem');
    
	event.preventDefault();

	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if($("#tipo").val()==""){
		error = true;
		$("#tipo").addClass("alertaCombo");
	}

	if(!$.trim($("#motivo").val()) || !esCampoValido("#motivo")){
		error = true;
		$("#motivo").addClass("alertaCombo");
	}

	if($("#vehiculo").val()==""){
		error = true;
		$("#vehiculo").addClass("alertaCombo");
	}

	if($("#kilometraje").val()==""|| !esCampoValido("#kilometraje")){
		error = true;
		$("#kilometraje").addClass("alertaCombo");
	}

	if($("#taller").val()==""){
		error = true;
		$("#taller").addClass("alertaCombo");
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

	if (!error){

		var km = $("#vehiculo  option:selected").attr("data-kilometraje");
		var km_str = km.split(" ");  

		if($("#kilometraje").val() >= Number(km_str[2])){
			//abrir($(this),event,false);
			ejecutarJson(this);
		}else{
			$("#estado").html("El kilometraje ingresado es inferior al actual, por favor verificar.").addClass("alerta");
		}
	}
	
		
});

	function esCampoValido(elemento){
	  var patron = new RegExp($(elemento).attr("data-er"),"g");
	  return patron.test($(elemento).val());
	 }

$("#vehiculo").change(function(){
	$("#estado").html($("#vehiculo  option:selected").attr("data-kilometraje")).addClass('exito');	
	$('#id_vehiculo').val($("#vehiculo  option:selected").attr("data-idVehiculo"));

	var actualKm = $("#vehiculo  option:selected").attr("data-kilometraje");
	var kmActual = actualKm.split(" ");
	
	$('#kilometraje').val(kmActual[2]);
});

$(document).ready(function(){
	distribuirLineas();
});


$("#tipo").change(function(){
	$("#estado").html("Recuerde que no debe realizar un mantenimiento de 5000kms, cambios de aceite, en una orden de mantenimiento correctivo").addClass('exito');	
});

</script>
