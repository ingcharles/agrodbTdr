<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorCertificadoCalidad.php';

$conexion = new Conexion();
$cca = new ControladorCertificadoCalidad();
$cc = new ControladorCatalogos();

$idSolicitud = $_POST['id'];
$identificadorUsuario = $_SESSION['usuario'];

$certificadoCalidad = pg_fetch_assoc($cca->obtenerSolicitudCertificadoCalidad($conexion, $idSolicitud));

$empresasVerificadoras = $cca->obtenerEmpresasVerificadoras($conexion);



?>

<header>
	<h1>Solicitud certificado de calidad</h1>
</header>

	<div id="estado"></div>
	
	<div class="pestania">

	
	<fieldset>
			<legend>Datos del exportador</legend>
			
			<div data-linea="1">
				<label>Identificación: </label> <?php echo $certificadoCalidad['identificador_exportador']; ?> 
			</div>
			
			<div data-linea="2">
				<label>Razón social: </label> <?php echo $certificadoCalidad['razon_social_exportador']; ?> 
			</div>
			
	</fieldset>
	
	<fieldset>
			<legend>Datos del importador</legend>
			
			<div data-linea="1">
				<label>Nombre: </label> <?php echo $certificadoCalidad['nombre_importador']; ?> 
			</div>
			
			<div data-linea="2">
				<label>Dirección: </label> <?php echo $certificadoCalidad['direccion_importador']; ?> 
			</div>
			
	</fieldset>
	
	<fieldset>
		<legend>Datos generales de exportación</legend>
			<div data-linea="1">
				<label>Fecha de embarque: </label> <?php echo date('j/n/Y',strtotime($certificadoCalidad['fecha_embarque']));?> 
			</div>
			
			<div data-linea="1">
				<label>Número de viaje: </label> <?php echo $certificadoCalidad['numero_viaje']; ?> 
			</div>
			
			<div data-linea="2">
				<label>País de embarque: </label> <?php echo $certificadoCalidad['nombre_pais_embarque']; ?> 
			</div>
			
			<div data-linea="2">
				<label>Puerto embarque: </label> <?php echo $certificadoCalidad['nombre_puerto_embarque']; ?> 
			</div>
			
			<div data-linea="3">
				<label>Medio de transporte: </label> <?php echo $certificadoCalidad['nombre_medio_transporte']; ?> 
			</div>
			
			<div data-linea="4">
				<label>País de destino: </label> <?php echo $certificadoCalidad['nombre_pais_destino']; ?> 
			</div>
			
			<div data-linea="4">
				<label>Puerto de destino: </label> <?php echo $certificadoCalidad['nombre_puerto_destino']; ?> 
			</div>
	</fieldset>
	
	<?php 
	
		$lugarCertificadoCalidad = $cca->obtenerLugarCertificadoCalidad($conexion, $idSolicitud);
	
		while ($lugarCertificado = pg_fetch_assoc($lugarCertificadoCalidad)){
			
			echo '
				<fieldset>
					<legend>Lugar de inspección '.$lugarCertificado['nombre_area_operacion'].'</legend>
		
					<div data-linea="4">
						<label>Nombre provincia: </label> '. $lugarCertificado['nombre_provincia'].'
					</div>
			
					<div data-linea="4">
						<label>Fecha de inspección: </label> '.  date('j/n/Y',strtotime($lugarCertificado['solicitud_fecha_inspeccion'])).'
					</div>
		
				<hr/>';
			
				$loteCertificadoInspeccion = $cca->obtenerLoteCertificadoCalidad($conexion, $lugarCertificado['id_lugar_inspeccion']);
				
				$i = 20;
				while($loteCertificado = pg_fetch_assoc($loteCertificadoInspeccion)){

				echo '
					<div data-linea='.++$i.'>
						<label>Número lote: </label> '. $loteCertificado['numero_lote'].'
					</div>
					<div data-linea='.++$i.'>
						<label>Nombre producto: </label> '. $loteCertificado['nombre_producto'].'
					</div>
					<div data-linea='.$i.'>
						<label>Valor FOB: </label> '. $loteCertificado['valor_fob'].'
					</div>
					<div data-linea='.++$i.'>
						<label>Peso neto: </label> '. $loteCertificado['peso_neto'] .' '.$loteCertificado['unidad_peso_neto'].'
					</div>
					<div data-linea='.$i.'>
						<label>Peso bruto: </label> '. $loteCertificado['peso_bruto'].' '.$loteCertificado['unidad_peso_bruto'].'
					</div>
					<div data-linea='.++$i.'>
						<label>Variedad: </label> '. $loteCertificado['nombre_variedad_producto'].'
					</div>
					<div data-linea='.$i.'>
						<label>Calidad: </label> '. $loteCertificado['nombre_calidad_producto'].'
					</div>
					<hr/>
					';
					
					
				}
			
			echo '</fieldset>';
		}	
	
	?>
	
	
	

