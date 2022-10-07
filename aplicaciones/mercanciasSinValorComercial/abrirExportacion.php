<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorMercanciasSinValorComercial.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$ce = new ControladorMercanciasSinValorComercial();

$idSolicitud = $_POST['id'];
$operador = $_SESSION['usuario'];
$res=$ce->obtenerSolicitud($conexion, $idSolicitud);
$filaSolicitud=pg_fetch_assoc($res);

$obligatorio="";

if($filaSolicitud['estado']=='subsanacion'){
	$obligatorio="*";
}
?>

<header>
	<h1>Solicitud de Exportación</h1>
</header>
<div id="mensajeCargando"></div>
<form id="cargarSolicitud" data-rutaAplicacion="mercanciasSinValorComercial" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" id="idRegistro" value=<?php echo $_POST['id']?> name="idRegistro" />
	<input type="hidden" id="estadoSolicitud" value=<?php echo $filaSolicitud['estado'];?> name="estadoSolicitud" />
	<input type="hidden" id="solicitud" name="solicitud" value=<?php echo $idSolicitud;?> />
	<input type="hidden" id="tipoSolicitud" name="tipoSolicitud" value=<?php echo $filaSolicitud['tipo_solicitud'];?> />
	<input type="hidden" id="usuario" value=<?php echo $operador;?> name="usuario">	
	<input type="hidden" id="numeracionCarnetVacuna">
	<input type="hidden" id="numeracionCertificadoMedico">
	<input type="hidden" id="numeracionTitularAnticuerpo">
	<input type="hidden" id="numeracionAutMinAmb">
	<input type="hidden" id="clasificacion" value="" name="clasificacion">
	<input type="hidden" id="numero" value="" name="numero">

<?php 
	if($filaSolicitud['estado']=='subsanacion'){
		echo "<button id=modificar type=button class=editar>Modificar</button> ";
		echo "<button id=actualizar type=submit class=guardar disabled=disabled>Actualizar</button>";
	}

	if($filaSolicitud['observacion'] !=''){
		echo'<fieldset>
				<legend>Resultado Solicitud:</legend>
				<div data-linea="1">
					<label>Estado: </label>'.$filaSolicitud['estado_solicitud'].
				'</div>
				<div data-linea="2">
					<label>Observación: </label>'.$filaSolicitud['observacion'].
				'</div>
			</fieldset>';
	}

	echo '<fieldset>
		<legend>Datos del Propietario</legend>';
		
		if($filaSolicitud['estado']=="subsanacion"){
			echo '<div data-linea="1"><label>*Tipo de identificación: </label><select id="tipoIdentificacion" name="tipoIdentificacion" disabled="disabled"><option value = "">Seleccione....</option>
					<option value="04">Ruc</option>
					<option value="05">Cédula</option>
					<option value="06">Pasaporte</option>
				</select></div>
				<div data-linea="3"><label for="identificacionPropietario">* Identificación: </label>'.'<input type="text" id="identificacionPropietario" name="identificacionPropietario" value="'.$filaSolicitud['identificador_propietario'].'" disabled > </div>
				<div data-linea="2"><label for="nombrePropietario">* Nombre: </label> <input type="text" id="nombrePropietario" name="nombrePropietario" value="'.$filaSolicitud['nombre_propietario'].'" onkeypress="return soloLetras(event)" disabled > </div>
				<div data-linea="4"><label for="direccionPropietario">* Dirección: </label>'.'<input type="text" id="direccionPropietario" name="direccionPropietario" value="'.$filaSolicitud['direccion_propietario'].'" disabled > </div>
				<div data-linea="5"><label>*Teléfono: </label><input type="number" id="telefonoPropietario" name="telefonoPropietario" value="'.$filaSolicitud['telefono_propietario'].'" class="soloNumeros" disabled="disabled"/></div>
				<div data-linea="6"><label>*Correo: </label><input type="text" id="correoPropietario" name="correoPropietario" value="'.$filaSolicitud['correo_propietario'].'" data-er="^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$" disabled="disabled"/></div>';
		}else {
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
		}
		echo'</fieldset>';
