<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorImportaciones.php';
require_once '../../clases/ControladorDestinacionAduanera.php';

$conexion = new Conexion();
$cr = new ControladorRegistroOperador();
$cc = new ControladorCatalogos();
$ci = new ControladorImportaciones();
$cd = new ControladorDestinacionAduanera();

$fecha1= date('Y-m-d - H-i-s');
$fecha = str_replace(' ', '', $fecha1);

$usuario = $_SESSION['usuario'];

//Obtener lista de solicitudes de importacion
$qImportaciones = $ci->listarImportacionesOperador($conexion, $usuario);

while ($fila = pg_fetch_assoc($qImportaciones)){
	if($fila['estado'] == 'aprobado'){
		$importaciones[] =  array(idImportacion=>$fila['id_importacion'], codigoCertificado=>$fila['codigo_certificado'], tipoCertificado=>$fila['tipo_certificado']);
	}
}

//Obtener lista de paises
$paises = $cc->listarSitiosLocalizacion($conexion,'PAIS');
	
//Obtener listado de Puertos Ecuador

$paisOrigen = pg_fetch_assoc($cc->obtenerIdLocalizacion($conexion, 'ECUADOR', 'PAIS'));

$qPuertoEcuador = $cc->listarPuertosPorPais($conexion, $paisOrigen['id_localizacion']);

while ($fila = pg_fetch_assoc($qPuertoEcuador)){
	$puertoEcuador[] =  array(idPuerto=>$fila['id_puerto'], nombre=>$fila['nombre_puerto'], pais=>$fila['id_pais']);
}

?>
<header>
	<h1>Nuevo documento destinación aduanera</h1>
</header>

	<!-- <form id='nuevoDDA' data-rutaAplicacion='destinacionAduanera' data-opcion='comboPuertos' data-destino="comboPuertoEmbarque" data-accionEnExito="ACTUALIZAR">-->
	<form id='nuevoDDA' data-rutaAplicacion='destinacionAduanera' data-opcion='buscarImportacion' data-destino="informacionImportacion" data-accionEnExito="ACTUALIZAR">
	<div id="estado"></div>
	
	<div class="pestania">
	
	<input type="hidden" id="identificador" name="identificador" value="<?php echo $usuario?>" />
	<input type=hidden id="fecha" name="fecha" value="<?php echo $fecha;?>" />
		
		<fieldset>
			<legend>Datos Generales</legend>
				<div data-linea="1">			
					<label>Propósito</label> 
					<select id="proposito" name="proposito" >
						<option value="" data-area="">Propósito....</option>
						<option value="Importación">Importación</option>
						<option value="Tránsito internacional">Tránsito internacional</option>
					</select>
				</div>
			
				<div data-linea="2">
					<label>Tipo Solicitud</label> 
					<select id="tipoSolicitud" name="tipoSolicitud" >
						<option value="" data-area="" data-certificado="">Tipo de solicitud....</option>
						<option value="DDA Animal" data-area="SA" data-certificado="Permiso Zoosanitario de Importación">DDA Animal</option>
						<option value="DDA Vegetal" data-area="SV" data-certificado="Permiso Fitosanitario de Importación">DDA Vegetal</option>
					</select>
					
					<input type="hidden" id="idTipoSolicitud" name="idTipoSolicitud" />
				</div>
				
				<div data-linea="2">
					<label>Categoría producto</label> 
					<select id="categoriaProducto" name="categoriaProducto" >
						<option value="" data-area="">Categoría producto....</option>
						<option value="No procesados">No procesados</option>
						<option value="Procesados y semiprocesados">Procesados y semiprocesados</option>
					</select>
					
					<input type="hidden" id="idCategoriaProducto" name="idCategoriaProducto" />
				</div> 
		</fieldset>
		
		<fieldset>
			<legend>Información de Importación</legend>
				<div data-linea="1">			
					<label>Permiso importación</label>
						<select id="permisoImportacion" name="permisoImportacion">
							<option value="">Seleccione....</option>
						</select> 
				</div>
				
				<div data-linea="2">			
					<label>Certificado exportación</label> 
						<input type="text" id="permisoExportacion" name="permisoExportacion" placeholder="1815161232" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü.#-/ ]+$" />
				</div>
			
				<div data-linea="3">
					<label># Carga</label> 
						<input type="text" id="carga" name="carga" placeholder="Ej: 123" data-er="^[0-9]+$" />
				</div>
				
				<div data-linea="3">
					<label># Doc. transporte</label> 
						<input type="text" id="documentoTransporte" name="documentoTransporte" placeholder="Ej: 123" data-er="^[0-9]+$" />
				</div>

				<div data-linea="5">			
					<label>Lugar de inspección</label> 
					<select id="lugarInspeccion" name="lugarInspeccion" >
						<option value="" data-area="">Lugar de inspección....</option>
						<option value="1">Lugar 1</option>
					</select>
					
					<input type="hidden" id="nombreLugarInspeccion"  name="nombreLugarInspeccion" />
				</div>
			
				<div data-linea="6">
				<label>Observaciones</label> 
					<input type="text" id="observacion" name="observacion" placeholder="Ej: La carga incluye o necesita...." data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü.#-/°0-9 ]+$" />
				</div> 
		</fieldset>
	</div>
	
	
	
	<div class="pestania">
		
		<div id="informacionImportacion"></div>
	
	</div>
	
	<div class="pestania">	
			<fieldset id="documentosSA">
				<legend>Documentación requerida para Animales</legend>
					<div data-linea="1">
						<label>Certificado de predio de cuarentena: </label>
							<input type="file" name="certificadoPredioCuarentenaSA" id="certificadoPredioCuarentenaSA" accept="application/pdf"/>
							<input type="hidden" id="archivoCertificadoPredioCuarentenaSA" name="archivoCertificadoPredioCuarentenaSA" value="0"/> 
					</div>
			</fieldset>
			
			<fieldset id="documentosSV">
				<legend>Documentación requerida para Vegetales</legend>
					<div data-linea="1">
						<label>Certificado de predio de cuarentena: </label>
							<input type="file" name="certificadoPredioCuarentenaSV" id="certificadoPredioCuarentenaSV" accept="application/pdf"/>
							<input type="hidden" id="archivoCertificadoPredioCuarentenaSV" name="archivoCertificadoPredioCuarentenaSV" value="0"/> 
					</div>
			</fieldset>
			
			<p class="nota">Por favor revise que la información ingresada sea correcta. Una vez enviada no podrá ser modificada.</p>
			<button type="submit" class="guardar">Guardar solicitud</button> 
	</div>
