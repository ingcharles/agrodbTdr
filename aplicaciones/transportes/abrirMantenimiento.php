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

$qVehiculo = $cv -> obtenerEstadoVehiculo($conexion, $mantenimiento['placa']);
$vehiculo = pg_fetch_assoc($qVehiculo);

if($mantenimiento['orden_trabajo']!= ''){
	$ordenTrabajo='1';
}else{
	$ordenTrabajo='0';
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
		<h1>Orden de mantenimiento</h1>
		<div class="numero">
			<?php echo ' N° '.$mantenimiento['id_mantenimiento'];?>
		</div>
	</header>



	<div id="estado"></div>

	<form id="datosMantenimiento" data-rutaAplicacion="transportes" data-opcion="actualizarMantenimiento" data-accionEnExito="ACTUALIZAR">

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
			<table class="soloImpresion">
				<tr>
					<td>
	
						<fieldset>
							<legend>Información del vehículo</legend>
	
							<div data-linea="0">
				
							<label>ID: </label>
								<?php echo $mantenimiento['id_mantenimiento'];?>
								
							</div><div data-linea="1">
	
								<label>Vehículo: </label>
								<?php echo $mantenimiento['marca'].' - '.$mantenimiento['modelo'].' - '.$mantenimiento['tipo'].'';?>
	
							</div>
							<div data-linea="2">
	
								<label>Placa: </label>
								<?php echo $mantenimiento['placa'];?>
	
							</div>
							<div data-linea="2">
	
								<label>Oficina: </label>
								<?php echo $mantenimiento['localizacion'];?>
	
							</div>
							<div data-linea="3">
	
								<label>Número motor: </label>
								<?php echo $mantenimiento['numero_motor'];?>
	
							</div>
							<div data-linea="3">
	
								<label>Número chasis: </label>
								<?php echo $mantenimiento['numero_chasis'];?>
	
							</div>
							<div data-linea="4">
	
								<label>Fecha solicitud: </label>
								<?php echo date('j/n/Y (G:i)',strtotime($mantenimiento['fecha_solicitud']));?>
	
							</div>
	
						</fieldset>
					</td>
					<td>
						<fieldset>
							<legend>Detalle</legend>
	
							<input type="hidden" name="id_mantenimiento"
								value="<?php echo $mantenimiento['id_mantenimiento'];?>" /> <label
								id="motivo">Motivo</label>
							<div data-linea="1">
								<textarea id="motivo" name="motivo"  placeholder="Ej: Motivo mantenimiento..." rows="4" disabled="disabled"><?php echo $mantenimiento['motivo'];?></textarea>
							</div>
							<div data-linea="2">
	
								<label>Taller</label> <select id="taller" name="taller"
									disabled="disabled">
									<option value="">Taller....</option>
									<?php 
									while($fila = pg_fetch_assoc($talleres)){
							echo '<option value="' . $fila['id_taller'] . '">' . $fila['nombretaller'] . '</option>';
						}
						?>
								</select>
	
							</div>
	
						</fieldset>
					</td>
					<td>
	
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
	
	
					</td>
				</tr>
			</table>
		</div>

	</form>

	<form id="imprimirMantenimiento" data-rutaAplicacion="transportes" data-opcion="imprimirMantenimiento" data-accionEnExito="ACTUALIZAR">
		<input type="hidden" name="id" value="<?php echo $mantenimiento['id_mantenimiento'];?>" /> 
		<input type="hidden" name="conductor" value="<?php echo $mantenimiento['conductor'];?>" />
		<input type='hidden' id='identificadorUsuarioRegistro' name='identificadorUsuarioRegistro' value="<?php echo $identificadorUsuarioRegistro;?>" />

		<p class="nota">Por favor presione el botón "Finalizar etapa".</p>   		
		
		<button id="imprimir" type="submit" class="imprimir">Finalizar etapa</button>

		<table class="firmas">

			<caption>Firmas</caption>

			<tr>
				<td><?php echo $conductor['nombres_completos'];?><br /> Responsable
				</td>

				<td><?php echo pg_fetch_result($administrador, 0, 'nombres_completos');?><br />
					Jefe de transportes</td>

				<td>Taller mecanico</td>

			</tr>
		</table>

	</form>



		<fieldset id="subirFactura">
			<legend>Subir factura</legend>
			
			<form id="subirArchivo" action="aplicaciones/transportes/subirArchivo.php" method="post" enctype="multipart/form-data" target="ventanaEmergente" onsubmit="window.open('', this.target, 'width=250,height=250,resizable,scrollbars=yes');">
				
				<input type="file" name="archivo" id="archivo" accept="application/pdf" /> 
				<input type="hidden" name="id" value="<?php echo $mantenimiento['id_mantenimiento'];?>" /> 
				<input type="hidden" name="placa" value="<?php echo $mantenimiento['placa'];?>" />
				
				<button type="submit" name="boton" value="factura" disabled="disabled" class="adjunto">Subir Archivo</button>
			</form>
			<iframe name="ventanaEmergente" class="ventanaEmergente"></iframe>
		</fieldset>

		<form id="datosDetalleMantenimiento" data-rutaAplicacion="transportes" data-opcion="finalizarMantenimiento" data-accionEnExito="ACTUALIZAR">
			<input type='hidden' id='identificadorUsuarioRegistro' name='identificadorUsuarioRegistro' value="<?php echo $identificadorUsuarioRegistro;?>" />
			<input type="hidden" name="mantenimiento" value="<?php echo $mantenimiento['id_mantenimiento'];?>" /> 
			<input type="hidden" name="placa" value="<?php echo $mantenimiento['placa'];?>" /> 
			<input type="hidden" name="km_Actual" id="km_Actual" value="<?php echo $mantenimiento['kilometraje'];?>" />
					
			<fieldset>
				<legend>Información de liquidación de factura</legend>

				<div data-linea="1">

					<label>Número de factura</label> 
						<input type="text" name="numeroFactura" required="required" placeholder="Ej: 123456789">

				</div>
				
				<div data-linea="2">	
					<label id="lKilometrajeInicial">Kilometraje inicial</label> 
						<input type="number" name="kilometrajeInicial" id="kilometrajeInicial" readonly="readonly" value="<?php echo $mantenimiento['kilometraje'];?>" />
				</div>
				
				<div data-linea="2">

					<label id='lKilometraje'>Kilometraje final</label> 
						<input type="number" step="1" name="kilometraje" id="kilometraje" placeholder="Ej: 12345" />
				</div>
				
				<div data-linea="3" id="razonIncrementoKm">	
					<label>Razón incremento kilometraje</label>
						<input type="text" name="razonKilometraje" id="razonKilometraje" /> 
				</div>
			</fieldset>



			<fieldset>
				<legend>Detalle del mantenimiento</legend>

				<div data-linea="1">

					<label>Detalle</label> <input type="text" id="concepto"
						name="concepto" />

				</div>
				<div data-linea="1">

					<label>Valor</label> <input type="number" step="0.01" id="valor" name="valor" /> 
					<input type="hidden" id="subTotal" name="subTotal" />
					<input type="hidden" id="valorTotal" name="valorTotal" /> 

				</div>
				<div data-linea="2">

					<div class="info"></div>

				</div>

				<button type="button" onclick="agregarItem()" class="mas">Agregar
					item</button>

				<div>
					<table>
						<thead>
							<tr>
								<th colspan="2">Items ingresados</th>
							
							
							<tr>
						
						</thead>
						<tbody id="detalles">
						</tbody>
					</table>
				</div>


			</fieldset>


			<button id="detalle" type="submit" class="guardar">Guardar detalle de
				mantenimiento</button>

		</form>

</body>

<script type="text/javascript">
var contador = 0;
var valor = 0;
var habilitarVehiculo = <?php echo json_encode($ordenTrabajo); ?>;

$(document).ready(function(){

	distribuirLineas();
	construirAnimacion($(".pestania"));	
	$("#razonIncrementoKm").hide();
				
	cargarValorDefecto("taller","<?php echo $mantenimiento['taller'];?>");
	$('<option value="<?php echo $conductor['identificador'];?>"><?php echo $conductor['apellido'].", ".$conductor['nombre'];?></option>').appendTo('#ocupante');
	$("#divArea").hide();
	$("#finalizar").hide();
	
	
	if (<?php echo $mantenimiento['estado'];?>=="2"){
		$("#datosMantenimiento").hide();
		$("#imprimirMantenimiento").hide();
		$("#finalizar").show();
	}

	if (<?php echo $mantenimiento['estado'];?>=="1"){
		$("#datosDetalleMantenimiento").hide();
		$("#subirFactura").hide();
	}
	
	if (habilitarVehiculo=="1"){
		$("#lKilometraje").hide();
		$("#kilometraje").hide();
		$("#lKilometrajeInicial").hide();
		$("#kilometrajeInicial").hide();
	}else{
		$("#lKilometraje").show();
		$("#kilometraje").show();
		$("#lKilometrajeInicial").show();
		$("#kilometrajeInicial").show();
	}

	$("#reporte").show();
	$("#informacion").hide();
});

function agregarItem(){
	
	numSecuencial = ++contador;
	valor1 = $("#valor").val();
	valor2 = $("#subTotal").val();
	if($("#concepto").val()!="" && $("#valor").val()!="" && Number($("#valor").val()) > "0"){
		$("#detalles").append("<tr id='r_"+numSecuencial+"'><td><button type='button' onclick='quitarItem(\"#r_"+numSecuencial+"\")' class='menos'>Quitar</button></td><td>"+$("#concepto").val()+"</td><td>"+$("#valor").val()+"</td><td><input name='sConcepto[]' value='"+$("#concepto").val()+"' type='hidden'><input id='sTotal' name='sTotal[]' value='"+$("#valor").val()+"' type='hidden'></td></tr>");
		var subTotal = Math.round((Number(valor1) + Number(valor2))*100)/100;
		var iva = Math.round(((Number(subTotal)*0.12))*100)/100;
		var total = Math.round((subTotal + iva)*100)/100;
		$("div.info").html('Total : '+subTotal+ '+'+iva +'='+total );
		$("#subTotal").val(subTotal);
		$("#valorTotal").val(total);
	}
	 	 $("#concepto").val("");
         $("#valor").val("");
}

function quitarItem(fila){
	alert(fila);
	 var vRestar = $("#detalles tr").eq($(fila).index()).find("input[id='sTotal']").val();
	 var vTotal = $("#subTotal").val();
	 var vActual =  Math.round((Number(vTotal) - Number(vRestar))*100)/100;
	 var rIva = Math.round(((Number(vActual)*0.12))*100)/100;
	 var rTotal = Math.round((vActual + rIva)*100)/100;
	 $("div.info").html('Total : '+vActual+ '+'+rIva +'='+rTotal );
	 $("#subTotal").val(vActual);
	 $("#valorTotal").val(rTotal);
	 $("#detalles tr").eq($(fila).index()).remove();
}
				
	var array_responsable= <?php echo json_encode($responsable); ?>;

	$("#area").change(function(event){
		$("#categoriaArea").val($('#area option:selected').attr('data-categoria'));
		$('#lResponsable').hide();
		$('#responsable').hide();
		
		$("#datosMantenimiento").attr('data-opcion', 'combosOcupante');
	    $("#datosMantenimiento").attr('data-destino', 'dSubOcupante');
	    abrir($("#datosMantenimiento"), event, false); //Se ejecuta ajax, busqueda de sub tipo producto
	    
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
		$("#motivo").removeAttr("disabled");
		$("#imprimir").attr("disabled","disabled");
	});

	$("#datosMantenimiento").submit(function(event){
		event.preventDefault();

		$("#datosMantenimiento").attr('data-opcion', 'actualizarMantenimiento');
	    $("#datosMantenimiento").attr('data-destino', 'detalleItem');
		
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

	$("#datosDetalleMantenimiento").submit(function(event){

		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("#sTotal").length == 0){
			error = true;
			$("#estado").html("Por favor ingrese uno o varios detalles de la factura emitida por el taller.").addClass("alerta");
		}

		if(!error){
			if(<?php echo $ordenTrabajo;?> == '0'){
				if($("#kilometraje").val() > Number($("#km_Actual").val())){
					ejecutarJson($(this));
				}else{
					$("#estado").html("El kilometraje ingresado en inferior al actual, por favor verificar.").addClass("alerta");
				}
			}else{
				ejecutarJson($(this));
			}
		}

	});

	$("#archivo").click(function(){
		$("#subirArchivo button").removeAttr("disabled");});

	$("#imprimirMantenimiento").submit(function(event){ 
		event.preventDefault();
		ejecutarJson($(this));
	});

	$("#kilometraje").change(function(event){
		if((Number($("#kilometraje").val())-Number($("#kilometrajeInicial").val())) >= Number(1000)){
			$("#razonIncrementoKm").show();
			$("#razonKilometraje").attr('required','required');
			$("#estado").html("El kilometraje ingresado supera los 1000 kms de recorrido, por favor verifique el valor o ingrese el motivo del mismo.").addClass("alerta");
		}else{
			$("#razonIncrementoKm").hide();
			$("#razonKilometraje").removeAttr("required");
			
		}
	});
</script>

</html>