?>
	<fieldset>
		<legend>Datos del Destinatario</legend>
		<div data-linea="1">
			<?php
				echo'<label for="nombreDestinatario">'.$obligatorio.' Nombre: </label>';
				if($filaSolicitud['estado']!="subsanacion"){
					echo $filaSolicitud['nombre_destinatario'];
				}else{
					echo '<input type="text" id="nombreDestinatario" name="nombreDestinatario" value="'.$filaSolicitud['nombre_destinatario'].'" onkeypress="return soloLetras(event)" disabled >';
				}
			?>
		</div>

		<div data-linea="2">
			<?php
				echo '<label for="direccionDestinatario">'.$obligatorio.' Dirección: </label>';
				if($filaSolicitud['estado']!="subsanacion"){
					echo $filaSolicitud['direccion_destinatario'];
				}else{
					echo '<input type="text" id="direccionDestinatario" name="direccionDestinatario" value="'.$filaSolicitud['direccion_destinatario'].'"  disabled >';
				}
			?>
		</div>
	</fieldset>

	<fieldset>
		<legend>Datos Generales</legend>
		<div data-linea="2">
			<?php 
				echo '<label for="pais">'.$obligatorio.' País Destino: </label>';
				if($filaSolicitud['estado']!="subsanacion"){
					echo $filaSolicitud['pais_origen_destino'];
				}else{
					echo '<select id="pais" name="pais" value="'.$filaSolicitud['id_localizacion_origen_destino'].'" disabled>';
						echo '<option value="">Seleccione...</option>';
						$pais = $cc->listarLocalizacion($conexion, 'PAIS');
						while($fila=pg_fetch_assoc($pais)){
							echo '<option value="' . $fila['id_localizacion'] .'">' . $fila['nombre'] . '</option>';
						}
					echo '</select>
							<input type="hidden" name="nombrePais" id="nombrePais" value="'. $filaSolicitud['pais_origen_destino'].'">';
				}
			?>
		</div>
		<div data-linea="3">
			<?php
				echo '<label for="uso">'.$obligatorio.' Uso Destinado: </label>';
				if($filaSolicitud['estado']!="subsanacion"){
					echo $filaSolicitud['nombre_uso'];
				} else{
					echo '<select id="uso" name="uso" disabled>
						<option value="">Seleccione...</option>';
						$res= $cc->listarUsosPorArea($conexion, 'SA');
						while($fila=pg_fetch_assoc($res)){
							echo'<option value="'.$fila['id_uso'].'">'.$fila['nombre_uso'].'</option>';
						}
					echo '</select><input type="hidden" name="nombreUso" id="nombreUso" value="'. $filaSolicitud['nombre_uso'].'">';
				}
			?>
		</div>
		<div data-linea="3">
			<?php
				echo'<label for="fechaEmbarque">'.$obligatorio.' Fecha Embarque: </label>';
				if($filaSolicitud['estado']!="subsanacion"){
					echo date('Y/m/d',strtotime($filaSolicitud['fecha_embarque']));
				}else{
					echo '<input type="text" id="fechaEmbarque" name="fechaEmbarque" value='.$filaSolicitud['fecha_embarque'].' disabled >';
				}
			?>
		</div>

		<div data-linea="6">
			<?php
				echo '<label for="puestoControl">'.$obligatorio.' Puesto Control Cuarentenario: </label>';
				if($filaSolicitud['estado']!="subsanacion"){
					echo $filaSolicitud['puesto_control'];
				}else{
					echo '<select id="puestoControl" name="puestoControl" disabled>
							<option value="">Seleccione...</option>';				
							$res= $cc->listarCatalogoLugarInspeccion($conexion, 'Mercancia');
							while($fila=pg_fetch_assoc($res)){
								echo'<option value="'.$fila['id_lugar'].'" data-provincia="'.$fila['nombre_provincia'].'" data-idprovincia="'.$fila['id_provincia'].'">'.$fila['nombre'].'</option>';
							}
				echo '</select>
					 	<input type="hidden" name="nombrePuestoControl" id="nombrePuestoControl" value="'. $filaSolicitud['puesto_control'].'">
						<input type="hidden" id="idProvincia" name="idProvincia" value="'.$filaSolicitud['id_pronvincia_control'].'">
						<input type="hidden" id="nombreProvincia" name="nombreProvincia" value="'.$filaSolicitud['nombre_provincia_control'].'">';
				}
			?>
		</div>
	</fieldset>

	<?php

	$res=$ce->cargarDocumentos($conexion, $idSolicitud);
	$filaDocumento=pg_fetch_assoc($res);

	echo '<fieldset id="resultadoProductos">
			<legend>Documentos Adjuntos</legend>
				<div data-linea="1">
					<label>Carnet de Vacunas: </label>';
					if($filaDocumento['ruta_vacuna'] !=""){
						echo '<a href="'. $filaDocumento['ruta_vacuna'].'" target="_blank" class="archivo_cargado"> Archivo Cargado</a>';
					}else{
						echo '<span class="alerta">No ha subido ningún archivo aún</span>';
					}
			echo'</div>
				<div data-linea="2">
					<label>Certificado Médico Veterinario: </label>';
						if($filaDocumento['ruta_veterinario'] !=""){
							echo '<a href="'. $filaDocumento['ruta_veterinario'].'" target="_blank" class="archivo_cargado"> Archivo Cargado</a>';
						}else{
							echo '<span class="alerta">No ha subido ningún archivo aún</span>';
						}
			echo'</div>
				<div data-linea="3">
					<label>Titulación Anticuerpos: </label>';
					if($filaDocumento['ruta_anticuerpo'] !=""){
						echo '<a href="'. $filaDocumento['ruta_anticuerpo'].'" target="_blank" class="archivo_cargado"> Archivo Cargado</a>';
					}else{
						echo '<span class="alerta">No ha subido ningún archivo aún</span>';
					}
			echo'</div>
				<div data-linea="4">
					<label>Autorización Ministerio del Ambiente: </label>';
					if($filaDocumento['ruta_autorizacion_min_ambiente'] !=""){
						echo '<a href="'. $filaDocumento['ruta_autorizacion_min_ambiente'].'" target="_blank" class="archivo_cargado"> Archivo Cargado</a>';
					}else{
						echo '<span class="alerta">No ha subido ningún archivo aún</span>';
					}
			echo'</div>';
			
		
		if($filaSolicitud['estado']=="subsanacion"){
			echo'
			<hr>
			<div style="width:100%">
				<label>* Carnet de Vacunas:</label>
					<input type="hidden" class="rutaArchivo" id="rutaVacuna" name="rutaVacuna" value="'.$filaDocumento['ruta_vacuna'].'" />
					<input type="file" class="archivo" id="archivoVacuna" accept="application/msword | application/pdf | image/*" disabled />
				<div class="estadoCarga">
					En espera de archivo... (Tamaño máximo '. ini_get('upload_max_filesize').'B)
				</div>
					<button type="button" id="cargarVacunas" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/mercanciasSinValorComercial/documentos" >Subir archivo</button>
			</div>
			<div style="width:100%">
				<hr>
				<label>* Certificado Médico Veterinario:</label>
					<input type="hidden" class="rutaArchivo" id="rutaVeterinario" name="rutaVeterinario" value="'.$filaDocumento['ruta_veterinario'].'" />
					<input type="file" class="archivo" id="archivoVeterinario" accept="application/msword | application/pdf | image/*" disabled />
				<div class="estadoCarga">En espera de archivo... (Tamaño máximo '. ini_get('upload_max_filesize') .'B)</div>
				<button type="button" id="cargarVeterinario" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/mercanciasSinValorComercial/documentos" >Subir archivo</button>
			</div>
			<div style="width:100%">
				<hr>
				<label>Titulación Anticuerpos:</label>
				<input type="hidden" class="rutaArchivo" name="rutaAnticuerpos" value="'.$filaDocumento['ruta_anticuerpo'].'" />
				<input type="file" class="archivo" id="archivoAnticuerpo" accept="application/msword | application/pdf | image/*" disabled />
				<div class="estadoCarga">En espera de archivo... (Tamaño máximo '. ini_get('upload_max_filesize') .'B)</div>
				<button type="button" id="cargarAnticuerpos" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/mercanciasSinValorComercial/documentos" >Subir archivo</button>
			</div>
			<div  style="width:100%">
				<hr>
				<label>Autorización del Ministerio del Ambiente:</label>
				<input type="hidden" class="rutaArchivo" name="rutaAutMinAmb" value="'.$filaDocumento['ruta_autorizacion_min_ambiente'].'"/>
				<input type="file" class="archivo" id="autMinAmb" accept="application/msword | application/pdf | image/*" disabled/>
				<div class="estadoCarga">En espera de archivo... (Tamaño máximo '. ini_get('upload_max_filesize'). 'B)</div>
				<button type="button" id="cargarAutMinAmb" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/mercanciasSinValorComercial/documentos" >Subir archivo</button>
			</div>';
		}
		echo'</fieldset>';

	if($filaSolicitud['estado']!="subsanacion"){
		$res = $ce->obtenerOrdenPagoFactura($conexion,$idSolicitud);
		$orden= pg_fetch_assoc($res);
		if($orden['orden_pago']!=''){
			echo '
			<fieldset id="resultadoProductos">
				<legend>Documentos Contables</legend>
				<div data-linea="1">
					<label>Orden de Pago: </label>
					<a href="'. $orden['orden_pago'].'" target="_blank" class="archivo_cargado"> Archivo Cargado</a>
				</div>';
			if($orden['factura']!=""){
				echo'<div data-linea="2">
						<label>Factura: </label>
						<a href="'. $orden['factura'].'" target="_blank" class="archivo_cargado"> Archivo Cargado</a>
					 </div>';
			}
		echo'</fieldset>';
		}
	}

	if($filaSolicitud['estado']!="subsanacion"){
		$res = $ce->obtenerCertificadoZoosanitario($conexion,$idSolicitud);
		$certificado= pg_fetch_assoc($res);
		if($certificado['ruta_zoosanitario']!=''){
			echo '
			<fieldset id="resultadoProductos">
				<legend>Certificado Zoosanitario</legend>
				<div data-linea="1">
					<label>Certificado Zoosanitario: </label>
					<a href="'. $certificado['ruta_zoosanitario'].'" target="_blank" class="archivo_cargado"> Archivo Cargado</a>
				</div>
			</fieldset>';
		}
	}

