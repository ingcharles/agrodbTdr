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

$res = $cv->abrirSiniestro($conexion, $_POST['id']);
$siniestro = pg_fetch_assoc($res);

//Identificador Usuario Administrador o Apoyo de Transportes
if($_SESSION['usuario'] != '' && $_SESSION['usuario']!=$vehiculo['identificador_registro']){
	$identificadorUsuarioRegistro = $_SESSION['usuario'];
}else if($_SESSION['usuario'] != '' && $_SESSION['usuario']==$vehiculo['identificador_registro']){
	$identificadorUsuarioRegistro = $vehiculo['identificador_registro'];
}else{
	$identificadorUsuarioRegistro = '';
}

$per = $ce->obtenerDatosPersonales($conexion, $siniestro['conductor']);
$conductor = pg_fetch_assoc($per);

$area = $ca->obtenerAreasDireccionesTecnicas($conexion, "('Planta Central','Oficina Técnica')", "(3,4,1)");
$usuario = $cu->obtenerUsuariosXarea($conexion);


while($fila = pg_fetch_assoc($usuario)){
	$responsable[]= array(identificador=>$fila['identificador'], apellido=>$fila['apellido'], nombre=>$fila['nombre'], area=>$fila['id_area']);
}

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>

<header>
	<h1>Detalle de Siniestro</h1><div class="numero"><?php echo $siniestro['id_siniestro'];?></div>
</header>



<div id="estado"></div>

<form id="datosSiniestro" data-rutaAplicacion="transportes" data-opcion="actualizarSiniestro" data-accionEnExito="ACTUALIZAR" >
	
	<p>
		<button id="modificar" type="button" class="editar">Modificar</button>
		<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>
	</p>
	
	<table class="soloImpresion">
	<tr><td>
	
	
	
	<fieldset>
		<legend>Información del vehículo</legend>
			
			<div data-linea="1">
			
				<label>ID: </label>
					<?php echo $siniestro['id_siniestro'].'';?>
					
			</div><div data-linea="2">
			
				<label>Vehículo: </label>
					<?php echo $siniestro['marca'].' - '.$siniestro['modelo'].' - '.$siniestro['tipo'].'';?>
					
			</div><div data-linea="3">
			
				<label>Placa: </label>
					<?php echo $siniestro['placa'];?>
			
			</div><div data-linea="3">
				<label>N° motor: </label>
					<?php echo $siniestro['numero_motor'];?>
					
			</div><div data-linea="4">
			
				<label>N° chasis: </label>
					<?php echo $siniestro['numero_chasis'];?>
			
			</div><div data-linea="4">
			
				<label>Oficina: </label>
					<?php echo $siniestro['nombre'];?>
			
			</div> 
			
				
	</fieldset>
	</td><td>
	<fieldset>
		<legend>Detalle del Siniestro</legend>
		
		 
		<input type="hidden" id="<?php echo $siniestro['id_siniestro'];?>" data-rutaAplicacion="transportes" data-opcion="mostrarFotosVehiculoSiniestro" data-destino="fotosVehiculoSiniestro"/>
		
		<input type="hidden" name="siniestro" value="<?php echo $siniestro['id_siniestro'];?>"/> 
		
		<div data-linea="1">
		
			<label>Tipo</label> 
				<select id="tipo_siniestro" name="tipo_siniestro" disabled="disabled">	
					<option value="Robo">Robo</option>
					<option value="Choque">Choque</option>
					<option value="Daños menores">Daños menores</option>							
				</select>
				
		</div><div data-linea="1">
						
			<label>Fecha del Siniestro</label>
				<input type="text" id="fecha" name="fecha" value="<?php echo date('j/n/Y',strtotime($siniestro['fecha_siniestro']));?>" disabled="disabled" required="required" readonly="readonly"/>
			
		</div><div data-linea="2">
			
			<label>Lugar del Siniestro</label> 
				<input type="text" id="lugar_siniestro" name="lugar_siniestro" value="<?php echo $siniestro['lugar_siniestro'];?>" disabled="disabled" required="required"/>
				
		</div><div data-linea="2">
				
			<label>Magnitud del Daño</label> 
				<select id="magnitud_siniestro" name="magnitud_siniestro" disabled="disabled">	
					<option value="Reparación">Reparación</option>
					<option value="Pérdida total">Pérdida total</option>							
				</select>  
			
		</div><div data-linea="3">
				
			<label>Observaciones</label> 
				<!--input type="text" id="observaciones" name="observaciones" value="< ?php echo $siniestro['observacion_siniestro'];?>" disabled="disabled"/--> 
				<textarea id="observaciones" name="observaciones" disabled="disabled" rows="4"><?php echo $siniestro['observacion_siniestro'];?></textarea> 
				
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
	<input type='hidden' id='identificadorUsuarioRegistro' name='identificadorUsuarioRegistro' value="<?php echo $identificadorUsuarioRegistro;?>" />
	
