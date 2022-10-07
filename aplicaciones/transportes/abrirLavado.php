<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAreas.php';
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorVehiculos.php';
require_once '../../clases/ControladorEmpleados.php';

$conexion = new Conexion();
$cv = new controladorVehiculos();
$ca = new ControladorAreas();
$cu = new ControladorUsuarios();
$ce = new ControladorEmpleados();

$res = $cv->abrirMantenimiento($conexion, $_POST['id']);
$mantenimiento = pg_fetch_assoc($res);

//Identificador Usuario Administrador o Apoyo de Transportes
if($_SESSION['usuario'] != '' && $_SESSION['usuario']!=$mantenimiento['identificador_registro']){
	$identificadorUsuarioRegistro = $_SESSION['usuario'];
}else if($_SESSION['usuario'] != '' && $_SESSION['usuario']==$mantenimiento['identificador_registro']){
	$identificadorUsuarioRegistro = $mantenimiento['identificador_registro'];
}else{
	$identificadorUsuarioRegistro = '';
}

$per = $ce->obtenerDatosPersonales($conexion, $mantenimiento['conductor']);
$conductor = pg_fetch_assoc($per);

$talleres = $cv->abrirDatosTalleres($conexion, $_SESSION['nombreLocalizacion']);

$area = $ca->obtenerAreasDireccionesTecnicas($conexion, "('Planta Central','Oficina Técnica')", "(3,4,1)");
$usuario = $cu->obtenerUsuariosXarea($conexion);


while($fila = pg_fetch_assoc($usuario)){
	$responsable[]= array(identificador=>$fila['identificador'], apellido=>$fila['apellido'], nombre=>$fila['nombre'], area=>$fila['id_area']);
}

$administrador = $cv->buscarAdministrador($conexion, $_SESSION['idLocalizacion']);

$jefeTransportes = pg_fetch_result($administrador, 0, 'nombres_completos');

$rutaArchivo = $mantenimiento['ruta_archivo'];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>

<header>
	<img src='aplicaciones/general/img/encabezado.png'>
	<h1>Orden de lavado</h1><div class="numero"><?php echo ' N° '.$mantenimiento['id_mantenimiento'];?></div>
</header>



<div id="estado"></div>

<form id="datosLavado" data-rutaAplicacion="transportes" data-opcion="actualizarLavada" data-accionEnExito="ACTUALIZAR" >
	<input type='hidden' id='identificadorUsuarioRegistro' name='identificadorUsuarioRegistro' value="<?php echo $identificadorUsuarioRegistro;?>" />
	<input type='hidden' id='jefeTransportes' name='jefeTransportes' value="<?php echo $jefeTransportes;?>" />
	
	<p>
		<button id="modificar" type="button" class="editar">Modificar</button>
		<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
	</p>
	
	<div id="reporte">
		<!-- insertar div del jasper -->
		<?php 
			if($mantenimiento['estado'] == 1){
					echo '<embed id="visor" src='.$rutaArchivo.' width="540" height="350">';
			}
		?>
	</div>
	
	<div id="informacion">
	<fieldset>
		<legend>Información del vehículo</legend>
			
			<div data-linea="0">
			
				<label>ID: </label>
					<?php echo $mantenimiento['id_mantenimiento'];?>
					
			</div><div data-linea="1">
			
				<label>Vehículo: </label>
					<?php echo $mantenimiento['marca'].' - '.$mantenimiento['modelo'].' - '.$mantenimiento['tipo'].'';?>
			
			</div><div data-linea="2">
			
				<label>Placa: </label>
					<?php echo $mantenimiento['placa'];?>
			
			</div><div data-linea="2">
			
				<label>N° motor: </label>
					<?php echo $mantenimiento['numero_motor'];?>
			
			</div><div data-linea="3">
			
				<label>N° chasis: </label>
					<?php echo $mantenimiento['numero_chasis'];?>
			
			</div><div data-linea="3">
			
				<label>Fecha solicitud: </label>
					<?php echo date('j/n/Y (G:i)',strtotime($mantenimiento['fecha_solicitud']));?>
			
			</div><div data-linea="5">
			
				<label>Oficina: </label>
					<?php echo $mantenimiento['localizacion'];?>
			
			</div> 
			
				
	</fieldset>

	<fieldset>
		<legend>Detalle</legend>
		
		<input type="hidden" name="id_mantenimiento" value="<?php echo $mantenimiento['id_mantenimiento'];?>"/>
		
		<div data-linea="1">
		
			<label>Motivo</label>
				<input type="text" id="motivo" name="motivo" value="<?php echo $mantenimiento['motivo'];?>" disabled="disabled"  required="required"/>
			
			<div id="divMotivo">
				<?php echo $mantenimiento['motivo'];?>
			</div>
			
		</div><div data-linea="1">
			
		<label>Taller</label> 
			<select id="taller" name="taller" disabled="disabled">
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
							<legend>Datos del conductor</legend>
	
							<div id="divArea">
	
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
									
								<div data-linea="4">	
									<div id="dSubOcupante">
										<label>Conductor</label> 
											<select id="ocupante" name="ocupante" disabled="disabled">
											</select>
									</div>
								
								</div>	
	
							</div>
							
							<div data-linea="2">
								 
									
							</div>
							
						</fieldset>

		
	</div>