?>
</form>
<?php 
	if($filaSolicitud['estado']=="subsanacion"){
?>
	<form id="nuevoRegistro" data-rutaAplicacion="mercanciasSinValorComercial" data-opcion="guardarNuevoProducto">
		<input type="hidden" id="idRegistro" value="<?php echo $_POST['id']?>" name="idRegistro" />
		<input type="hidden" id="opcion" value="" name="opcion">
	
		<fieldset id="datosProducto">
			<legend>Datos del Producto </legend>
			<div data-linea="1">
				<label for="tipoProducto">* Tipo de Producto:</label>
				<select id="tipoProducto" name="tipoProducto" disabled="disabled" required="required">
					<option value="">Seleccione...</option>
					<?php
						$res= $cc->listarTipoProductosXAreaCodificacion($conexion, "SA","'PRD_MASCOTA'");
						while($fila=pg_fetch_assoc($res)){
							echo'<option value="'.$fila['id_tipo_producto'].'">'.$fila['nombre'].'</option>';
						}
					?>
				</select>
				<input type="hidden" id="nombreTipoProducto" name="nombreTipoProducto">
			</div>
			<div data-linea="2" id="resultadoSubTipo">
				<label for="subTipoProducto">* Subtipo:</label>
				<select id="subTipoProducto" name="subTipoProducto" disabled="disabled" required="required">
					<option value="">Seleccione</option>
				</select>
			</div>
			<div data-linea="2" id="resultadoProducto">
				<label for="producto">* Producto:</label>
				<select id="producto" name="producto" disabled="disabled" required="required">
					<option value="">Seleccione</option>
				</select>
			</div>
	
			<div data-linea="8">
				<label for="sexo">* Sexo:</label>
				<select id="sexo" name="sexo" disabled="disabled" required="required">
					<option value="">Seleccione</option>
					<option value="H">Hembra</option>
					<option value="M">Macho</option>
				</select>
			</div>
			<div data-linea="8">
				<label for="color">* Color: </label>
				<input type="text" id="color" name="color" onkeypress="return soloLetras(event)" disabled="disabled" required="required">
			</div>
			<div data-linea="8">
				<label for="edad">* Edad (Meses): </label>
				<input type="text" id="edad" name="edad" disabled="disabled" class="soloNumeros" required="required">
			</div>		
			<div data-linea="9">
				<label for="raza">* Raza: </label>
				<input type="text" id="raza" name="raza" onkeypress="return soloLetras(event)" disabled="disabled" required="required">
			</div>
			<div data-linea="9">
				<label for="identificacionProducto">* Identificación: </label>
				<input type="text" id="identificacionProducto" name="identificacionProducto" disabled="disabled" required="required">
			</div>
			<button class="mas" type="submit" disabled="disabled">Agregar</button>
		</fieldset>
	</form>
<?php 
	}
	$res=$ce->obtenerDetalleSolicitud($conexion, $idSolicitud);
	if($filaSolicitud['estado']=="subsanacion"){
		echo '<fieldset id="resultadoProductos">
				<legend>Productos registrados</legend>
	
				<table style="width:100%" id="registros">
					<thead>
						<tr>
							<th>Tipo</th>
							<th>Producto</th>
							<th>Identificación</th>
							<th>Acciones</th>
						</tr>
					</thead>';
					while ($fila=pg_fetch_assoc($res)) {
						echo $ce->imprimirLineaProducto($fila['id_producto_solicitud'], $fila['nombre_tipo'], $fila['nombre_comun'], $fila['identificacion_producto']);
					}
			echo '</table>
			</fieldset>';
	}else{
		$contador=0;
		while($fila=pg_fetch_assoc($res)){
			$contador+=1;
			echo '<fieldset id="datosProducto">
			<legend>Datos del Producto '.$contador.' </legend>'.
			'<div data-linea="1"><label>Tipo de Producto: </label>'.$fila['nombre_tipo'].'</div>'.
			'<div data-linea="2"><label>Subtipo: </label>'.$fila['nombre_subtipo'].'</div>'.
			'<div data-linea="2"><label>Producto: </label>'.$fila['nombre_comun'].'</div>'.
			'<div data-linea="3"><label>Sexo: </label>'.$fila['sexo_completo'].'</div>'.
			'<div data-linea="3"><label>Edad: </label>'.$fila['edad'].' meses</div>'.
			'<div data-linea="4"><label>Raza: </label>'.$fila['raza'].'</div>'.
			'<div data-linea="4"><label>Color: </label>'.$fila['color'].'</div>'.
			'<div data-linea="5"><label>Identificación: </label>'.$fila['identificacion_producto'].'</div>'.
			'</fieldset>';
		}
	}
