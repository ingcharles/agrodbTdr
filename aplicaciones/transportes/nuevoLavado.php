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
	<h1>Nuevo lavado</h1>
</header>

<div id="estado"></div>

<form id="nuevoLavado" data-rutaAplicacion="transportes" data-opcion="guardarNuevoLavado" data-destino="detalleItem" data-accionEnExito='ACTUALIZAR'>

		<input type="hidden" name="id_vehiculo" id="id_vehiculo"/>
		<input type='hidden' id='identificadorUsuarioRegistro' name='identificadorUsuarioRegistro' value="<?php echo $identificadorUsuarioRegistro;?>" />
		<input type='hidden' id='jefeTransportes' name='jefeTransportes' value="<?php echo $jefeTransportes;?>" />
		
		<fieldset id="datosVehiculo">
		<legend>Datos generales</legend>
		
		<div data-linea="1">		
			<label>Vehículo</label>	
				<select id="vehiculo" name="vehiculo" >
					<option value="">Vehículo....</option>
					<?php 
						while($fila = pg_fetch_assoc($vehiculo)){
							echo '<option value="' . $fila['placa'] . '" data-idVehiculo="'. $fila['id_vehiculo'].'" data-kilometraje="Kilometraje actual: '. $fila['kilometraje_actual'].'">' . $fila['marca'] .' '.$fila['modelo'] .' -> '.$fila['placa']. '</option>';					
						}
					?>
				</select>
				
		</div><div data-linea="1">		

			<label>Kilometraje</label> 
				<input type="number" step="1" name="kilometraje" id="kilometraje" placeholder="Ej: 12345" required="required"/>
				
		</div>
		
		
		<div data-linea="2">
		
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
		
		<fieldset id="datosResponsable">
			<legend>Datos responsable</legend>
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

	<button type="submit" class="guardar">Guardar lavado</button>
	
</form>

<script type="text/javascript">

var array_responsable= <?php echo json_encode($responsable); ?>;

$("#area").change(function(event){
	$("#categoriaArea").val($('#area option:selected').attr('data-categoria'));
	$("#nuevoLavado").attr('data-opcion', 'combosOcupante');
    $("#nuevoLavado").attr('data-destino', 'dSubOcupante');
    abrir($("#nuevoLavado"), event, false); //Se ejecuta ajax, busqueda de sub tipo producto
    
    $('#ocupante').html(socupante);
    $('#ocupante').removeAttr("disabled");
 });

$("#nuevoLavado").submit(function(event){

	$("#nuevoLavado").attr('data-opcion', 'guardarNuevoLavado');
    $("#nuevoLavado").attr('data-destino', 'detalleItem');
    
	event.preventDefault();

	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if($("#vehiculo").val()==""){
		error = true;
		$("#vehiculo").addClass("alertaCombo");
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

$("#vehiculo").change(function(){
	$("#estado").html($("#vehiculo  option:selected").attr("data-kilometraje")).addClass('exito');	
	$('#id_vehiculo').val($("#vehiculo  option:selected").attr("data-idVehiculo"));

	var actualKm = $("#vehiculo  option:selected").attr("data-kilometraje");
	var kmActual = actualKm.split(" ");  

	$("#kilometraje").val(Number(kmActual[2]));
});


$(document).ready(function(){
	distribuirLineas();
});

</script>
