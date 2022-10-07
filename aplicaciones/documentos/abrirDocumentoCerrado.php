<?php
//session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorDocumentos.php';
require_once '../../clases/ControladorSolicitudes.php';
require_once '../../clases/ControladorAuditoria.php';

$conexion = new Conexion();
$cd = new ControladorDocumentos();
$cs = new ControladorSolicitudes();
$ca = new ControladorAuditoria();

//Validar sesion
$conexion->verificarSesion();

$res = $cd->abrirDocumento($conexion, $_POST['id']);
$documento = pg_fetch_assoc($res);
$res2 = $cs-> listarRevisores($conexion, $documento['id_solicitud']);
$total_q = $cs ->solicitudesTotales($conexion, $documento['id_solicitud']);
$totales = pg_fetch_assoc($total_q);
$aprobadas_q = $cs ->solicitudesAprobadas($conexion, $documento['id_solicitud']);
$aprobadas = pg_fetch_assoc($aprobadas_q);


$tmpBorrador = explode(";",$documento['documento_borrador']);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>
	<header><h1>Detalle de documento generado</h1></header>
	<table class="soloImpresion">
	<tr><td>
	<fieldset>
		<legend>Detalle</legend>
		<?php
		echo'
			
		<div><label>Tipo: </label> '.$documento['tipo'].' (V. ' .$documento['version_plantilla'].')</div>
		<div><label>Autor:  </label>' .$documento['nombres_completos'].'</div>
		<div><label>Fecha creación: </label>' .date('j/n/Y (G:i)',strtotime($documento['fecha_creacion'])).'</div>
		<div><label>Asunto: </label>'.$documento['asunto'].'</div>
		<!--div><label>Documento original: </label><a href="'.$documento['documento_inicial'].'">'.$documento['id_documento'].'</a></div-->
		<div><label>Documento borrador: </label>'.($documento['documento_borrador']==''?'<span class="alerta">No ha subido ningún borrador aún.</span>':'<a href="'.end($tmpBorrador).'">'.$documento['id_documento'].'_borrador</a></div>').'
		<div><label>Documento final en pdf: </label>'.($documento['documento_final']==''?'<span class="alerta">No ha subido ningún documento final aún.</span>':'<a href="'.$documento['documento_final'].'">'.$documento['id_documento'].'_final</a></div>'); ?>
		
	</fieldset>
	</td>
	<td>
	<fieldset>
		<legend>Novedades reportadas</legend>
		<div>
			<table>
				<tbody id="revisores">
					<?php 
					while($revisor = pg_fetch_assoc($res2)){
						echo'<tr>
								<td class="n'.$revisor['estado'].'"><div><label>Revisor: </label>' . $revisor['nombres_completos'] . '</div>
									<div><label>Estado de la revisión: </label>'.str_replace('_', ' ', $revisor['estado']).'</div>
									<div><label>Comentario: </label><span class="comentario">'. (trim($revisor['comentario'])==''?'Ningún comentario.':$revisor['comentario']).'</span></div>';
					
						$tmpBorrador='';
						for($i=0;$i<sizeof($tmpBorradores)-1;$i++){
							$tmp= explode("_",$tmpBorradores[$i]);
							$tmpBorrador = explode('.',$tmp[1]);
							if ($tmpBorrador[0]==$revisor['identificador']){
								echo '<div><label>Borrador del revisor: </label><a href="'.$tmpBorradores[$i].'" target="_blank">Borrador</a></div>';
								continue;
							}
						}
						echo '</td></tr>';
					}?>
				</tbody>
			</table>



		</div>
	</fieldset>
	
	</td></tr></table>
	
	<br/><fieldset>
				<legend>Historial</legend>
				  
						<table id="historial">
					   		<thead>
								<tr>
							    	<th>Fecha</th>
							     	<th>Acción realizada</th>
							    </tr>
						 	</thead>
							<tbody>
							 	<tr>
							     	<?php 
							     		 $qHistorial = $ca->listaHistorial($conexion, $documento['id_solicitud'], 'Documentos');
							      			while($historial = pg_fetch_assoc($qHistorial)){
										        echo ' <td>'.date('j/n/Y (G:i:s)',strtotime($historial['fecha'])).'</td>
										            <td>'.$historial['accion'].'</td></tr><tr>';
										      }
							     	?>
							    </tr>
							</tbody>
					  	</table>
		 	</fieldset>

</body>
<script type="text/javascript">
					$("#archivo").click(function(){
						$("#subirArchivo button").removeAttr("disabled");});
					</script>
</html>
