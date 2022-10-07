<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAccidentesIncidentes.php';

$conexion = new Conexion();
$cai = new ControladorAccidentesIndicentes();

$codSolicitud=$_POST['id'];
$datosCierreCaso=pg_fetch_array($cai->buscarCierreCaso($conexion,$codSolicitud));

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>

<body>

	<embed id="visor" src="<?php echo  $datosCierreCaso['archivo_ficha_accidente_incidente']; ?>" width="540" height="490">
	<?php if($datosCierreCaso['archivo_unidad_riesgos_iess']!=''){
	echo '<br><br>';?>
	<fieldset>
			<legend>Documentos Adjunto de Respaldo</legend>
			<div data-linea="1">
				<label>Documentación Emitida por la Unidad de Riesgos del Trabajo
					del IESS:</label><br>
					<?php 
				    echo $datosCierreCaso['archivo_unidad_riesgos_iess']=='' ? '<span class="alerta">No ha subido ningún archivo.</span>':'<a href="'.$datosCierreCaso['archivo_unidad_riesgos_iess'].'" target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>';?>
		
			</div>
					<br>		
			<div data-linea="2">
				<label>Certificado Médico:</label><br>
				<?php 
				    echo $datosCierreCaso['archivo_certificado_medico']=='' ? '<span class="alerta">No ha subido ningún archivo.</span>':'<a href="'.$datosCierreCaso['archivo_certificado_medico'].'" target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>';?>
		
			</div>
					<br>
			
			<div data-linea="3">
				<label>Informe Ampliado Firmado y con Sello de la Persona que
					Reporta:</label><br>
					<?php 
				    echo $datosCierreCaso['archivo_informe_reporte']=='' ? '<span class="alerta">No ha subido ningún archivo.</span>':'<a href="'.$datosCierreCaso['archivo_informe_reporte'].'" target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>';?>
					
			</div>
			<br>
							
		</fieldset>
	<?php }?>
</body>
</html>