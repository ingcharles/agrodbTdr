<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAreas.php';
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorVehiculos.php';
require_once '../../clases/ControladorEmpleados.php';

//Vehiculo Direccion Ejecutiva
//$DIR_EJE = 'PEI-1418';

$conexion = new Conexion();
$cv = new controladorVehiculos();
$ca = new ControladorAreas();
$cu = new ControladorUsuarios();
$ce = new ControladorEmpleados();

$res = $cv->abrirCombustible($conexion, $_POST['id']);
$combustible = pg_fetch_assoc($res);

//Identificador Usuario Administrador o Apoyo de Transportes
if($_SESSION['usuario'] != '' && $_SESSION['usuario']!=$combustible['identificador_registro']){
	$identificadorUsuarioRegistro = $_SESSION['usuario'];
}else if($_SESSION['usuario'] != '' && $_SESSION['usuario']==$combustible['identificador_registro']){
	$identificadorUsuarioRegistro = $combustible['identificador_registro'];
}else{
	$identificadorUsuarioRegistro = '';
}

$per = $ce->obtenerDatosPersonales($conexion, $combustible['conductor']);
$conductor = pg_fetch_assoc($per);

$gasolineras = $cv->abrirDatosGasolineras($conexion,$_SESSION['nombreLocalizacion']);
$area = $ca->obtenerAreasDireccionesTecnicas($conexion, "('Planta Central','Oficina Técnica')", "(3,4,1)");
$usuario = $cu->obtenerUsuariosXarea($conexion);

while($fila = pg_fetch_assoc($usuario)){
	$responsable[]= array(identificador=>$fila['identificador'], apellido=>$fila['apellido'], nombre=>$fila['nombre'], area=>$fila['id_area']);
}

$administrador = $cv->buscarAdministrador($conexion, $_SESSION['idLocalizacion']);

$jefeTransportes = pg_fetch_result($administrador, 0, 'nombres_completos');

$rutaArchivo = $combustible['ruta_archivo'];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>

	<header>
		<img src='aplicaciones/general/img/encabezado.png'>
		<h1>Orden de Combustible</h1><div class="numero"><?php echo ' N° '. $combustible['id_combustible'];?></div>
	</header>

	<div id="estado"></div>
	
<form id="datosCombustible" data-rutaAplicacion="transportes" data-opcion="actualizarCombustible" data-accionEnExito="ACTUALIZAR">
	<input type='hidden' id='identificadorUsuarioRegistro' name='identificadorUsuarioRegistro' value="<?php echo $identificadorUsuarioRegistro;?>" />
	<input type='hidden' id='jefeTransportes' name='jefeTransportes' value="<?php echo $jefeTransportes;?>" />
	<input type="hidden" id="placaVehiculo" name="placaVehiculo" value="<?php echo $combustible['placa'];?>" />
	
	<p>
		<button id="modificar" type="button" class="editar">Modificar</button>
		<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
	</p>

	<div id="reporte">
		<!-- insertar div del jasper -->
		<?php 
			if($combustible['estado'] == 1){
					echo '<embed id="visor" src='.$rutaArchivo.' width="540" height="350">';
			}
		?>
	</div>
	
	<div id="informacion">