</form>
	
<script type="text/javascript">
	var array_producto = <?php echo json_encode($productoAutorizado); ?>;
	var array_importacionesAprobadas = <?php echo json_encode($importaciones); ?>;

	$(document).ready(function(){
		distribuirLineas();
		construirAnimacion($(".pestania"));	
		construirValidador();
		$('button.bsig').attr("disabled","disabled");
		$("#documentosSA").hide();
		$("#documentosSV").hide();
	});

	$("#permisoImportacion").change(function(){	
		//Consultar en esquema de importaciones la correspondiente y cargar valores.
		if($("#permisoImportacion").val() != ''){
			$('button.bsig').removeAttr("disabled");
			$('button.bsig').last().attr("disabled","disabled");
			$("#nuevoDDA").attr('data-opcion','buscarImportacion');
			$("#nuevoDDA").attr('data-destino','informacionImportacion');
			abrir($("#nuevoDDA"),event,false); //Se ejecuta ajax, busqueda de informacion solicitud importacion
		}else{
			$('button.bsig').attr("disabled","disabled");
			$("#estado").html('El código ingresado no es válido').addClass('alerta');
			$("#permisoImportacion").val('');
		}		 		
	});

	$("#tipoSolicitud").change(function(){			
		iimportaciones = '0';
		iimportaciones = '<option value="">Seleccione....</option>';
		
		for (var i = 0; i < array_importacionesAprobadas.length; i++){
			if($("#tipoSolicitud option:selected").attr('data-certificado') == array_importacionesAprobadas[i]['tipoCertificado']){
				iimportaciones += '<option value="' + array_importacionesAprobadas[i]['idImportacion'] + '" >' + array_importacionesAprobadas[i]['tipoCertificado'] + ' - ' + array_importacionesAprobadas[i]['codigoCertificado'] + '</option>';
			}
		}

		$("#permisoImportacion").html(iimportaciones);

		if($("#tipoSolicitud option:selected").attr('data-area') == 'SA'){
			$("#documentosSA").show();
			$("#documentosSV").hide();
		}else{
			$("#documentosSV").show();
			$("#documentosSA").hide();
		}
	});

	$("#puertoDestino").change(function(){	
		$('#nombrePuertoDestino').val($("#puertoDestino option:selected").text());
	});

	$("#lugarInspeccion").change(function(){	
		$('#nombreLugarInspeccion').val($("#lugarInspeccion option:selected").text());
	});


