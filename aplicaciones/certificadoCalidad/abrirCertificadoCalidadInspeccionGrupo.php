<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorCertificadoCalidad.php';

$conexion = new Conexion();
$cca = new ControladorCertificadoCalidad();
$cc = new ControladorCatalogos();

$identificadorUsuario = $_SESSION['usuario'];
$estado = 'enviado';

$idSolicitud = ($_POST['elementos']==''?$_POST['id']:$_POST['elementos']);

$qCertificadoCalidad = $cca->obtenerSolicitudCertificadoCalidadXGrupoLotes($conexion, $idSolicitud);


$empresasVerificadoras = $cca->obtenerEmpresasVerificadoras($conexion);

?>

<header>
	<h1>Solicitud certificado de calidad</h1>
</header>

	<div id="estado"></div>
	
	<div class="pestania">

<?php 
	
	while ($certificadoCalidad = pg_fetch_assoc($qCertificadoCalidad)){
		
		echo'<fieldset>
				<legend>Solicitud # '.$certificadoCalidad['id_certificado_calidad'].' </legend>
		
				<div data-linea="1">
					<label>Datos exportador </label>
				</div>
			
				<div data-linea="2">
					<label>Identificación: </label> '. $certificadoCalidad['identificador_exportador'].'
				</div>
				
				<div data-linea="3">
					<label>Razón social: </label> '. $certificadoCalidad['razon_social_exportador'].'
				</div>
		
				<hr/>
		
				<div data-linea="4">
					<label>Datos del importador</label>
				</div>
			
				<div data-linea="5">
					<label>Nombre: </label> '. $certificadoCalidad['nombre_importador'].'
				</div>
				
				<div data-linea="6">
					<label>Dirección: </label> '. $certificadoCalidad['direccion_importador'].'
				</div>
				
				<hr/>
				
				<div data-linea="7">
					<label>Datos generales de exportación</label>
				</div>
				
				<div data-linea="8">
					<label>Fecha de embarque: </label> '. date('j/n/Y',strtotime($certificadoCalidad['fecha_embarque'])).'
				</div>
				
				<div data-linea="8">
					<label>Número de viaje: </label> '. $certificadoCalidad['numero_viaje'].'
				</div>
				
				<div data-linea="9">
					<label>País de embarque: </label> '. $certificadoCalidad['nombre_pais_embarque'].'
				</div>
				
				<div data-linea="9">
					<label>Puerto embarque: </label> '. $certificadoCalidad['nombre_puerto_embarque'].'
				</div>
				
				<div data-linea="10">
					<label>Medio de transporte: </label> '. $certificadoCalidad['nombre_medio_transporte'].'
				</div>
				
				<div data-linea="11">
					<label>País de destino: </label> '. $certificadoCalidad['nombre_pais_destino'].'
				</div>
				
				<div data-linea="11">
					<label>Puerto de destino: </label> '. $certificadoCalidad['nombre_puerto_destino'].'
				</div>';
						
				$lugarCertificadoCalidad = $cca->obtenerLugarXGrupoLotes($conexion, $idSolicitud, $certificadoCalidad['id_certificado_calidad']);
	
				$i = 20;
				
				while ($lugarCertificado = pg_fetch_assoc($lugarCertificadoCalidad)){
					
				echo '
						<hr/>
							<div data-linea='.++$i.'>
								<label class="mayusculas">Lugar de inspección '.$lugarCertificado['nombre_area_operacion'].'</label>
							</div>
						<hr/>
					
							<div data-linea='.++$i.'>
								<label>Nombre provincia: </label> '. $lugarCertificado['nombre_provincia'].'
							</div>
					
							<div data-linea='.$i.'>
								<label>Fecha de inspección: </label> '.  date('j/n/Y',strtotime($lugarCertificado['solicitud_fecha_inspeccion'])).'
							</div>
					
						';
				
						$loteCertificadoInspeccion = $cca->obtenerLoteCertificadoCalidad($conexion, $idSolicitud, $lugarCertificado['id_lugar_inspeccion']);
						
						$cantidadRegistros = pg_num_rows($loteCertificadoInspeccion);
						
						$aux = 0;
						while($loteCertificado = pg_fetch_assoc($loteCertificadoInspeccion)){
						
							$aux++;
							echo '
							<div data-linea='.++$i.'>
								<label>Nombre producto: '.$loteCertificado['nombre_producto'].'</label>
							</div>
								
							<div data-linea='.++$i.'>
								<label>Número lote: </label> '. $loteCertificado['numero_lote'].'
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
							</div>';
						
							if($cantidadRegistros != $aux){
								echo '<hr/>';
							}
						}	
				}
						
				echo'</fieldset>';
		}

?>


</div>

<!-- SECCION DE REVISIÓN DE PRODUCTOS Y ÁREAS PARA IMPORTACION -->

<div class="pestania">	
	<form id="evaluarDocumentosSolicitud" data-rutaAplicacion="revisionFormularios" data-opcion="evaluarElementosSolicitud" data-accionEnExito="ACTUALIZAR">
		<input type="hidden" name="inspector" value="<?php echo $identificadorUsuario;?>"/> <!-- INSPECTOR -->
		<input type="hidden" name="idSolicitud" value="<?php echo $idSolicitud;?>"/>
		<input type="hidden" name="tipoSolicitud" value="certificadoCalidad"/>
		<input type="hidden" name="tipoInspector" value="Técnico"/>
		<input type="hidden" name="tipoElemento" value="Lotes"/>
		
		<fieldset>
			<legend>Resultado de Revisión</legend>
					
				<div data-linea="1">
					<label>Resultado</label>
						<select id="resultado" name="resultado">
							<option value="">Seleccione....</option>
							<option value="aprobado">Aprobado</option>
							<option value="rechazado">Rechazado</option>
						</select>
				</div>
					
				<div data-linea="2">
					<label>Observaciones</label>
					<input type="text" id="observacion" name="observacion"/>
				</div>
				
		</fieldset>
				
		<button type="submit" class="guardar">Enviar resultado</button>		
	</form> 
	
	
</div>    

<script type="text/javascript">

	$(document).ready(function(){
		distribuirLineas();
		construirAnimacion($(".pestania"));	
	});

	
	$("#evaluarDocumentosSolicitud").submit(function(event){
		event.preventDefault();
		chequearCamposInspeccionDocumental(this);
	});

	function chequearCamposInspeccionDocumental(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
	
		if(!$.trim($("#resultado").val()) || !esCampoValido("#resultado")){
			error = true;
			$("#resultado").addClass("alertaCombo");
		}

		
		if(!$.trim($("#observacion").val()) || !esCampoValido("#observacion")){
			error = true;
			$("#observacion").addClass("alertaCombo");
		}
		
		
		if (error){
			$("#estado").html("Por favor revise la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson(form);
		}
	}
</script>