<table class="soloImpresion">
	<tr><td>
		
	<fieldset>
		<legend>Información del vehículo</legend>
			
			<div data-linea="0">
				
				<label>ID: </label>
					<?php echo $combustible['id_combustible'];?>
			
			</div><div data-linea="1">
				
				<label>Vehículo: </label>
					<?php echo $combustible['marca'].' - '.$combustible['modelo'].' - '.$combustible['tipo'].'';?>
			
			</div><div data-linea="2">
			
				<label>Placa: </label>
					<?php echo $combustible['placa'];?>
					
			</div><div data-linea="2">
			
				<label>N° motor: </label>
					<?php echo $combustible['numero_motor'];?>
					
			</div><div data-linea="3">
			
				<label>N° chasis: </label>
					<?php echo $combustible['numero_chasis'];?>
					
			</div><div data-linea="3">
				
				<label>Fecha solicitud: </label>
					<?php echo date('j/n/Y',strtotime($combustible['fecha_solicitud']));?>
			
			</div><div data-linea="4">
			
				<label>Oficina: </label>
					<?php echo $combustible['localizacion'];?>
					
			</div>
				
	</fieldset>
	
	</td><td>
	<fieldset>
		<legend>Detalle Gasolinera</legend>
		
		<input type="hidden" name="id_combustible" value="<?php echo $combustible['id_combustible'];?>"/>
		
		<div data-linea="1">
		
			<label>Gasolinera</label> 
				<select id="gasolinera" name="gasolinera" disabled="disabled">
					<option value="" data-extra="0" data-super="0" data-diesel="0" >Gasolinera....</option>
					<?php 
						while($fila = pg_fetch_assoc($gasolineras)){
							echo '<option value="' . $fila['id_gasolinera'] . '" data-extra="' . $fila['extra'] . '" data-super="' . $fila['super'] . '" data-diesel="' . $fila['diesel'] . '" data-ecopais="' . $fila['ecopais'] . '">' . $fila['nombre'] . '</option>';					
						}
					?>
				</select>
				
		</div><div data-linea="1">
			
		<label>Tipo Combustible</label> 
				<select id="combustible" name="combustible"	disabled="disabled">
				</select>
			
		</div>
		
		
		<div data-linea="2">
				<label>Fecha de Despacho:</label>
					<input id="fechaDespacho" name="fechaDespacho" type="text" value="<?php echo date('j/n/Y',strtotime($combustible['fecha_despacho']));?>" disabled="disabled" required="required"  readonly="readonly"/>
		</div>
		
		<div data-linea="2">
			<label>Kilometraje</label> 
				<input type="number" step="1" id="kilometraje" name="kilometraje" value="<?php echo $combustible['kilometraje'];?>" disabled="disabled" required="required"/>
		</div>
		
		<div data-linea="3">
			<div id="divObservacion">	
				<label>Observación:</label>
					<input id="observacion" name="observacion" type="text" />
			</div>
		</div>		
		
		<div data-linea="4">
				
			<label>Monto solicitado $</label> 
				<input id="montoSolicitado" step="0.01" name="montoSolicitado" type="number" required="required" value="<?php echo $combustible['monto_solicitado'];?>" disabled="disabled"/>		
		</div>
		
		<div data-linea="4">
				
			<label>Galones a cargar</label> 
				<input id="galonesSolicitados" name="galonesSolicitados" type="number" readonly="readonly" value="<?php echo $combustible['galones_solicitados'];?>" disabled="disabled"/>		
		</div>	
	</fieldset>	
	</td>
	<td>

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
				
		</div>
		
		<div data-linea="4">	
			<div id="dSubOcupante">
				<label>Conductor</label> 
					<select id="ocupante" name="ocupante" disabled="disabled">
					</select>
			</div>
		
		</div>	
	
	</fieldset>
		
		
	</td>
	</tr>
</table>
</div>
</form>


<form id="imprimirCombustible" data-rutaAplicacion="transportes" data-opcion="imprimirCombustible" data-accionEnExito="ACTUALIZAR" >
	<input type='hidden' id='identificadorUsuarioRegistro' name='identificadorUsuarioRegistro' value="<?php echo $identificadorUsuarioRegistro;?>" />
	<input type="hidden" name="id_combustible" value="<?php echo $combustible['id_combustible'];?>"/>
	
	<p class="nota">Por favor presione el botón "Finalizar etapa".</p>   
	
	<button id="imprimir" type="submit" class="imprimir">Finalizar etapa</button>
</form>

<fieldset id="subirComprobante">
	<legend>Subir comprobante</legend>
	<form id="subirArchivo" action="aplicaciones/transportes/subirArchivo.php" method="post" enctype="multipart/form-data" target="ventanaEmergente" onsubmit="window.open('', this.target, 'width=250,height=250,resizable,scrollbars=yes');">
		<input type="file" name="archivo" id="archivo" accept="application/pdf" /> 
		<input type="hidden" name="id" value="<?php echo $combustible['id_combustible'];?>" /> 
		<input type="hidden" name="placa" value="<?php echo $mantenimiento['placa'];?>" />
		
		<button type="submit" name="boton" value="comprobanteGasolinera" disabled="disabled" class="adjunto">Subir Archivo</button>
	</form>
	<iframe name="ventanaEmergente" class="ventanaEmergente"></iframe>