?>

<script type="text/javascript">

$("document").ready(function(){

	var tipoIdentificacion = <?php echo json_encode($filaSolicitud['tipo_identificacion_propietario']); ?>;
	cargarValorDefecto("pais","<?php echo $filaSolicitud['id_localizacion_origen_destino'];?>");
	cargarValorDefecto("uso","<?php echo $filaSolicitud['id_uso'];?>");
	cargarValorDefecto("puestoControl","<?php echo $filaSolicitud['id_lugar_control'];?>");
	cargarValorDefecto("tipoIdentificacion",tipoIdentificacion);	

	$("#identificacionPropietario").attr("maxlength","16");	
	$("#resultadoDetalleProducto").hide();
	$("#fechaEmbarque").attr("readonly",true);
	$("#nombrePropietario").attr('maxlength','256');
	$("#direccionPropietario").attr('maxlength','256');
	$("#nombreDestinatario").attr('maxlength','256');
	$("#direccionDestinatario").attr('maxlength','256');
	$(".icono").attr("disabled","disabled");

	$("#fechaEmbarque").datepicker({
	      changeMonth: true,
	      changeYear: true,
	      minDate:"0",
	      dateFormat: 'yy-mm-dd'
	});

	$(".subirArchivo").attr("disabled",true);

	if(tipoIdentificacion == "04"){
		$("#identificacionPropietario").attr("maxlength","13");
		$("#clasificacion").val("Natural");
	}else if(tipoIdentificacion == "05"){
		$("#identificacionPropietario").attr("maxlength","10");
		$("#clasificacion").val("Cédula");
	}else if(tipoIdentificacion == "06"){
		$("#clasificacion").val("Pasaporte");
	}

	distribuirLineas();
	construirValidador();
	acciones();
});