</form>

<form id="imprimirLavado" data-rutaAplicacion="transportes" data-opcion="imprimirMantenimiento" data-accionEnExito="ACTUALIZAR" >
		<input type='hidden' id='identificadorUsuarioRegistro' name='identificadorUsuarioRegistro' value="<?php echo $identificadorUsuarioRegistro;?>" />
		<input type="hidden" name="id" value="<?php echo $mantenimiento['id_mantenimiento'];?>"/>
		<input type="hidden" name="conductor" value="<?php echo $mantenimiento['conductor'];?>"/>
		
		<p class="nota">Por favor presione el botón "Finalizar etapa".</p>   
	
		<button id="imprimir" type="submit" class="imprimir">Finalizar etapa</button>
</form>





</body>

<script type="text/javascript">

				
	var array_responsable= <?php echo json_encode($responsable); ?>;

	$("#area").change(function(event){
		$("#categoriaArea").val($('#area option:selected').attr('data-categoria'));
		$('#lResponsable').hide();
		$('#responsable').hide();
		
		$("#datosLavado").attr('data-opcion', 'combosOcupante');
	    $("#datosLavado").attr('data-destino', 'dSubOcupante');
	    abrir($("#datosLavado"), event, false); //Se ejecuta ajax, busqueda de sub tipo producto
	    
	    $('#ocupante').html(socupante);
	    $('#ocupante').removeAttr("disabled");
	 });

	$("#modificar").click(function(){
		$("#reporte").hide();
		$("#informacion").show();
		
		$("input").removeAttr("disabled");
		$("select").removeAttr("disabled");
		$("textarea").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$("#divArea").fadeIn();
		$(this).attr("disabled","disabled");
		$("#divMotivo").hide();
		$("#motivo").fadeIn();
		$("#imprimir").attr("disabled","disabled");
	});

	
	$(document).ready(function(){

		distribuirLineas();
		
		cargarValorDefecto("taller","<?php echo $mantenimiento['taller'];?>");
		$('<option value="<?php echo $conductor['identificador'];?>"><?php echo $conductor['apellido'].", ".$conductor['nombre'];?></option>').appendTo('#ocupante');
		$("#divArea").hide();
		$("#motivo").hide();
		
		
		if (<?php echo $mantenimiento['estado'];?>=="2"){
			$("#datosLavado").hide();
			$("#imprimirLavado").hide();
			$("#detalleItem").html('<div class="mensajeInicial">Seleccione una o varias ordenes de lavado y a continuación presione el boton liquidar.</div>');
			
		}

		if (<?php echo $mantenimiento['estado'];?>=="1"){
			$("#datosLiquidacion").hide();
			$("#subirFactura").hide();
		}

		$("#reporte").show();
		$("#informacion").hide();
	});

	$("#datosLavado").submit(function(event){

		$("#datosLavado").attr('data-opcion', 'actualizarLavada');
	    $("#datosLavado").attr('data-destino', 'detalleItem');
	    
		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("#taller").val()==""){
			error = true;
			$("#taller").addClass("alertaCombo");
		}

		if($("#ocupante").val()==null || $("#ocupante").val()=='' || $("#ocupante").val()=="Otro"){
			error = true;
			$("#ocupante").addClass("alertaCombo");
			$("#estado").html("Debe seleccionar a un funcionario de Agrocalidad").addClass("alerta");
		}

		if(!error){
			ejecutarJson($(this));
		}
	});

	$("#datosLiquidacion").submit(function(event){
		
		if($("#kilometraje").val() > Number($("#km_Actual").val())){
			event.preventDefault();
			ejecutarJson($(this));
		}else{
			event.preventDefault();
			$("#estado").html("El kilometraje ingresado en inferior al actual, por favor verifique y vuelva a intentar.").addClass("alerta");
		}
		
	});

	$("#archivo").click(function(){
		$("#subirArchivo button").removeAttr("disabled");});

	/*$("#imprimir").click(function(){
		window.print();
	});*/

	$("#imprimirLavado").submit(function(event){ 
		event.preventDefault();
		ejecutarJson($(this));
	});

	
</script>

</html>
