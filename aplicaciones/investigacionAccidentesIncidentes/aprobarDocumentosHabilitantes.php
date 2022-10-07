<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAccidentesIncidentes.php';

$conexion = new Conexion();
$cai = new ControladorAccidentesIndicentes();

$identificador=$_SESSION['usuario'];
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
	data-opcion="actualizarEstadoDocumentos" data-accionEnExito="ACTUALIZAR">
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
	
		<br>
		<div data-linea="3">
			<label>* Copia de Cédula y Papeleta de Votación de la Persona que
				Reporta:</label>
		</div>
			<?php 
			    echo $datosCierreCaso['archivo_cedula_papeleta_reporta']=='0' ? '<span class="alerta">No ha subido ningún archivo.</span>':'<a href="'.$datosCierreCaso['archivo_cedula_papeleta_reporta'].'" target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>';?>
				
		
		<br>
		<div data-linea="5">
			<label>* Informe Ampliado Firmado y con Sello de la Persona que
				Reporta:</label>
		</div>
		<br>
			<?php 
			    echo $datosCierreCaso['archivo_informe_reporte']=='0' ? '<span class="alerta">No ha subido ningún archivo.</span>':'<a href="'.$datosCierreCaso['archivo_informe_reporte'].'" target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>';?>
				
	</fieldset>

		<fieldset>
			<legend>Resultado</legend>
			<div data-linea="1">
				<label>Observación:</label> <input type="text" id="observacion"
					name="observacion" value="" maxlength="127"/>
			</div>
			<div data-linea="2">
				<label>Resultado:</label> <select name="resultado" id="resultado">
					<option value="">Seleccione...</option>
					<option value="Aprobado">Aprobado</option>
					<option value="Subsanar">Subsanar</option>
				</select>
			</div>
		</fieldset>

	<button id="guardarForm" type="submit" class="guardar">Guardar</button>
</form>

<script type="text/javascript">
var cedula= <?php echo json_encode($valores_accidentes['identificador_accidentado']); ?>;
	
	$("#guardarCierreCaso").submit(function(event){
		event.preventDefault();
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
		
		if($("#resultado").val()==""){
			error = true;
			$("#resultado").addClass("alertaCombo");
		}
		if (!error){
			ejecutarJson($(this));
		}else{
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
			}
});
	
	$(document).ready(function(){
		construirValidador();
		distribuirLineas();
		construirAnimacion($(".pestania"));
		
	}); 

</script>
