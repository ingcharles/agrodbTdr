<?php
//session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorDocumentos.php';
require_once '../../clases/ControladorSolicitudes.php';
require_once '../../clases/ControladorUsuarios.php';
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

$sinNotificar_q = $cs ->solicitudesSinNotificar($conexion, $documento['id_solicitud']);
$sinNotificar = pg_fetch_assoc($sinNotificar_q);

$delegadas_q = $cs ->solicitudesDelegadas($conexion, $documento['id_solicitud']);
$delegadas = pg_fetch_assoc($delegadas_q);

$r_condicion = $cs -> condicionSolicitud($conexion, $documento['id_solicitud']);
$condicion = pg_fetch_assoc($r_condicion);

$estado_q = $cs ->estadoAprobador($conexion, $documento['id_solicitud']);
$estadoAprobador = pg_fetch_assoc($estado_q);

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>
	<header><h1>Detalle de documento generado</h1></header>
	<table class="soloImpresion">
	<tr>
	<td>
	<fieldset id="fs_detalle">
		<legend>Detalle</legend>
		<?php
		echo'
		
		<!--div><label>Original del solicitante: </label><a href="aplicaciones/documentos/generados/'.$documento['id_documento'].'.rtf">'.$documento['id_documento'].'</a></div-->
		
		<div data-linea="1"><label>Tipo: </label> '.$documento['tipo'].' (V. ' .$documento['version_plantilla'].')</div>
		<div data-linea="1"><label>Autor:  </label>' .$documento['nombres_completos'].'</div>
		<div data-linea="2"><label>Fecha creación: </label>' .date('j/n/Y (G:i)',strtotime($documento['fecha_creacion'])).'</div>
		<div data-linea="3"><label>Asunto: </label>'.$documento['asunto'].'</div>
		<div data-linea="4"><label>Original del solicitante: </label><a href="'.$documento['documento_inicial'].'">'.$documento['id_documento'].'</a></div>'; 
				$tmpBorradores = explode(";",$documento['documento_borrador']);
		echo '<div data-linea="5"><label>Borrador del solicitante: </label>'.($tmpBorradores[sizeof($tmpBorradores)-1]==''?'<span class="alerta">No ha subido ningún borrador aún.</span>':'<a href="'.$tmpBorradores[sizeof($tmpBorradores)-1].'">'.$documento['id_documento'].'_borrador</a></div>'); ?>
		
	</fieldset>
	</td>
	<td>
	<fieldset id="fs_novedades">
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
							$tmpBorrador = explode(".",$tmp[1]);
							if ($tmpBorrador[0]==$revisor['identificador']){
								echo '<div><label>Borrador del revisor: </label><a href="'.$tmpBorradores[$i].'" target="_blank">Borrador</a></div>';
								continue;
							}
						}
						echo '</td></tr>';
						
					}
					;?>
				</tbody>
			</table>

			

		</div>
	</fieldset>
	
	<fieldset id="fs_observaciones">
		<legend>Observaciones</legend>
			
			<div data-linea="1">
				<label>Observaciones reportadas</label><br/><br/>
						<div><?php echo ($condicion['observacion']!=''?$condicion['observacion']:'<div class="alerta">No se ha ingresado un comentario aún.</div>')?></div><br/>
			</div>
			<label>Observación</label>
			<div data-linea="2">
				<textarea id="observacion" name="observacion" rows="3"></textarea>	
			</div>
	</fieldset>
	
	</td>
	</tr>
	</table>
	

	<?php 
		if($totales['total'] !=  ($aprobadas['aprobadas']+$delegadas['delegadas']) && $estadoAprobador['estado']!='Pendiente' && $estadoAprobador['estado']!='Delegado'){
		//if($totales['total'] == ($aprobadas['aprobadas']+$sinNotificar['sin_notificar']+$delegadas['delegadas']) && $aprobadas['aprobadas']!=0  && $documento['documento_final']==''){
		// Subir documento adjunto proceso entre Revisores.
		echo'
		<fieldset class="soloPantalla">
			<legend>Subir borrador</legend>
			<form id="subirArchivo" action="aplicaciones/documentos/subirArchivo.php" method="post" enctype="multipart/form-data" target="ventanaEmergente" onsubmit="window.open("", this.target, "width=250,height=250,resizable,scrollbars=yes");">
				<input type="file" name="archivo" id="archivo" accept="text/rtf"/>
				<input name="id_documento" value='. $documento['id_documento'].' type="hidden"/>
				<input name="id_solicitud" value='. $documento['id_solicitud'].' type="hidden"/>
				<input name="rutaBorradores" value='.($documento['documento_borrador']!=''?$documento['documento_borrador']:'null').' type="hidden"/>
				<input name="notificar" value="TODOS" type="hidden"/>
				<input id="nuevaObservacion" name="nuevaObservacion" type="hidden"/>
				<input id="actualObservacion" name="actualObservacion" type="hidden" value= "'.$condicion['observacion'].'"/>
				<button type="submit" name="boton" value="borrador" disabled="disabled" class="adjunto" >Subir Archivo</button>
			</form>
			<iframe name="ventanaEmergente" class="ventanaEmergente"></iframe>
			<div class="nota">Al subir un documento nuevo, este reemplazara al existe y se reenviarán nuevas peticiones de revisión a los funcionarios designados.</div>
		</fieldset>';

		}
		
		else if($condicion['condicion'] != 'aprobado' && $condicion['condicion'] !='archivado' ){
			// Subir documento adjunto proceso entre Aprobador.
			echo'
		
		<fieldset class="soloPantalla">
			<legend>Subir documento </legend>
		
			<form id="subirArchivo" action="aplicaciones/documentos/subirArchivo.php" method="post" enctype="multipart/form-data" target="ventanaEmergente" onsubmit="window.open("", this.target, "width=250,height=250,resizable,scrollbars=yes");">';
		
					$res = $cs -> estadoAprobador($conexion, $documento['id_solicitud']);
					$revisor = pg_fetch_assoc($res);
					
					if($revisor['identificador']== ''){
						echo '<label>Aprobador</label>
									<select id="p_aprobador" name="p_aprobador">
										<option value="">Seleccione....</option>';
	
							$cu = new ControladorUsuarios();
							$res = $cu->obtenerUsuariosActivos($conexion,"'".$_SESSION['usuario']."'");
							//$res = $cu->obtenerUsuariosActivos($conexion,"'nada'");
							while($fila = pg_fetch_assoc($res)){
								echo '<option value="' . $fila['identificador'] . '">' . $fila['apellido'] . ", " . $fila['nombre'] . '</option>';
							}
							echo' 	</select>';
					}
					
					echo '
					<input type="file" name="archivo" id="archivo" accept="text/rtf"/>
					<input name="id_documento" value='. $documento['id_documento'].' type="hidden"/>
					<input name="id_solicitud" value='. $documento['id_solicitud'].' type="hidden"/>
					<input name="rutaBorradores" value='.$documento['documento_borrador'].' type="hidden"/>
					<input id="nuevaObservacion" name="nuevaObservacion" type="hidden"/>	
					<input id="actualObservacion" name="actualObservacion" type="hidden" value= "'.$condicion['observacion'].'"/>								
					<button type="submit" name="boton" value="aprobador" disabled="disabled" class="adjunto" >Subir Archivo</button>
			
			</form>
			<iframe name="ventanaEmergente" class="ventanaEmergente"></iframe>
			<div class="nota">Al subir un documento nuevo, este reemplazara al existe y se reenviarán nuevas peticiones de revisión a los funcionarios designados.</div>
		</fieldset>';
		}
		
		//else if(($estadoAprobador['estado']=='Aprobado'|| $estadoAprobador['estado']=='Delegado') && $documento['estado']==1){
		else if(($estadoAprobador['estado']=='Aprobado'|| $estadoAprobador['estado']=='Delegado') && $documento['estado']==1){
				// Subir documento adjunto asignación de número.
			echo'
						
				<fieldset id="fs_pre_final" class="soloPantalla">
					<legend>Subir documento final (DOCX)</legend>
						<form id="subirArchivoPreFinal" action="aplicaciones/documentos/subirArchivo.php" method="post" enctype="multipart/form-data" target="ventanaEmergentePreFinal" onsubmit="window.open("", this.target, "width=250,height=250,resizable,scrollbars=yes");">
							<input type="file" name="archivo" id="archivo" accept="text/rtf"/>
							<input name="id_documento" value='.$documento['id_documento'].' type="hidden"/>
							<input name="id_solicitud" value='.$documento['id_solicitud'].' type="hidden"/>
							<input id="nuevaObservacion" name="nuevaObservacion" type="hidden"/>
							<input id="actualObservacion" name="actualObservacion" type="hidden" value= "'.$condicion['observacion'].'"/>
							<button type="submit" name="boton" value="pre_final" class="adjunto" >Subir arhivo y finalizar trámite</button>
						</form>
					<iframe name="ventanaEmergentePreFinal" class="ventanaEmergente"></iframe>
				</fieldset>';
			}
			//else if(($estadoAprobador['estado']=='Aprobado'|| $estadoAprobador['estado']=='Delegado') && $documento['estado']=='2'){
			else if(($estadoAprobador['estado']=='Aprobado'|| $estadoAprobador['estado']=='Delegado') && $documento['estado']=='2'){
				
				// Subir documento adjunto PDF finalización de proceso.
				echo'		
						
				<fieldset id="fs_final" class="soloPantalla">
					<legend>Subir documento final de respaldo (PDF)</legend>
						<form id="subirArchivoFinal" action="aplicaciones/documentos/subirArchivo.php" method="post" enctype="multipart/form-data" target="ventanaEmergenteFinal" onsubmit="window.open("", this.target, "width=250,height=250,resizable,scrollbars=yes");">
							<label>Correo electronico: </label><input name="correo" type="text" style=" width: 76%;" />
							<input type="file" name="archivo" id="archivo" accept="application/pdf"/>
							<input name="id_documento" value='.$documento['id_documento'].' type="hidden"/>
							<input name="id_solicitud" value='.$documento['id_solicitud'].' type="hidden"/>
							<input id="nuevaObservacion" name="nuevaObservacion" type="hidden"/>
							<input id="actualObservacion" name="actualObservacion" type="hidden" value= "'.$condicion['observacion'].'"/>
							<button type="submit" name="boton" value="final" class="adjunto" >Subir arhivo y finalizar trámite</button>
						</form>
							<iframe name="ventanaEmergenteFinal" class="ventanaEmergente"></iframe>
							<div class="nota">Al subir el documento final, este documento se cerrará y desaparecera de sus "Documentos activos".</div>
		
					</fieldset>';
		}
		
		
		?>
			<br/><fieldset>
				<legend>Historial</legend>
					
					<button type="button" id='mostrarHistorial'>Mostrar/Ocultar</button>   
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

	$("document").ready(function(){
		distribuirLineas();
	});

	$("#mostrarHistorial").click(function(){
		 $("#historial").slideToggle();		 
	});
	
		
	$("#archivo").click(function(){
		$("#subirArchivo button").removeAttr("disabled");
	});

	$("#observacion").change(function(){
		$("#nuevaObservacion").val($("#observacion").val());
	});

	$("#subirArchivo button[type='submit']").click(function(e){
		$("#fs_detalle").fadeOut();
		$("#fs_novedades").fadeOut();
		$("#fs_final").fadeOut();
		$("#fs_pre_final").fadeOut();
	});
</script>
					
					
</html>
