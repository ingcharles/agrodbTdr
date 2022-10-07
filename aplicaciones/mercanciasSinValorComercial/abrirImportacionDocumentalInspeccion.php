<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorMercanciasSinValorComercial.php';

$conexion = new Conexion();
$ce = new ControladorMercanciasSinValorComercial();
$idSolicitud = $_POST['id'];

$res=$ce->obtenerSolicitud($conexion, $idSolicitud);
$filaSolicitud=pg_fetch_assoc($res);

?>

<header>
<h1>Importación de Mascotas</h1>
</header>

<div class="pestania">

<?php
	echo '<fieldset>
		<legend>Datos del Propietario:</legend>';

			if($filaSolicitud['identificador_propietario']==""){
				echo '<div data-linea="1"><label>No existen datos del propietario</label></div>';
			} else{				
				echo'<div data-linea="1"><label for="tipoIdentificacion">Tipo de identificación: </label>'.($filaSolicitud['tipo_identificacion_propietario']== "04" ? "Ruc" : ($filaSolicitud['tipo_identificacion_propietario']== "05" ? "Cédula" : "Pasaporte")).'</div>
				<div data-linea="3"><label for="identificacionPropietario">Identificación: </label>'.$filaSolicitud['identificador_propietario'].'</div>				
				<div data-linea="2"><label for="nombrePropietario">Nombre: </label>'.$filaSolicitud['nombre_propietario'].'</div>
				<div data-linea="4"><label for="direccionPropietario">Dirección: </label>'.$filaSolicitud['direccion_propietario'].'</div>
				<div data-linea="5"><label for="telefonoPropietario">Teléfono: </label>'.$filaSolicitud['telefono_propietario'].'</div>
				<div data-linea="6"><label for="correoPropietario">Correo: </label>'.$filaSolicitud['correo_propietario'].'</div>';
			}
		echo'</fieldset>';
	?>
	<fieldset>
		<legend>Datos Generales</legend>
		<div data-linea="2">
			<label for="pais">País Origen</label>
			<?php 
				echo $filaSolicitud['pais_origen_destino'];
			?>
		</div>
		<div data-linea="2" id="resultadoEmbarque">
			<label for="puertoEmbarque">Puerto de Embarque:</label>
			<?php
				echo $filaSolicitud['nombre_puerto'];
			?>
		</div>
		<div data-linea="4">
			<label for=residencia>Dirección Ecuador: </label>
			<?php
				echo $filaSolicitud['direccion_ecuador'];
			?>
		</div>
		<div data-linea="4">
			<label for="fechaEmbarque">Fecha Embarque: </label>
			<?php
				$fecha=explode(' ',$filaSolicitud["fecha_embarque"]);
				echo $fecha[0];
			?>
		</div>
		<div data-linea="5">
			<label for="uso">Uso Destinado: </label>
			<?php
				echo $filaSolicitud['nombre_uso'];
			?>
		</div>
		<div data-linea="6">
			<label for="puestoControl">Puesto Control Cuarentenario: </label>
			<?php
				echo $filaSolicitud['puesto_control'];
			?>
		</div>
	</fieldset>

	<?php

	$res=$ce->cargarDocumentos($conexion, $idSolicitud);
	$filaDocumento=pg_fetch_assoc($res);
	
	echo '<fieldset id="resultadoProductos">
				<legend>Documentos Adjuntos</legend>
					<div data-linea="1">
						<label>Certificado Zoosanitario de Exportación: </label>';
						if($filaDocumento['ruta_zoosanitario_exp'] !=""){
							echo '<a href="'. $filaDocumento['ruta_zoosanitario_exp'].'" target="_blank" class="archivo_cargado"> Archivo Cargado</a>';
						}else{
							echo '<span class="alerta">No ha subido ningún archivo aún</span>';
						}
				echo'</div>
					<div data-linea="2">
						<label>Autorización Ministerio de Ambiente: </label>';
						if($filaDocumento['ruta_autorizacion_min_ambiente'] !=""){
							echo '<a href="'. $filaDocumento['ruta_autorizacion_min_ambiente'].'" target="_blank" class="archivo_cargado"> Archivo Cargado</a>';
						}else{
							echo '<span class="alerta">No ha subido ningún archivo aún</span>';
						}
				echo'</div>';
	echo'</fieldset>';

	$res=$ce->obtenerDetalleSolicitud($conexion, $idSolicitud);
	$fila=null;
	$contador=0;
	while($fila=pg_fetch_assoc($res)){
		$contador+=1;
		echo '<fieldset id="datosProducto">	<legend>Datos del Producto '.$contador.' :</legend>'.
				'<div data-linea="1"><label>Tipo de Producto: </label>'.$fila['nombre_tipo'].'</div>'.
				'<div data-linea="2"><label>Subtipo: </label>'.$fila['nombre_subtipo'].'</div>'.
				'<div data-linea="2"><label>Producto: </label>'.$fila['nombre_comun'].'</div>'.
				'<div data-linea="3"><label>Sexo: </label>'.$fila['sexo_completo'].'</div>'.
				'<div data-linea="3"><label>Edad: </label>'.$fila['edad'].' meses</div>'.
				'<div data-linea="4"><label>Color: </label>'.$fila['color'].'</div>'.
				'<div data-linea="4"><label>Raza: </label>'.$fila['raza'].'</div>'.				
				'<div data-linea="5"><label>Identificación: </label>'.$fila['identificacion_producto'].'</div>'.
			'</fieldset>';
			}
	?>