</fieldset>

<form id="datosDetalleCombustible" data-rutaAplicacion="transportes" data-opcion="finalizarCombustible" data-accionEnExito="ACTUALIZAR" >
	<input type='hidden' id='identificadorUsuarioRegistro' name='identificadorUsuarioRegistro' value="<?php echo $identificadorUsuarioRegistro;?>" />
	<input name="id_combustible"  type="hidden" value="<?php echo $combustible['id_combustible'] ?>" />
	<input name="id_gasolinera"  type="hidden" value="<?php echo $combustible['gasolinera'] ?>" />
	<input name="tipo_combustible"  type="hidden" value="<?php echo $combustible['tipo_combustible'] ?>" />
	<input id="monto_solicitado" name="monto_solicitado"  type="hidden" value="<?php echo $combustible['monto_solicitado'] ?>" />
	<input name="galones_solicitados"  type="hidden" value="<?php echo $combustible['galones_solicitados'] ?>" />
			
			
	<fieldset>
		
		<legend>Liquidar orden de combustible</legend>
		
	<div data-linea="1">
				
		<label>Fecha de Liquidación:</label>
			<input id="fechaLiquidacion" name="fechaLiquidacion" type="text" required="required"  readonly="readonly"/>
			
	</div><div data-linea="1">
			
		<label>Valor Liquidado:</label>
			<input name="valorLiquidado" id="valorLiquidado" type="text" required="required"/>
			
	</div>
	
	<div id="razonCambioMonto" data-linea="2">
			
		<label>Razón de cambio en el monto:</label>
			<input name="razonCambio" id="razonCambio" type="text" />
			
	</div>
			
	</fieldset>
	
		<button type="submit" class="guardar">Guardar orden de combustible</button>
	
		
</form>


</body>