</div>

<!-- SECCION DE REVISIÓN DE PRODUCTOS Y ÁREAS PARA IMPORTACION -->

<div class="pestania">	
	<form id="evaluarDocumentosSolicitud" data-rutaAplicacion="revisionFormularios" data-opcion="evaluarDocumentosSolicitud" data-accionEnExito="ACTUALIZAR">
		<input type="hidden" name="inspector" value="<?php echo $identificadorUsuario;?>"/> <!-- INSPECTOR -->
		<input type="hidden" name="idSolicitud" value="<?php echo $idSolicitud;?>"/>
		<input type="hidden" name="tipoSolicitud" value="certificadoCalidad"/>
		<input type="hidden" name="tipoInspector" value="Documental"/>
		
		<fieldset>
			<legend>Resultado de Revisión</legend>
					
				<div data-linea="1">
					<label>Resultado</label>
						<select id="resultadoDocumento" name="resultadoDocumento">
							<option value="">Seleccione....</option>
							<option value="pago">Aprobar revisión documental</option>
							<option value="subsanacion">Subsanación</option>
						</select>
				</div>
					
				<div data-linea="2">
					<label>Observaciones</label>
					<input type="text" id="observacionDocumento" name="observacionDocumento"/>
				</div>
				
		</fieldset>
		
		<fieldset id="aprobarSolicitud">
			<legend>Empresa verificadora</legend>
			
			<div data-linea="1">
				<select id="empresaVerificadora" name="empresaVerificadora">
					<option value="">Seleccione....</option>
					<?php 					    
						while ($fila = pg_fetch_assoc($empresasVerificadoras)){
							echo '<option value="' . $fila['identificador'] . '">' . $fila['nombre'] . '</option>';							
						}
					?>
				</select> 
			</div>
			
			
		</fieldset>
		
		
		
		<button type="submit" class="guardar">Enviar resultado</button>		
	</form> 
	
	
</div>    

<script type="text/javascript">

	$(document).ready(function(){
		distribuirLineas();
		construirAnimacion($(".pestania"));		

		$('#aprobarSolicitud').hide();
	});

	$('#resultadoDocumento').change(function(event){
		if($('#resultadoDocumento').val() == 'pago'){
			$('#aprobarSolicitud').show();
		}else{
			$('#aprobarSolicitud').hide();
		}
	});

	$("#evaluarDocumentosSolicitud").submit(function(event){
		event.preventDefault();
		chequearCamposInspeccionDocumental(this);
	});

	function chequearCamposInspeccionDocumental(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
	
		if(!$.trim($("#resultadoDocumento").val()) || !esCampoValido("#resultadoDocumento")){
			error = true;
			$("#resultadoDocumento").addClass("alertaCombo");
		}

		
		if($("#resultadoDocumento").val()=='subsanacion'){
			if(!$.trim($("#observacionDocumento").val()) || !esCampoValido("#observacionDocumento")){
				error = true;
				$("#observacionDocumento").addClass("alertaCombo");
			}
		}else{
			if(!$.trim($("#empresaVerificadora").val()) || !esCampoValido("#empresaVerificadora")){
				error = true;
				$("#empresaVerificadora").addClass("alertaCombo");
			}
		}
		
		if (error){
			$("#estado").html("Por favor revise la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson(form);
		}
	}
</script>