</form>





<fieldset id="subirDocumentacion">
	<legend>Documentación para Aseguradora</legend>
		<form id="subirArchivo" action="aplicaciones/transportes/subirArchivoSiniestro.php" method="post" enctype="multipart/form-data" target="ventanaEmergente" onsubmit="window.open('', this.target, 'width=250,height=250,resizable,scrollbars=yes');">
				<input type="file" name="archivo" id="archivo" accept="application/pdf"/> 
				<input type="hidden" name="id_siniestro" value="<?php echo $siniestro['id_siniestro'];?>"/>
				<button type="submit" name="boton" value="documentacion" disabled="disabled" class="adjunto" >Subir Archivo</button>
		</form>
	<iframe name="ventanaEmergente" class="ventanaEmergente"></iframe>
</fieldset>


<form id="enviarSiniestroPlantaCentral" data-rutaAplicacion="transportes" data-opcion="enviarSiniestroPlantaCentral" data-accionEnExito="ACTUALIZAR" >
		<input type='hidden' id='identificadorUsuarioRegistro' name='identificadorUsuarioRegistro' value="<?php echo $identificadorUsuarioRegistro;?>" />
		<p class="nota">Si se realiza el envío de la ficha de siniestro a Planta Central, esta cambiará de estado y ya no estará disponible para modificación.</p>
		<input type="hidden" name="id_siniestro" value="<?php echo $siniestro['id_siniestro'];?>"/>
		<button id="cerrarFase" type="submit" class="cerrarFase">Enviar a Planta Central</button>
</form>

							<!-- INFORMACIÓN PARA EL ADMINISTRADOR DE TRANSPORTES NACIONAL -->
<fieldset id="fichaSiniestro" name="fichaSiniestro">
		<legend>Detalle del Siniestro</legend>
		
		 
		<input type="hidden" id="<?php echo $siniestro['id_siniestro'];?>" data-rutaAplicacion="transportes" data-opcion="mostrarFotosVehiculoSiniestro" data-destino="fotosVehiculoSiniestro"/>
		
		<div data-linea="1">
			<label>ID: </label> <?php echo $siniestro['id_siniestro'];?>
		</div>
		<div data-linea="1">
			<label>Fecha del Siniestro: </label><?php echo date('j/n/Y',strtotime($siniestro['fecha_siniestro']));?>
		</div>
		<div data-linea="2">
			<label>Tipo: </label><?php echo $siniestro['tipo_siniestro'];?>
		</div>
		<div data-linea="2">
			<label>Magnitud del Daño: </label> <?php echo $siniestro['magnitud_danio_siniestro'];?>
		</div>
		<div data-linea="3">
			<label>Lugar del Siniestro: </label><?php echo $siniestro['lugar_siniestro'];?>
		</div>
		<div data-linea="5">
			<label>Observaciones: </label> <?php echo $siniestro['observacion_siniestro'];?>
		</div>
		<div data-linea="6">
			<label>Informe generado: </label> <a download="<?php echo $siniestro['id_siniestro'];?>.pdf" href="<?php echo $siniestro['documentacion_siniestro'];?>">Descargar documento</a>
		</div>	
	</fieldset>	


