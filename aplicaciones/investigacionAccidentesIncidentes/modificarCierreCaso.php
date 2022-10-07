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
	data-opcion="actualizarCierreCaso" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" id="solicitud" name="solicitud"
		value="<?php echo $solicitud;?>" />
	<div id="estado"></div>
	<input type="hidden" id="identificadorAccidentado"
		name="identificadorAccidentado"
		value="<?php echo $valores_accidentes['identificador_accidentado'];?>" />

	<fieldset>
		<legend>Documentos Cierre del Caso</legend>
		<div data-linea="1">
			<label>* Documentación Emitida por la Unidad de Riesgos del Trabajo
				del IESS:</label>
		</div>
			<?php 
			    echo $datosCierreCaso['archivo_unidad_riesgos_iess']=='0' ? '<span class="alerta">No ha subido ningún archivo.</span>':'<a href="'.$datosCierreCaso['archivo_unidad_riesgos_iess'].'" target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>';?>
				
		
		<div data-linea="2">
			<input type="file" class="archivo" name="unidadIess" id="unidadIess"
				accept="application/pdf" /> <input type="hidden" class="rutaArchivo"
				name="docUnidadIess" id="docUnidadIess" value="<?php echo $datosCierreCaso['archivo_unidad_riesgos_iess'];?>" />
			<div class="estadoCarga">
				En espera de archivo... (Tamaño máximo
				<?php echo ini_get('upload_max_filesize'); ?>
				B)
			</div>
			<button type="button" class="subirArchivo adjunto" id="documentacionIess"
				onclick="subirArchivosPdf(id);"
				data-rutaCarga="aplicaciones/investigacionAccidentesIncidentes/archivos">Subir
				archivo</button>
		</div>
		<div data-linea="3">
			<label>* Certificado Médico:</label>
		</div>
			<?php 
			    echo $datosCierreCaso['archivo_certificado_medico']=='0' ? '<span class="alerta">No ha subido ningún archivo.</span>':'<a href="'.$datosCierreCaso['archivo_certificado_medico'].'" target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>';?>
			
		
		<div data-linea="4">
			<input type="file" class="archivo" name="certificadoMedico"
				id="certificadoMedico" accept="application/pdf" /> <input
				type="hidden" class="rutaArchivo" name="docCertificadoMedico"
				id="docCertificadoMedico" value="<?php echo $datosCierreCaso['archivo_certificado_medico'];?>" />
			<div class="estadoCarga">
				En espera de archivo... (Tamaño máximo
				<?php echo ini_get('upload_max_filesize'); ?>
				B)
			</div>
			<button type="button" class="subirArchivo adjunto" id="certificadoMed"
				onclick="subirArchivosPdf(id);"
				data-rutaCarga="aplicaciones/investigacionAccidentesIncidentes/archivos">Subir
				archivo</button>
		</div>
		<div data-linea="5">
			<label>* ¿Existió Responsabilidad Patronal?: </label><select
				name="responPatron" id="responPatron">
				<option value="">Seleccione...</option>
				<option value="SI">Si</option>
				<option value="NO">No</option>
			</select>
		</div>
	</fieldset>
    <fieldset>
		<legend>Resultado</legend>
		<div data-linea="1">
			<label>Observación:</label> <input type="text" id="observacion"
				name="observacion" value="<?php echo $valores_accidentes['observacion'];?>" maxlength="127" readonly/>
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
		cargarValorDefecto("responPatron","<?php echo $datosCierreCaso['responsabilidad'];?>");
		
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
