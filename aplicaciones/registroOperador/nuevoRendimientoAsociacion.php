<?php


session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';

$conexion = new Conexion();
$cro = new ControladorRegistroOperador();

$usuario = $_SESSION['usuario'];

$operaciones = $cro->obtenerOperacionesXOperador($conexion, $usuario);
$qDatosOperador = $cro->obtenerDatosOperador($conexion, $usuario);
$datosOperador = pg_fetch_assoc($qDatosOperador);

list($idSitio, $idArea) = explode("-", $_POST['id']);

$nombreSitio = pg_fetch_result($cro->abrirSitio($conexion, $idSitio), 0, 'nombre_lugar');

$qProductosXArea = $cro->obtenerOperacionesRendimientoXIdArea($conexion, $idArea);

$observacion = pg_fetch_result($qProductosXArea, 0, 'observacion');
$estadoOperacion = pg_fetch_result($qProductosXArea, 0, 'estado');

list($tipoActividad, $codigoSic) = explode("-", $datosOperador['tipo_actividad']);

?>

<header>
	<h1>Registro de rendimiento sitio <?php echo $nombreSitio; ?></h1>
</header>
	<div id="estado"></div>
	<div id="mensajeCargando"></div>

	<fieldset id="resultado">
			<legend>Resultado de Inspección</legend>
			<div data-linea="1">
				<label>Resultado: </label> <?php echo $estadoOperacion; ?> <br/>
			</div>
			<div data-linea="2">
				<label>Observaciones: </label> <?php echo $observacion; ?> <br/>
			</div>			
	</fieldset>

	<?php if($tipoActividad == "" || $tipoActividad == null){?>
	
	<div data-linea="1">
			<br>
			<label>Para poder registrar rendimiento debe actualizar su información en la opción <img src="aplicaciones/registroOperador/img/datosOperador.png"> Datos operador, como se muestra en la imagen:<br></br></label>			
			<br>	
	</div>
	<div id="imagenTipoOperador">	
	</div>
	
	<?php }else{?>
	<form id="declararRendimientoAsociacion" data-rutaAplicacion="registroOperador" data-opcion="guardarNuevoRendimientoAsociacion" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" id="opcion" name="opcion" value="" />
	<input type="hidden" id="estadoOperacion" name="estadoOperacion" value="<?php echo $estadoOperacion; ?>" />
	<fieldset>
			<legend>Datos del miembro</legend>				
				
				<?php 
				
				$qDatosMiembroAsociacion = $cro->obtenerMiembroAsociacionXIdSitio($conexion, $idSitio);
				$datosMiembroAsociacion = pg_fetch_assoc($qDatosMiembroAsociacion);			
				
				if ($tipoActividad == "grupal" || $tipoActividad == "grupal-SIC"){					
					
					if(pg_num_rows($qDatosMiembroAsociacion)>0){?>
						<div data-linea="1">
							<label>Identificacion:</label>
							<input type="text" id="numero" name="numero" value="<?php echo $datosMiembroAsociacion['identificador_miembro_asociacion'] ?>" maxlength="13" />
						</div>
						<div data-linea="1">
							<label>Código MAG:</label>
							<input type="text" id="codigoMagap" name="codigoMagap" value="<?php echo $datosMiembroAsociacion['codigo_magap'] ?>" maxlength="63" />
						</div>
						<div id="resultadoMiembro" data-linea="3">					
							<label>Nombres:</label>
							<input type="text" id="nombreMiembro" name="nombreMiembro" value="<?php echo $datosMiembroAsociacion['nombre_miembro_asociacion'] ?>" />						
							<label style="margin-top:3px">Apellidos:</label>
							<input style="margin-top:3px" type="text" id="apellidoMiembro" name="apellidoMiembro" value="<?php echo $datosMiembroAsociacion['apellido_miembro_asociacion'] ?>" />						
						</div>
					<?php }else{ ?>	
						<div data-linea="1">
							<label>Identificacion:</label>						
							<input type="text" id="numero" name="numero" maxlength="13"/>
						</div>
						<div data-linea="1">
							<label>Código MAG</label>
							<input type="text" id="codigoMagap" name="codigoMagap" value="<?php echo $datosMiembroAsociacion['codigo_magap'] ?>" maxlength="63" />
						</div>
						<div id="resultadoMiembro"></div>
					
				<?php }}else{?>			
				
					<div data-linea="1">
						<label>Identificacion:</label>
						<input type="text" id="numero" name="numero" maxlength="13" value="<?php echo $datosOperador['identificador'];?>" readonly />
					</div>
					<div data-linea="1">
						<label>Código MAG</label>
						<input type="text" id="codigoMagap" name="codigoMagap" value="<?php echo $datosMiembroAsociacion['codigo_magap'] ?>" maxlength="63" />
					</div>
					<div data-linea="2">
						<label>Nombres:</label>
						<input type="text" id="nombreMiembro" name="nombreMiembro" maxlength="50" value="<?php echo $datosOperador['nombre_representante'];?>" readonly />
					</div>
					<div data-linea="3">
						<label>Apellidos:</label>
						<input type="text" id="apellidoMiembro" name="apellidoMiembro" maxlength="50" value="<?php echo $datosOperador['apellido_representante'];?>" readonly />
					</div>
			
				<?php }?>	
	</fieldset>
	
	<fieldset>
	<legend>Declarar rendimiento por producto</legend>
	<?php
			
	echo '<table id="tRendimiento" width="100%">
				<tr><th width="25%">Producto</th><th width="25%">Superficie (ha)</th><th width="25%">Rendimiento</th><th width="25%">Actualizar agencia</th></tr>';
	
	while($productoXArea = pg_fetch_assoc($qProductosXArea)){
		$superficieArea = $productoXArea['superficie_utilizada'];
		echo '
				<tr>
					<td width="25%">' . $productoXArea['nombre_producto'] . '<input type="hidden" name="producto" value ="'.$productoXArea['nombre_producto'].'" />
					<input type="hidden" name="idOperadorTipoOperacion" value ="'.$productoXArea['id_operador_tipo_operacion'].'" />
					<input type="hidden" name="idHistorialOperacion" value ="'.$productoXArea['id_historial_operacion'].'" />
					<input type="hidden" name="idOperacion['.$productoXArea['id_operacion'].']" value ="'.$productoXArea['id_operacion'].'" />
					<input type="hidden" name="idArea" value ="'.$productoXArea['id_area'].'" />
					<input type="hidden" name="idSitio" value ="'.$productoXArea['id_sitio'].'" />
					<input type="hidden" name="superficieSitio" value ="'.$productoXArea['superficie_utilizada'].'" /></td>
					<td width="25%" style="text-align: center;"><input style="width: 90%;" type="text" name="superficie['.$productoXArea['id_operacion'].']" id="S'.$productoXArea['id_operacion'].'" value ="'.$productoXArea['superficie_miembro'].'" class="superficie" /></td>
					<td width="25%" style="text-align: center;"><input style="width: 90%;" type="text" name="rendimiento['.$productoXArea['id_operacion'].']"  id="R'.$productoXArea['id_operacion'].'" value ="'.$productoXArea['rendimiento'].'" class="rendimiento" onchange="validarGuardar('.$productoXArea['id_operacion'].')" /></td>
					<td width="25%" style="text-align: center;"><input type="checkbox" name="agencia['.$productoXArea['id_operacion'].']" value="actualizar"></td>
				</tr>';			
				
	}
	echo '</table>
	<hr/> <label>Superficie total del Área: ' . $superficieArea . ' Hectáreas</label>';
	
	?>
	</fieldset>
	<p class="nota">Por favor, para registrar rendimientos, se debe realizar la conversión a "toneladas/año" para cultivos, huevos, camarones y carne; usar "kilogramos" para miel; seleccionar "litros" para bebidas; y "número de unidades" para animales, colmenas y pilones.<p>

	<button id="btnGuardar" type="submit" name="btnGuardar">Enviar Rendimiento</button>
	
	</form>	
	<?php }?>	
	
