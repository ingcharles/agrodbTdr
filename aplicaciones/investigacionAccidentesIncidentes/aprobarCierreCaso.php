<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAccidentesIncidentes.php';

//poner en todos los combos style="width:100%"

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
	data-opcion="actualizarEstadoCierreCaso" data-accionEnExito="ACTUALIZAR">
	<input type="hidden" id="solicitud" name="solicitud"
		value="<?php echo $solicitud;?>" />
	<div id="estado"></div>
	<input type="hidden" id="identificadorAccidentado"
		name="identificadorAccidentado"
		value="<?php echo $valores_accidentes['identificador_accidentado'];?>" />

	<div id="reporte">
		<?php 
			if($filaSolicitud['estado'] == 'InformeGenerado'){
					echo '<embed id="visor" src='.$rutaArchivo.' width="540" height="490">';
			}
		?>
	</div>
	
	<fieldset>
		<legend>Documentos Cierre del Caso</legend>
		<div data-linea="1">
			<label>* Documentación Emitida por la Unidad de Riesgos del Trabajo
				del IESS:</label>
		</div>
			<?php 
			    echo $datosCierreCaso['archivo_unidad_riesgos_iess']=='0' ? '<span class="alerta">No ha subido ningún archivo.</span>':'<a href="'.$datosCierreCaso['archivo_unidad_riesgos_iess'].'" target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>';?>
		
		<div data-linea="3">
			<label>* Certificado Médico:</label>
		</div>
			<?php 
			    echo $datosCierreCaso['archivo_certificado_medico']=='0' ? '<span class="alerta">No ha subido ningún archivo.</span>':'<a href="'.$datosCierreCaso['archivo_certificado_medico'].'" target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>';?>
			
		<div data-linea="5">
			<label>* ¿Existió Responsabilidad Patronal?: </label><select
				name="responPatron" id="responPatron">
				<option value="<?php echo $datosCierreCaso['responsabilidad'];?>"><?php echo $datosCierreCaso['responsabilidad'];?></option>
			</select>
		</div>
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
	<button id="btnGenerarAccion" type="submit" class="guardar">Guardar</button>
	
</form>

<script type="text/javascript">
var cedula= <?php echo json_encode($valores_accidentes['identificador_accidentado']); ?>;
	
		
	$(document).ready(function(){
		construirValidador();
		distribuirLineas();
		construirAnimacion($(".pestania"));
		
	}); 

	$("#btnGenerarAccion").click(function (event) {
		   event.preventDefault();
		   $(".alertaCombo").removeClass("alertaCombo");
			var error = false;
			
			if($("#resultado").val()==""){
				error = true;
				$("#resultado").addClass("alertaCombo");
			}
			if (!error){
				ejecutarJson($("#guardarCierreCaso"));

				   var resultado = $("#estado").html();

				   if(resultado == 'Los datos han sido actualizados satisfactoriamente.'){
						$('#guardarCierreCaso').attr('data-opcion','generarFichaSso');
						$('#guardarCierreCaso').attr('data-destino','detalleItem');

						 abrir($("#guardarCierreCaso"),event,false);
				   }
			}else{
				$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
				}
	});
	
</script>
