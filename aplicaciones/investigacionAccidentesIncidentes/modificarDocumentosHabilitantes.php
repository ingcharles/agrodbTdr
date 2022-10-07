<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAccidentesIncidentes.php';

//poner en todos los combos style="width:100%"

$conexion = new Conexion();
$cai = new ControladorAccidentesIndicentes();

$identificador=$_SESSION['usuario_seleccionado'];
$solicitud=$_POST['id'];
$valores_accidentes=pg_fetch_array($cai->listarDatosAccidente($conexion,'', '','','', '',$solicitud, ''));
$datosCierreCaso=pg_fetch_array($cai->buscarCierreCaso($conexion,$solicitud));


?>
<header>
	<h1>
		Solicitud #
		<?php echo $solicitud;?>
	</h1>
</header>

<form id="guardarCierreCaso"
	data-rutaAplicacion="investigacionAccidentesIncidentes"
	data-opcion="actualizarDocumentosHabilitantes" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" id="solicitud" name="solicitud"
		value="<?php echo $solicitud;?>" />
	<div id="estado"></div>
	<input type="hidden" id="identificadorAccidentado"
		name="identificadorAccidentado"
		value="<?php echo $valores_accidentes['identificador_accidentado'];?>" />

	<fieldset>
		<legend>Documentos Habilitantes</legend>
		<div data-linea="1">
			<label>* Copia de Cédula y Papeleta de Votación del Accidentado:</label>

		</div>
			<?php 
			    echo $datosCierreCaso['archivo_cedula_papeleta_accidentado']=='0' ? '<span class="alerta">No ha subido ningún archivo.</span>':'<a href="'.$datosCierreCaso['archivo_cedula_papeleta_accidentado'].'" target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>';?>
	
		<div data-linea="2">
			<input type="file" class="archivo" name="archivoCedPap"
				id="archivoCedPap" accept="application/pdf" /> <input type="hidden"
				class="rutaArchivo" name="cedulaPapeleta" id="cedulaPapeleta"
				value="<?php echo $datosCierreCaso['archivo_cedula_papeleta_accidentado'];?>" />
			<div class="estadoCarga">
				En espera de archivo... (Tamaño máximo
				<?php echo ini_get('upload_max_filesize'); ?>
				B)
			</div>
			<button type="button" class="subirArchivo adjunto" id="accidentadoCedulaPapeleta"
				onclick="subirArchivosPdf(id);"
				data-rutaCarga="aplicaciones/investigacionAccidentesIncidentes/archivos">Subir
				archivo</button>
		</div>
		<div data-linea="3">
			<label>* Copia de Cédula y Papeleta de Votación de la Persona que
				Reporta:</label>
		</div>
			<?php 
			    echo $datosCierreCaso['archivo_cedula_papeleta_reporta']=='0' ? '<span class="alerta">No ha subido ningún archivo.</span>':'<a href="'.$datosCierreCaso['archivo_cedula_papeleta_reporta'].'" target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>';?>
				
		
		<div data-linea="4">
			<input type="file" class="archivo" name="archivoCedPapRep"
				id="archivoCedPapRep" accept="application/pdf" /> <input
				type="hidden" class="rutaArchivo" name="cedulaPapeletaRep"
				id="cedulaPapeletaRep" value="<?php echo $datosCierreCaso['archivo_cedula_papeleta_reporta'];?>" />
			<div class="estadoCarga">
				En espera de archivo... (Tamaño máximo
				<?php echo ini_get('upload_max_filesize'); ?>
				B)
			</div>
			<button type="button" class="subirArchivo adjunto" id="reportaCedulaPapeleta"
				onclick="subirArchivosPdf(id);"
				data-rutaCarga="aplicaciones/investigacionAccidentesIncidentes/archivos">Subir
				archivo</button>
		</div>
		<div data-linea="5">
			<label>* Informe Ampliado Firmado y con Sello de la Persona que
				Reporta:</label>
		</div>
			<?php 
			    echo $datosCierreCaso['archivo_informe_reporte']=='0' ? '<span class="alerta">No ha subido ningún archivo.</span>':'<a href="'.$datosCierreCaso['archivo_informe_reporte'].'" target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>';?>
				
		
		<div data-linea="6">
			<input type="file" class="archivo" name="archivoInfo"
				id="archivoInfo" accept="application/pdf" /> <input type="hidden"
				class="rutaArchivo" name="infoReporte" id="infoReporte" value="<?php echo $datosCierreCaso['archivo_informe_reporte'];?>" />
			<div class="estadoCarga">
				En espera de archivo... (Tamaño máximo
				<?php echo ini_get('upload_max_filesize'); ?>
				B)
			</div>
			<button type="button" class="subirArchivo adjunto" id="informeReporte"
				onclick="subirArchivosPdf(id);"
				data-rutaCarga="aplicaciones/investigacionAccidentesIncidentes/archivos">Subir
				archivo</button>
		</div>
	</fieldset>

	<fieldset>
		<legend>Resultado</legend>
		<div data-linea="1">
			<label>Observación: </label><input type="text" readonly value="<?php echo $valores_accidentes['observacion'];?>" /> 
		</div>
	</fieldset>
	<button id="guardarForm" type="submit" class="guardar">Guardar</button>
</form>

<script type="text/javascript">
var cedula= <?php echo json_encode($valores_accidentes['identificador_accidentado']); ?>;
var solicitud =<?php echo json_encode($solicitud); ?>;
	
	$("#guardarCierreCaso").submit(function(event){
		event.preventDefault();
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
		
		if($("#cedulaPapeleta").val()==""){
			error = true;
			$("#archivoCedPap").addClass("alertaCombo");
		}
		if($("#cedulaPapeletaRep").val()==""){
			error = true;
			$("#archivoCedPapRep").addClass("alertaCombo");
		}
		if($("#infoReporte").val()==""){
			error = true;
			$("#archivoInfo").addClass("alertaCombo");
		}
		if($("#docUnidadIess").val()==""){
			error = true;
			$("#unidadIess").addClass("alertaCombo");
		}
		if($("#docCertificadoMedico").val()==""){
			error = true;
			$("#certificadoMedico").addClass("alertaCombo");
		}
		if($("#responPatron").val()==""){
			error = true;
			$("#responPatron").addClass("alertaCombo");
		}
		if($("#resultado").val()==""){
			error = true;
			$("#resultado").addClass("alertaCombo");
		}
		if (!error){
			ejecutarJson($(this));
		}else{
			$("#estado").html("Todos los campos con ( * ) son obligatorios...!").addClass('alerta');
			}
});
	
	$(document).ready(function(){
		construirValidador();
		distribuirLineas();
		construirAnimacion($(".pestania"));
		
	}); 

	function subirArchivosPdf(id){
		    var boton = $("#"+id);
	        var archivo = boton.parent().find(".archivo");
	        var rutaArchivo = boton.parent().find(".rutaArchivo");
	        var extension = archivo.val().split('.');
	        var estado = boton.parent().find(".estadoCarga");
	        if (extension[extension.length - 1].toUpperCase() == 'PDF') {
	        		subirArchivo(
	    	                archivo
	    	                , solicitud+'_'+id+'_'+cedula
	    	                , boton.attr("data-rutaCarga")
	    	                , rutaArchivo
	    	                , new carga(estado, archivo, boton)
	    	            );
	        } else {
	            estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
	            archivo.val("");
	        }
	}
</script>
