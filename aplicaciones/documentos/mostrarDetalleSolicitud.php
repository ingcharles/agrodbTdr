<?php
//session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorDocumentos.php';
require_once '../../clases/ControladorSolicitudes.php';

$conexion = new Conexion();
$ca = new ControladorDocumentos();
$cs = new ControladorSolicitudes();

$conexion->verificarSesion();

$res = $ca->abrirDocumentoPorSolicitud($conexion, $_POST['id']);
$documento = pg_fetch_assoc($res);

$tmpBorrador = explode(";",$documento['documento_borrador']);

$qSolicitud = $cs -> condicionSolicitud($conexion, $_POST['id']);
$solicitud = pg_fetch_assoc($qSolicitud);

?>

<fieldset>
	<legend>Detalles del documento a revisar</legend>
	<?php
	echo'
			
		<div data-linea="1"><label>Tipo: </label> '.$documento['tipo'].' (V. ' .$documento['version_plantilla'].')</div>
		<div data-linea="1"><label>Autor:  </label>' .$documento['nombres_completos'].'</div>
		<div data-linea="2"><label>Fecha creación: </label>' .date('j/n/Y (G:i)',strtotime($documento['fecha_creacion'])).'</div>
		<div data-linea="3"><label>Asunto: </label>'.$documento['asunto'].'</div>
		<div data-linea="4"><label>Documento borrador: </label><a href="'.end($tmpBorrador).'">'.$documento['id_documento'].'_borrador</a></div>';
	?>
</fieldset>

<fieldset id="fs_observaciones">
	<legend>Observaciones reportadas</legend>
		
		<div data-linea="1">
				<br/><div><?php echo ($solicitud['observacion']!=''?$solicitud['observacion']:'<div class="alerta">No se ha ingresado un comentario aún.</div>')?></div><br/>
		</div>
</fieldset>

<fieldset>
	<legend>Subir corrección</legend>
	<form id="subirArchivo"	action="aplicaciones/documentos/subirArchivo.php" method="post"	enctype="multipart/form-data" target="ventanaEmergente"	onsubmit="window.open('', this.target, 'width=250,height=250,resizable,scrollbars=yes');">
		<input type="file" name="archivo" id="archivo"	accept="application/rtf" />
		<input name="id_documento"	value='<?php echo $documento['id_documento'];?>' type="hidden" /> 
		<input	name="id_solicitud" value='<?php echo $documento['id_solicitud'];?>' type="hidden" />
		<input name="rutaBorradores" value="<?php echo $documento['documento_borrador'];?>" type="hidden"/>
		<input name="notificar" value="INDIVIDUAL" type="hidden"/>
		<button type="submit" name="boton" value="borrador"	disabled="disabled" class="adjunto">Subir Archivo</button>
	</form>
	<iframe name="ventanaEmergente" class="ventanaEmergente"></iframe>
		<div class="nota">Puede subir un documento con sus comentarios y correcciones, si desea. Al subir un documento nuevo, este reemplazará al borrador existente y podrá ser visto por otros revisores.</div>
</fieldset>

<script type="text/javascript">
	$("#archivo").click(function(){
		$("#subirArchivo button").removeAttr("disabled");
	});

	$(document).ready(function(){
		distribuirLineas();
	});
</script>
</html>