<script type="text/javascript">	

var tipo_actividad = <?php echo json_encode($tipoActividad); ?>;
	
	$(document).ready(function(){	
				
		$("#resultado").hide();	
		//cambio de rechazado a noHabilitado
		if (<?php echo '"'.$estadoOperacion.'"';?> == "subsanacion" || <?php echo '"'.$estadoOperacion.'"';?> == "subsanacionProducto"){
			$("#resultado").show();
		}
		
		$(".superficie").numeric();
		$(".rendimiento").numeric();
		$('.superficie').on("cut copy paste",function(event) {
			event.preventDefault();
		});
		$('.rendimiento').on("cut copy paste",function(event) {
			event.preventDefault();
		});

		distribuirLineas();	
		
	});

	$("#numero").change(function(event){
		
		if(tipo_actividad == "grupal"){
			$('#declararRendimientoAsociacion').attr('data-opcion','accionesRendimientoAsociacion');
			$('#declararRendimientoAsociacion').attr('data-destino','resultadoMiembro');
			$('#opcion').val('miembro');
			abrir($("#declararRendimientoAsociacion"),event,false);	
			$('#declararRendimientoAsociacion').attr('data-opcion','guardarNuevoRendimientoAsociacion');
			distribuirLineas(); 
		}
		
	});

	function validarGuardar(vel) {
		//alert(vel);
		//rendimiento = "R"+(vel);
		//superficie = "S"+(vel);
		
	   /* if($("#"+superficie).val()!="" || $("."+superficie).val()!=null){
			alert("campo lleno");

		}else{
			
			alert("campo vacio");
			$("#"+rendimiento).val("");
			}*/

	   /* event.preventDefault();
  	    $(".alertaCombo").removeClass("alertaCombo");
  	  	var error = false;

    	if($("#"+superficie).val()==""){
			error = true;
			$("#"+superficie).addClass("alertaCombo");
		}*/

	}
	

	
	$("#declararRendimientoAsociacion").submit(function(event){

		event.preventDefault();

		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;		

		if(!$.trim($("#numero").val()) || $("#numero").val()==""){
			error = true;
			$("#numero").addClass("alertaCombo");
		}

		if(!$.trim($("#nombreMiembro").val()) || $("#nombreMiembro").val()==""){
			error = true;
			$("#nombreMiembro").addClass("alertaCombo");
		}

		if(!$.trim($("#apellidoMiembro").val()) || $("#apellidoMiembro").val()==""){
			error = true;
			$("#apellidoMiembro").addClass("alertaCombo");
		}
					
		/*$("#tRendimiento tbody").each(function(){
			alert($(this).find('input[name="superficie[1160]"]').val());
			superficie = $(this).find('input[name="superficie[1160]"]').val();
			rendimiento = $(this).find('input[name="rendimiento"]').val();
			if (superficie == "" || rendimiento==""){
				//alert("llene todos los campos");
				error = true;

				error = true;
				$("#"+superficie).addClass("alertaCombo");
			}


			
			
		});*/

		if (!error){
			//alert("dsdss");
			//event.stopImmediatePropagation();
			ejecutarJson($(this));				
		}else{
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	
		//ejecutarJson($(this));
		
	});
	
	
	/*function validarGuardar(val) {
	
		$("#"+val).submit(function(event){
		
			event.preventDefault();
			
			$(".alertaCombo").removeClass("alertaCombo");
			var error = false;

			superficie = "S"+(val);
			rendimiento = "R"+(val);
			
			
		    if($("#"+superficie).val()=="" || $("#"+superficie).val()==null){
				error = true;
				$("#"+superficie).addClass("alertaCombo");
			}
		
		    if($("#"+rendimiento).val()=="" || $("#"+rendimiento).val()==null){
				error = true;
				$("#"+rendimiento).addClass("alertaCombo");
			}
			
			if (!error){
				event.stopImmediatePropagation();
				ejecutarJson($(this));				
			}else{
				$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
			}
			
		});
	
	}*/
	

</script>