/////////////////////// VALIDACION ////////////////////////

	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	$("#nuevoDDA").submit(function(event){
		$("#nuevoDDA").attr('data-opcion','guardarNuevoDDA');
		$("#nuevoDDA").attr('data-destino','detalleItem');
		event.preventDefault();
		chequearCamposExportacion(this);		
	});
//----------------------------------------------------------------------------------------------------------------------------

	/////////////////////// VALIDACION ////////////////////////

	function chequearCamposExportacion(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false; 

		if(!$.trim($("#proposito").val())){
			error = true;
			$("#proposito").addClass("alertaCombo");
		}

		if(!$.trim($("#tipoSolicitud").val())){
			error = true;
			$("#tipoSolicitud").addClass("alertaCombo");
		}

		if(!$.trim($("#categoriaProducto").val())){
			error = true;
			$("#categoriaProducto").addClass("alertaCombo");
		}

		if(!$.trim($("#permisoImportacion").val())){
			error = true;
			$("#permisoImportacion").addClass("alertaCombo");
		}

		if(!$.trim($("#permisoExportacion").val())){
			error = true;
			$("#permisoExportacion").addClass("alertaCombo");
		}

		if(!$.trim($("#carga").val()) || !esCampoValido("#carga")){
			error = true;
			$("#carga").addClass("alertaCombo");
		}

		if(!$.trim($("#documentoTransporte").val()) || !esCampoValido("#documentoTransporte")){
			error = true;
			$("#documentoTransporte").addClass("alertaCombo");
		}

		if(!$.trim($("#lugarInspeccion").val())){
			error = true;
			$("#lugarInspeccion").addClass("alertaCombo");
		}

		if(!$.trim($("#observacion").val()) || !esCampoValido("#observacion")){
			error = true;
			$("#observacion").addClass("alertaCombo");
		}

		if($("#tipoSolicitud option:selected").attr('data-area') == 'SA'){
			if($("#archivoCertificadoPredioCuarentenaSA").val() == 0){
				error = true;
				$("#certificadoPredioCuarentenaSA").addClass("alertaCombo");
			}
		}

		if($("#tipoSolicitud option:selected").attr('data-area') == 'SV'){
			if($("#archivoCertificadoPredioCuarentenaSV").val() == 0){
				error = true;
				$("#certificadoPredioCuarentenaSV").addClass("alertaCombo");
			}
		}

		if (!error){
			ejecutarJson(form);
		}else{
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}
	}

	

///////////////////////////////////////////// ADMINISTRACION DE DOCUMENTOS ////////////////////////////////////////////////////
	
	//SANIDAD ANIMAL
	$('#certificadoPredioCuarentenaSA').change(function(event){
		
		$("#estado").html('');
		var archivo = $("#certificadoPredioCuarentenaSA").val();
		var extension = archivo.split('.');

		if(extension[extension.length-1].toUpperCase() == 'PDF'){
			subirArchivo('certificadoPredioCuarentenaSA',$("#identificador").val()+'_archivoCertificadoPredioCuarentenaSA_'+$("#fecha").val().replace(/[_\W]+/g, "-"),'aplicaciones/destinacionAduanera/archivosAdjuntos', 'archivoCertificadoPredioCuarentenaSA');
		}else{
			$("#estado").html('Formato incorrecto, por favor solo se permiten archivos en formato PDF').addClass("alerta");
			$('#certificadoPredioCuarentenaSA').val('');
		}
	});

	//SANIDAD VEGETAL
	$('#certificadoPredioCuarentenaSV').change(function(event){
		
		$("#estado").html('');
		var archivo = $("#certificadoPredioCuarentenaSV").val();
		var extension = archivo.split('.');

		if(extension[extension.length-1].toUpperCase() == 'PDF'){
			subirArchivo('certificadoPredioCuarentenaSV',$("#identificador").val()+'_archivoCertificadoPredioCuarentenaSV_'+$("#fecha").val().replace(/[_\W]+/g, "-"),'aplicaciones/destinacionAduanera/archivosAdjuntos', 'archivoCertificadoPredioCuarentenaSV');
		}else{
			$("#estado").html('Formato incorrecto, por favor solo se permiten archivos en formato PDF').addClass("alerta");
			$('#certificadoPredioCuarentenaSV').val('');
		}
	});
</script>