<script type="text/javascript">
var placaDirEje= <?php echo json_encode($DIR_EJE); ?>;
var vehiculoDirEje = 0;

	$(document).ready(function(){

		distribuirLineas();
		
		$( "#fechaLiquidacion" ).datepicker({
			      changeMonth: true,
			      changeYear: true
		});

		$( "#fechaDespacho" ).datepicker({
		      changeMonth: true,
		      changeYear: true
		 });

		$("#razonCambioMonto").hide();

		$("#reporte").show();
		$("#informacion").hide();

		$('<option value="<?php echo $conductor['identificador'];?>"><?php echo $conductor['apellido'].", ".$conductor['nombre'];?></option>').appendTo('#ocupante');
		$("#divArea").hide();
		$("#finalizar").hide();
		
	});

	
	var array_responsable= <?php echo json_encode($responsable); ?>;

	$("#area").change(function(event){
		$("#categoriaArea").val($('#area option:selected').attr('data-categoria'));
		$("#datosCombustible").attr('data-opcion', 'combosOcupante');
	    $("#datosCombustible").attr('data-destino', 'dSubOcupante');
	    abrir($("#datosCombustible"), event, false); //Se ejecuta ajax, busqueda de sub tipo producto
	    
	    $('#ocupante').html(socupante);
	    $('#ocupante').removeAttr("disabled");
	 });

	$("#modificar").click(function(){
		$("#reporte").hide();
		$("#informacion").show();
		
		$("input").removeAttr("disabled");
		$("select").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$("#imprimir").attr("disabled","disabled");
		$("#divarea").fadeIn();
		$(this).attr("disabled","disabled");
	});


	$(document).ready(function(){
		cargarValorDefecto("gasolinera","<?php echo $combustible['gasolinera'];?>");
		$('<option value="<?php echo $combustible['tipo_combustible'];?>" data-precio="'+$("#gasolinera option:selected").attr("data-<?php echo strtolower($combustible['tipo_combustible']);?>")+'"><?php echo $combustible['tipo_combustible'];?></option>').appendTo('#combustible');
		$('<option value="<?php echo $combustible['conductor'];?>"><?php echo $combustible['apellido'].", ".$combustible['nombreconductor'];?></option>').appendTo('#responsable');
		$("#divarea").hide();
		$("#divObservacion").hide();
		
		
		if (<?php echo $combustible['estado'];?>=="2"){
			$("#datosCombustible").hide();
			$("#imprimirCombustible").hide();
		}

		if (<?php echo $combustible['estado'];?>=="1"){
			$("#datosDetalleCombustible").hide();
			$("#subirComprobante").hide();
		}
		
	});

	$("#kilometraje").change(function(){
		$("#divObservacion").fadeIn();
		$("#observacion").attr("required", "true");
	});
	

	$("#datosCombustible").submit(function(event){

		$("#datosCombustible").attr('data-opcion', 'actualizarCombustible');
	    $("#datosCombustible").attr('data-destino', 'detalleItem');
	    
		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("#kilometraje").val()==""){
			error = true;
			$("#kilometraje").addClass("alertaCombo");
		}
		
		if($("#gasolinera").val()==""){
			error = true;
			$("#gasolinera").addClass("alertaCombo");
		}

		if($("#combustible").val()==""){
			error = true;
			$("#combustible").addClass("alertaCombo");
		}

		if($("#ocupante").val()==null || $("#ocupante").val()=='' || $("#ocupante").val()=="Otro"){
			error = true;
			$("#ocupante").addClass("alertaCombo");
			$("#estado").html("Debe seleccionar a un funcionario de Agrocalidad").addClass("alerta");
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
			ejecutarJson($(this));
		}
	});

	$("#datosDetalleCombustible").submit(function(event){

			event.preventDefault();
	
			$(".alertaCombo").removeClass("alertaCombo");
			var error = false;
	
			if(Number($("#valorLiquidado").val())<="0"){
				error = true;
				$("#valorLiquidado").addClass("alertaCombo");
			}

			if(Number($("#valorLiquidado").val()) != Number($("#monto_solicitado").val())){
				if($("#razonCambio").val() ==""){
					error = true;
					$("#razonCambio").addClass("alertaCombo");
				}
			}
				
			if (!error)
			ejecutarJson($(this));
		});	
	
	/*$("#imprimir").click(function(){
		window.print();
	});*/

	$("#imprimirCombustible").submit(function(event){
		event.preventDefault();
		ejecutarJson($(this));
		});	

	$("#gasolinera").click(function(){

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
		    $('<option value="Ecopais" data-precio="'+$("#gasolinera option:selected").attr("data-ecopais")+'">Ecopais</option>').appendTo('#combustible');
		   
	});

	$("#gasolinera").click(function(){
		$("#montoSolicitado").val("");
		$("#galonesSolicitados").val("");
	});

	$("#montoSolicitado").change(function(){
		$("#montoSolicitado").removeClass("alertaCombo");
		$("#combustible").removeClass("alertaCombo");

		if($("#combustible option:selected").val() != ''){
			if($("#montoSolicitado").val() != ''){
				/*if($("#placaVehiculo").val() != placaDirEje){
					if(($("#montoSolicitado").val() > 0) && ($("#montoSolicitado").val() <= 25)){*/
						$("#galonesSolicitados").val(($("#montoSolicitado").val()/($("#combustible option:selected").attr("data-precio"))).toFixed(2));
				/*	}else if($("#montoSolicitado").val() <= 0){
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
				}*/
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

	$("#valorLiquidado").change(function(){
		if ($("#valorLiquidado").val() != $("#monto_solicitado").val()){
			alert('Se está realizando la liquidación de la orden por un monto diferente al registrado, por favor detalle la razón del mismo.');
			$("#razonCambioMonto").show();
		}else{
			$("#razonCambioMonto").hide();
		}
	 });

	$("#archivo").click(function(){
		$("#subirComprobante button").removeAttr("disabled");
	});
    
</script>	    

</html>