$(".soloNumeros").on("keypress keyup blur",function (event) {
    $(this).val($(this).val().replace(/[^\d].+/, ""));
     if ((event.which < 48 || event.which > 57)) {
         if(event.which != 8)
         event.preventDefault();
     }
});

function soloLetras(e){
    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key).toLowerCase();
    letras = " áéíóúäëïöüabcdefghijklmnñopqrstuvwxyz";
    especiales = "8-37-39-46";

    tecla_especial = false
    for(var i in especiales){
         if(key == especiales[i]){
             tecla_especial = true;
             break;
         }
     }
     if(letras.indexOf(tecla)==-1 && !tecla_especial){
         return false;
     }
 }

$("#modificar").click(function (event){
	$("#modificar").attr("disabled","disabled");
	$("#propietario").removeAttr("disabled");
	$("#actualizar").removeAttr("disabled");
	$("#tipoIdentificacion").removeAttr("disabled");
	$("#nombrePropietario").removeAttr("disabled");
	$("#identificacionPropietario").removeAttr("disabled");
	$("#direccionPropietario").removeAttr("disabled");
	$("#telefonoPropietario").removeAttr("disabled");
	$("#correoPropietario").removeAttr("disabled");
	$("#nombreDestinatario").removeAttr("disabled");
	$("#direccionDestinatario").removeAttr("disabled");
	$("#pais").removeAttr("disabled");
	$("#uso").removeAttr("disabled");
	$("#fechaEmbarque").removeAttr("disabled");
	$("#puestoControl").removeAttr("disabled");
	$("#archivoVacuna").removeAttr("disabled");
	$("#archivoVeterinario").removeAttr("disabled");
	$("#archivoAnticuerpo").removeAttr("disabled");
	$("#autMinAmb").removeAttr("disabled");
	$("#tipoProducto").removeAttr("disabled");
	$("#subTipoProducto").removeAttr("disabled");
	$("#producto").removeAttr("disabled");
	$("#sexo").removeAttr("disabled");
	$("#color").removeAttr("disabled");
	$("#edad").removeAttr("disabled");
	$("#raza").removeAttr("disabled");
	$("#identificacionProducto").removeAttr("disabled");
	$(".subirArchivo").removeAttr("disabled");
	$(".mas").removeAttr("disabled");
	$(".icono").removeAttr("disabled");
});