<form id="datosMontoSiniestro" data-rutaAplicacion="transportes" data-opcion="finalizarSiniestro" data-accionEnExito="ACTUALIZAR" >
	<input type="hidden" id="<?php echo $siniestro['id_siniestro'];?>" data-rutaAplicacion="transportes" data-opcion="mostrarFotosVehiculoSiniestro" data-destino="fotosVehiculoSiniestro"/>
		
	<input type='hidden' id='identificadorUsuarioRegistro' name='identificadorUsuarioRegistro' value="<?php echo $identificadorUsuarioRegistro;?>" />
		
	<fieldset>
		<legend>Kilometraje del vehículo</legend>

		<div data-linea="2">	
			<label id="lKilometrajeInicial">Kilometraje inicial</label> <?php echo $siniestro['kilometraje_inicial'];?>
		</div>
		
		<div data-linea="2">
			<label id='lKilometraje'>Kilometraje final</label> <?php echo $siniestro['kilometraje_final'];?>
		</div>
		
		<div data-linea="3" id="razonIncrementoKm">	
			<label>Razón incremento kilometraje</label>
				<input type="text" name="razonKilometraje" id="razonKilometraje" /> 
		</div>
	</fieldset>	
	<fieldset>
		<legend>Monto de daño a terceros</legend>
		
		<div data-linea="3">
			
			<label>Valor</label>
				<input type="text" name="montoTerceros" required="required" placeholder="Ej: 123456789"> 
		
		</div>
								
	</fieldset>
	
		
	<fieldset>
		<legend>Detalle de Monto generado</legend>
			
			<div data-linea="1">
			
			<label>Concepto</label> 
				<input type="text" id="concepto" name="concepto" />
				
			</div><div data-linea="1">
	
			<label>Valor</label> 
				<input type="number" step="0.01" id="valor" name="valor" />
				<input type="hidden" id="subTotal" name="subTotal"/>
				<input type="hidden" id="valorTotal" name="valorTotal" />
				<input type="hidden" name="id_siniestro" value="<?php echo $siniestro['id_siniestro'];?>"/>
				
			</div><div data-linea="2">
			
				<div class="info"></div>
			
			</div>

			<button type="button" onclick="agregarItem()" class="mas">Agregar item</button>
	
		<div>
			<table>
				<thead>
					<tr>
						<th colspan="2">Items ingresados   </th>
					<tr>
				
				</thead>
				<tbody id="detalles">
				</tbody>
			</table>
		</div> 
	

	</fieldset>
	
	<button id="detalle" type="submit" class="guardar" >Finalizar siniestro</button>
	
</form>

<br/>
<table id="fotosVehiculoSiniestro" class="soloImpresion">
<tr><td>
<div id="fotosVehiculoSiniestro"></div>
</td></tr>
</table>

</body>

<script type="text/javascript">