</div>
<div class="pestania">	
	<form id="evaluarSolicitud" data-rutaAplicacion="revisionFormularios" data-opcion="evaluarDocumentosSolicitud" data-accionEnExito="ACTUALIZAR">
			<input type="hidden" name="inspector" value="<?php echo $_SESSION['usuario'];?>"/> <!-- INSPECTOR -->
			<input type="hidden" name="idSolicitud" value="<?php echo $idSolicitud;?>"/>
			<input type="hidden" name="tipoSolicitud" value="mercanciasSinValorComercialImportacion"/>
			<input type="hidden" name="tipoInspector" value="Documental"/>
			<input type="hidden" name="identificadorOperador" value="<?php echo $filaSolicitud['identificador_operador'];?>"/> <!-- USUARIO OPERADOR -->
			<input type="hidden" name="tipoElemento" value="Productos"/>
							
			<fieldset>
				<legend>Resultado de Revisión</legend>
						
					<div data-linea="6">
						<label>Resultado</label>
							<select id="resultadoDocumento" name="resultadoDocumento">
								<option value="">Seleccione....</option>
								<option value="pago">Aprobar revisión documental</option>
								<option value="subsanacion">Subsanación</option>
								<option value="rechazado">Rechazado</option>
							</select>
					</div>	
					<div data-linea="2">
						<label id="lDetallePago" >Detalle pago</label>
							<input type="text" id="detallePago" name="detallePago" />
					</div>
					<div data-linea="3">
						<label>Observación</label>
							<input type="text" id="observacionDocumento" name="observacionDocumento"/>
					</div>
			</fieldset>
			<button type="submit" class="guardar">Enviar resultado</button>
		</form>
	</div>
<script type="text/javascript">

	var estado= <?php echo json_encode($filaSolicitud['estado']); ?>;

	$("document").ready(function(){
		distribuirLineas();	
		construirAnimacion($(".pestania"));
		$("#lDetallePago").hide();
		$("#detallePago").hide();
		$("#observacionDocumento").attr("maxlength","512");
		$("#detallePago").attr("maxlength","128");
	});

	$("#resultadoDocumento").change(function(event){ 
		if($("#resultadoDocumento option:selected").val() == 'pago'){
			$("#lDetallePago").show();
			$("#detallePago").show();
			$("#detallePago").attr('required',"required");
		}else{
			$("#lDetallePago").hide();
			$("#detallePago").hide();
			$("#detallePago").removeAttr('required');
		}
	});
	
	$("#evaluarSolicitud").submit(function(event){
		event.preventDefault();
		chequearCamposInspeccion(this,event);
	});
	
	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}
	
	function chequearCamposInspeccion(form,event){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
		
		if(!$.trim($("#resultadoDocumento").val()) || !esCampoValido("#resultadoDocumento")){
			error = true;
			$("#resultadoDocumento").addClass("alertaCombo");
		}

		if($("#resultadoDocumento").val()=='pago'){
			if(!$.trim($("#detallePago").val()) || !esCampoValido("#detallePago")){
				error = true;
				$("#detallePago").addClass("alertaCombo");
			}
		}

		if($("#resultadoDocumento").val()=='rechazado' || $("#resultadoDocumento").val()=='subsanacion'){
			if(!$.trim($("#observacionDocumento").val()) || !esCampoValido("#observacionDocumento")){
				error = true;
				$("#observacionDocumento").addClass("alertaCombo");
			}
		}

		if (error){
			$("#estado").html("Por favor revise la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson(form);

			if($("#resultadoDocumento").val() == 'pago'){
				 $('#evaluarSolicitud').attr('data-rutaAplicacion','mercanciasSinValorComercial');
				 $('#evaluarSolicitud').attr('data-opcion','mostrarDocumentoPDF');
				 $('#evaluarSolicitud').attr('data-destino','detalleItem');
				 abrir($("#evaluarSolicitud"),event,false);			 
			} else{				
				$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un item para revisarlo.</div>');
			}
		}
	}

</script>