$("#cargarSolicitud").submit(function(event){
	event.preventDefault();
	$("#estado").html("");
	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if($.trim($("#tipoIdentificacion").val()) == "" ) {
		error=true;
		$("#tipoIdentificacion").addClass("alertaCombo");
		$("#estado").html("Por favor revise los campos obligatorios.").addClass('alerta');
	}

	if($.trim($("#telefonoPropietario").val()) == "" ) {
		error=true;
		$("#telefonoPropietario").addClass("alertaCombo");
		$("#estado").html("Por favor revise los campos obligatorios.").addClass('alerta');
	}

	if(!$.trim($("#correoPropietario").val()) || !esCampoValido("#correoPropietario")){
		error = true;
		$("#correoPropietario").addClass("alertaCombo");
	}

	if($.trim($("#nombrePropietario").val()) == "" ){
		error=true;
		$("#nombrePropietario").addClass("alertaCombo");
		$("#estado").html("Por favor revise los campos obligatorios.").addClass('alerta');
	}

	if($.trim($("#identificacionPropietario").val()) == "" ){
		error=true;
		$("#identificacionPropietario").addClass("alertaCombo");
		$("#estado").html("Por favor revise los campos obligatorios.").addClass('alerta');
	}

	if($("#identificacionPropietario").val() == "04" || $("#identificacionPropietario").val() == "05"){
		if($("#identificacionPropietario").val().length != $("#identificacionPropietario").attr("maxlength")){
			$("#identificacionPropietario").addClass("alertaCombo");
			$("#estado").html("Por favor verifique la longitud del campo.").addClass('alerta');
		}
	}

	if($.trim($("#direccionPropietario").val()) == "" ){
		error=true;
		$("#direccionPropietario").addClass("alertaCombo");
		$("#estado").html("Por favor revise los campos obligatorios.").addClass('alerta');
	}

	if($.trim($("#nombreDestinatario").val()) == "" ){
		error=true;
		$("#nombreDestinatario").addClass("alertaCombo");
		$("#estado").html("Por favor revise los campos obligatorios.").addClass('alerta');
	}

	if($.trim($("#direccionDestinatario").val()) == "" ){
		error=true;
		$("#direccionDestinatario").addClass("alertaCombo");
		$("#estado").html("Por favor revise los campos obligatorios.").addClass('alerta');
	}

	if($("#pais").val() == "" ){
		error=true;
		$("#pais").addClass("alertaCombo");
		$("#estado").html("Por favor revise los campos obligatorios.").addClass('alerta');
	}

	if($("#uso").val() == "" ){
		error=true;
		$("#uso").addClass("alertaCombo");
		$("#estado").html("Por favor revise los campos obligatorios.").addClass('alerta');
	}

	if($.trim($("#fechaEmbarque").val()) == "" ){
		error=true;
		$("#fechaEmbarque").addClass("alertaCombo");
		$("#estado").html("Por favor revise los campos obligatorios.").addClass('alerta');
	}

	if($("#puestoControl").val() == "" ){
		error=true;
		$("#puestoControl").addClass("alertaCombo");
		$("#estado").html("Por favor revise los campos obligatorios.").addClass('alerta');
	}

	var rows = document.getElementById('registros').rows.length;

	if(rows==1){
		error=true;
		$("#estado").html("Debe agregar al menos una mascota para enviar la solicitud.").addClass('alerta');
		$('#registros').addClass("alertaCombo");
	}

	if(!error){
		$("#cargarSolicitud").attr('data-destino', 'detalleItem');
		$("#cargarSolicitud").attr('data-opcion', 'actualizarSolicitud');
		ejecutarJson($(this));
	}
});