var contador = 0;
var valor = 0;

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
		sresponsable ='0';
		sresponsable = '<option value="">Apellido, Nombre...</option>';
	    for(var i=0;i<array_responsable.length;i++){
		    if ($("#area").val()==array_responsable[i]['area']){
		    	sresponsable += '<option value="'+array_responsable[i]['identificador']+'">'+array_responsable[i]['apellido']+', '+array_responsable[i]['nombre']+'</option>';
			    }
	   		}
	    $('#responsable').html(sresponsable);
	    $('#responsable').removeAttr("disabled");
	 });

	
	$("#modificar").click(function(){
		$("input").removeAttr("disabled");
		$("select").removeAttr("disabled");
		$("textarea").removeAttr("disabled");
		$("#actualizar").removeAttr("disabled");
		$("#divArea").fadeIn();
		$(this).attr("disabled","disabled");
	});

	
	$(document).ready(function(){

		distribuirLineas();
		$("#razonIncrementoKm").hide();
		
		abrir($("#datosSiniestro input:hidden"),null,false);
		$('select[name="tipo_siniestro"]').find('option[value="<?php echo $siniestro['tipo_siniestro'];?>"]').prop("selected","selected");
		$('select[name="magnitud_siniestro"]').find('option[value="<?php echo $siniestro['magnitud_danio_siniestro'];?>"]').prop("selected","selected");

		$('<option value="<?php echo $conductor['identificador'];?>"><?php echo $conductor['apellido'].", ".$conductor['nombre'];?></option>').appendTo('#ocupante');


		if (<?php echo $siniestro['estado'];?>=="2"){
			$("#datosSiniestro").hide();
			$("#enviarSiniestroPlantaCentral").hide();
			$("#fotosVehiculoSiniestro").show();
			$("#subirDocumentacion").hide();
			$("#subirInforme").hide();
			$("#datosInformeSiniestro").hide();
			$("#cerrarFaseSiniestroInforme").hide();
			$("#datosResolucionSiniestro").hide();
			$("#fichaSiniestro").show();
		}

		if (<?php echo $siniestro['estado'];?>=="1"){
			$("#datosDetalleSiniestro").hide();
			$("#subirFactura").hide();
			$("#datosMontoSiniestro").hide();
			$("#cerrarFaseSiniestroFactura").hide();
			$("#subirInforme").hide();
			$("#datosInformeSiniestro").hide();
			$("#cerrarFaseSiniestroInforme").hide();
			$("#datosResolucionSiniestro").hide();
			$("#fichaSiniestro").hide();			
		}
		
	});

	$("#datosSiniestro").submit(function(event){

		$("#datosSiniestro").attr('data-opcion', 'actualizarSiniestro');
	    $("#datosSiniestro").attr('data-destino', 'detalleItem');
	    
		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
		
		if($("#ocupante").val()==null || $("#ocupante").val()=='' || $("#ocupante").val()=="Otro"){
			error = true;
			$("#ocupante").addClass("alertaCombo");
			$("#estado").html("Debe seleccionar a un funcionario de Agrocalidad").addClass("alerta");
		}

		if (!error){
			ejecutarJson($(this));
		}
	});

	$("#archivo").click(function(){
		$("#subirArchivo button").removeAttr("disabled");});

	$("#archivoFactura").click(function(){
		$("#subirFactura button").removeAttr("disabled");});

	$("#archivoInforme").click(function(){
		$("#subirInforme button").removeAttr("disabled");});

	$("#enviarSiniestroPlantaCentral").submit(function(event){ 
			$("#fotosVehiculoSiniestro").fadeOut();
			event.preventDefault();
			ejecutarJson($(this));
	});

	$("#cerrarFaseSiniestroFactura").submit(function(event){ 
		$("#datosMontoSiniestro").hide();
		event.preventDefault();
		ejecutarJson($(this));
	});

	$("#datosMontoSiniestro").submit(function(event){
		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
		
		if($("#sTotal").length == 0){
			error = true;
			$("#estado").html('Por favor ingrese el detalle del monto generado en el siniestro.').addClass("alerta");
		}

		if (!error){
			ejecutarJson($(this));
		}
	});

	$("#datosInformeSiniestro").submit(function(event){
		event.preventDefault();
		ejecutarJson($(this));
	});

	$("#datosResolucionSiniestro").submit(function(event){
		event.preventDefault();
		ejecutarJson($(this));
	});

	$(document).ready(function(){
		$("#fecha").datepicker({
	      changeMonth: true,
	      changeYear: true,
		dateFormat: 'yy-mm-dd'
	    });
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

	$("#area").change(function(){
		$("#categoriaArea").val($('#area option:selected').attr('data-categoria'));
		$('#lResponsable').hide();
		$('#responsable').hide();
		
		$("#datosSiniestro").attr('data-opcion', 'combosOcupante');
	    $("#datosSiniestro").attr('data-destino', 'dSubOcupante');
	    abrir($("#datosSiniestro"), event, false); //Se ejecuta ajax, busqueda de sub tipo producto
	    
	    $('#ocupante').html(socupante);
	    $('#ocupante').removeAttr("disabled");
	 });
</script>

</html>
