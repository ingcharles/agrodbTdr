<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';
require_once '../../clases/ControladorAreas.php';
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$cv = new ControladorVehiculos();
$ca = new ControladorAreas();
$cu = new ControladorUsuarios();
$cc = new ControladorCatalogos();

//Identificador Usuario Administrador o Apoyo de Transportes
$identificadorUsuarioRegistro = $_SESSION['usuario'];

$res = $cv->abrirVehiculo($conexion, $_POST['id']);
$vehiculo= pg_fetch_assoc($res);
$area = $ca->listarAreas($conexion);
$usuario = $cu->obtenerUsuariosXarea($conexion);

while($fila = pg_fetch_assoc($usuario)){
	$responsable[]= array(identificador=>$fila['identificador'], apellido=>$fila['apellido'], nombre=>$fila['nombre'], area=>$fila['id_area']);
}

$localizacion = $cc->listarLocalizacion($conexion, 'SITIOS');
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>

<body>
<header>
	<img src='aplicaciones/general/img/encabezado.png'>
	<h1>Reasignar vehículo</h1>
</header>

<form id="reasignarVehiculo" data-rutaAplicacion="transportes" data-opcion="datosFuncionario" data-destino="datosFuncionario" data-accionEnExito="ACTUALIZAR" >
	<input type='hidden' id='identificadorUsuarioRegistro' name='identificadorUsuarioRegistro' value="<?php echo $identificadorUsuarioRegistro;?>" />
	
	<div id="estado"></div>
	<fieldset>
		<legend>Información del vehículo</legend>
		
		<div data-linea="1">
		
		<label>Placa</label>
			<?php echo $_POST['id'];?>
			<input type="hidden" id="placa" name="placa" value="<?php echo $_POST['id'];?>" />
			
		</div><div data-linea="2">
		
		<label>Marca</label> 
			<?php echo pg_fetch_result($res, 0, 'marca');?> 
			
		</div><div data-linea="2">
			
		<label>Modelo</label> 
			<?php echo pg_fetch_result($res, 0, 'modelo');?> 
			
		</div><div data-linea="3">
				
		<label>Tipo</label> 
			<?php echo pg_fetch_result($res, 0, 'tipo');?>  
			
		</div><div data-linea="3">			
	
		<label>Tipo Combustible</label>
			<?php echo pg_fetch_result($res, 0, 'combustible');?> 
		
		</div>	
	</fieldset>	
	
	<fieldset>
		<legend>Nueva localización</legend>
			<div data-linea="1">
				<label>Localización</label>
					<select id="localizacionAsignacion" name="localizacionAsignacion">
						<option value="">Seleccione....</option>
						<?php 
							while($fila = pg_fetch_assoc($localizacion)){
									//if(strstr($fila['nombre'], 'Coordinación') || strstr($fila['nombre'], 'Oficina') || strstr($fila['nombre'], 'Oficina Planta Central') || strstr($fila['nombre'], 'Laboratorios tumbaco')){
										echo '<option value="' . $fila['nombre'] . '">' . $fila['nombre'] . '</option>';
									//}
								}
						?>
						
					</select>
			</div>
		
			<!-- div id="datosFuncionario"></div-->
	</fieldset>
	
	<fieldset>
		<legend>Motivo reasignación</legend>

		<div data-linea="1">
		
		<label>Motivo</label> 
			<input type="text" id="motivo" name="motivo" data-er="^[A-Za-z0-9.,/ ]+$" />
			
		</div>
			
	</fieldset>

	<button type="submit" class="guardar">Reasignar vehículo</button> 
	
</form>
</body>
<script type="text/javascript">

var array_responsable= <?php echo json_encode($responsable); ?>;

	$(document).ready(function(){
		cargarValorDefecto("localizacion","<?php echo $vehiculo['localizacion'];?>");
		$('select[name="area"]').find('option[value="<?php echo $vehiculo['id_area'];?>"]').prop("selected","selected");
		$('<option value="<?php echo $vehiculo['identificador'];?>"><?php echo $vehiculo['apellido'].", ".$vehiculo['nombre'];?></option>').appendTo('#responsable');

		distribuirLineas();
	});
	
	/*$("#localizacionAsignacion").change(function(event){
		if($("#localizacionAsignacion").val() != ''){
			$("#reasignarVehiculo").attr('data-opcion','datosFuncionario');
			$("#reasignarVehiculo").attr('data-destino','datosFuncionario');
			abrir($("#reasignarVehiculo"),event,false); //Se ejecuta ajax, busqueda de funcionario responsable
		}else{
			alert('elija un valor');
			$("#datosFuncionario").text('');			
		}		 		
	});*/

	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}
				
	$("#reasignarVehiculo").submit(function(event){
		$("#reasignarVehiculo").attr('data-opcion','reasignarVehiculo');
		$("#reasignarVehiculo").attr('data-destino','detalleItem');
		
		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("#localizacionAsignacion").val()==""){
			error = true;
			$("#localizacionAsignacion").addClass("alertaCombo");
		}

		/*if($("#identificadorResponsable").val()==""){
			error = true;
			$("#datosFuncionario").addClass("alertaCombo");
		}*/	

		if(!$.trim($("#motivo").val()) || !esCampoValido("#motivo")){
			error = true;
			$("#motivo").addClass("alertaCombo");
		}	
		
		if (!error){
			ejecutarJson($(this));
		}else{
			$("#estado").html('Por favor revise el formato de la información ingresada').addClass('alerta');
		}
		
	});

</script>
</html>