$("#pais").change(function(event){
	if($.trim($("#pais").val()) != "" ) {
		$("#nombrePais").val($("#pais option:selected").text());
	}
});

$("#uso").change(function(event){
	if($.trim($("#uso").val()) != "" ) {
		$("#nombreUso").val($("#uso option:selected").text());
	}
});

$("#puestoControl").change(function (event){
	if($.trim($("#puestoControl").val())!=""){
		$("#nombrePuestoControl").val($("#puestoControl option:selected").text());
		$("#idProvincia").val($("#puestoControl option:selected").attr("data-idprovincia"));
		$("#nombreProvincia").val($("#puestoControl option:selected").attr("data-provincia"));
	}
});

$('#cargarVacunas').click(function (event) {
	var fecha= obtenerFecha();
	$("#numeracionCarnetVacuna").val(fecha);
	cargarArchivos('#cargarVacunas',fecha,'_carnet_');
});

$('#cargarVeterinario').click(function (event) {	
	var fecha= obtenerFecha();
	$("#numeracionCertificadoMedico").val(fecha);
	cargarArchivos('#cargarVeterinario',fecha,'_certificadoVeterinario_');
});

$('#cargarAnticuerpos').click(function (event) {	
	var fecha= obtenerFecha();
	$("#numeracionTitularAnticuerpo").val(fecha);
    cargarArchivos('#cargarAnticuerpos',fecha,'_titularAnticuerpo_');
});

$('#cargarAutMinAmb').click(function (event) {
	var fecha= obtenerFecha();
	$("#numeracionAutMinAmb").val(fecha);
    cargarArchivos('#cargarAutMinAmb',$("#numeracionAutMinAmb").val(),'_autorizacionMinAmbiente_');
});


function cargarArchivos(button,numero,documento){
	var numero = numero;
	var usuario = $("#usuario").val();

    var boton = $(button);
    var archivo = boton.parent().find(".archivo");
    var rutaArchivo = boton.parent().find(".rutaArchivo");
    var extension = archivo.val().split('.');
    var estado = boton.parent().find(".estadoCarga");

    if (extension[extension.length - 1].toUpperCase() == 'PDF') {
    		subirArchivo(
	                archivo
	                , usuario+documento+numero
	                , boton.attr("data-rutaCarga")
	                , rutaArchivo
	                , new carga(estado, archivo, $("#no"))
	            );
    		$(archivo).removeClass("alertaCombo")
    } else {
        estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
        archivo.val("");
    }
}

function obtenerFecha(){	
	var fecha = new Date();
	var dd=("00" + fecha.getDate()).slice (-2); 
	var mm=("00" + (fecha.getMonth()+1)).slice (-2); 
	var yy=fecha.getFullYear();	
	var hh=fecha.getHours();
	var mi=fecha.getMinutes();
	var ss=fecha.getSeconds();
	var fechaFinal=yy+"-"+mm+"-"+dd+"_"+hh+"-"+mi+"-"+ss;
	return fechaFinal;
}

$("#tipoProducto").change(function(event){
	if($("#tipoProducto").val()!=""){
		$("#nuevoRegistro").attr('data-destino', 'resultadoSubTipo');
	    $("#nuevoRegistro").attr('data-opcion', 'comboExportacion');
		$("#opcion").val("subtipo");
		$("#nombreTipoProducto").val($("#tipoProducto option:selected").text());
		abrir($("#nuevoRegistro"), event, false);
	}
});

$("#tipoIdentificacion").change(function(event){

	$("#identificacionPropietario").val('');

	if($('#tipoIdentificacion').val() == '04'){
		$("#identificacionPropietario").attr("maxlength","13");
		$("#clasificacion").val("Natural");
	}

	if($('#tipoIdentificacion').val() == '05'){
		$("#identificacionPropietario").attr("maxlength","10");
		$("#clasificacion").val("Cédula");
	}

	if($('#tipoIdentificacion').val() == '06'){
		$("#clasificacion").val("Pasaporte");
	}

});

function esCampoValido(elemento){
	var patron = new RegExp($(elemento).attr("data-er"),"g");
	return patron.test($(elemento).val());
}

$("#identificacionPropietario").change(function(event){
	if($("#tipoIdentificacion").val() != ''){
		$("#numero").val($("#identificacionPropietario").val());
		event.preventDefault();
		var $botones = $("#cargarSolicitud").find("button[type='submit']"),
		serializedData = $("#cargarSolicitud").serialize(),
		url = "aplicaciones/general/consultaWebServices.php";
		//url = "aplicaciones/general/consultaValidarIdentificacion.php";
		$botones.attr("disabled", "disabled");
		resultado = $.ajax({
			url: url,
			type: "post",
			data: serializedData,
			dataType: "json",
			async:   true,
			beforeSend: function(){
				$("#estado").html('').removeClass();
				$("#mensajeCargando").html("<div id='cargando'>Cargando...</div>").fadeIn();
			},
			
			success: function(msg){
				if(msg.estado=="exito"){
					$botones.removeAttr("disabled");
				}else{
					mostrarMensaje(msg.mensaje,"FALLO");
				}
			},
			error: function(jqXHR, textStatus, errorThrown){
				$("#cargando").delay("slow").fadeOut();
				mostrarMensaje("ERR: " + textStatus + ", " +errorThrown,"FALLO");
			},
			complete: function(){
				$("#cargando").delay("slow").fadeOut();
			}
		});
	}else{
		$("#estado").html("Por favor seleccione un tipo de identificación.").addClass('alerta');
	}
});

</script>