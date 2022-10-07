<?php
    session_start();
    require_once '../../clases/Conexion.php';
    require_once '../../clases/ControladorCertificacionBPA.php';
    require_once '../../clases/ControladorCatalogos.php';
    require_once '../../clases/ControladorExpedienteDigital.php';
    
    define("CERT_BPA_URL_CHECK_TEC", "aplicaciones/mvc/modulos/CertificacionBPA/archivos/checklists/");
    define("CERT_BPA_URL_PLAC_TEC", "aplicaciones/mvc/modulos/CertificacionBPA/archivos/plantillas/");
    
    $conexion = new Conexion();
    $ccb = new ControladorCertificacionBPA();
    $cc = new ControladorCatalogos();
    $ce = new ControladorExpedienteDigital();
    
    $idSolicitud = $_POST['id'];
    
    $res = $ccb->abrirSolicitud($conexion, $idSolicitud);
    $filaSolicitud = pg_fetch_assoc($res);
    
    $res=null;
    $tipo = ($filaSolicitud['tipo_explotacion']=="SV"?"'BPA'":($filaSolicitud['tipo_explotacion']=="SA"?"'BPP'":"'BPA', 'BPP'"));
        
    $res = $cc->obtenerGuiaBuenasPracticas($conexion, $tipo);
    
    $resoluciones = array();
    while ($fila = pg_fetch_assoc($res)){
        $resoluciones[] =  '<option value="'.$fila['id_guia_buenas_practicas']. '" >'. $fila['numero_resolucion'] .' - '. $fila['nombre_resolucion'] .' - '. $fila['fecha_resolucion'] .'</option>';
    }
    
    $rutaFecha = date("Y/m/d", time());
    
    $sitiosCertificar = pg_fetch_result($ccb->obtenerCantidadSitiosCertificado($conexion, $idSolicitud),0, 'num_sitios');

?>

<header>
	<h1>Solicitud de Certificación BPA</h1>
</header>

<div class="pestania">

	<fieldset>
		<legend>Datos Generales</legend>
		<div data-linea="1">
			<label>Tipo Solicitud: </label> <?php echo $filaSolicitud['tipo_solicitud']; ?>			
		</div>		
		
		<div data-linea="1"> 
			<label>Tipo Explotación: </label> 
			<?php echo ($filaSolicitud['tipo_explotacion']=="SV"?"Sanidad Vegetal":($filaSolicitud['tipo_explotacion']=="SA"?"Sanidad Animal":"Inocuidad de Alimentos"));?>			
		</div>
	</fieldset>
	
	<fieldset>
		<legend>Datos Operador</legend>
		<div data-linea="1">
			<label>Identificador: </label> <?php echo $filaSolicitud['identificador']; ?>						
		</div>
		
		<div data-linea="2">
			<label>Nombre/Razón Social: </label> <?php echo $filaSolicitud['razon_social']; ?>						
		</div>	
		
		<div data-linea="3">
			<label>Dirección: </label> <?php echo $filaSolicitud['direccion']; ?>						
		</div>
		
		<div data-linea="4">
			<label>Teléfono: </label> <?php echo $filaSolicitud['telefono']; ?>						
		</div>
		
		<div data-linea="5">
			<label>Correo electrónico: </label> <?php echo $filaSolicitud['correo']; ?>						
		</div>
				
		<div data-linea="6">
			<label>Provincia: </label> <?php echo $filaSolicitud['provincia_unidad_produccion']; ?>
		</div>
		
		<div data-linea="7">
			<label>Cantón: </label> <?php echo $filaSolicitud['canton_unidad_produccion']; ?>
		</div>
		
		<div data-linea="8">
			<label>Parroquia: </label> <?php echo $filaSolicitud['parroquia_unidad_produccion']; ?>
		</div>
		
		<hr />
		
		<div data-linea="4"> 
			<label>Identificación Representante: </label> <?php echo $filaSolicitud['identificador_representante_legal']; ?>				
		</div>
		
		<div data-linea="5">
			<label>Representante Legal: </label> <?php echo $filaSolicitud['nombre_representante_legal']; ?>						
		</div>		
	</fieldset>
	
	<fieldset>
		<legend>Datos del Responsable Técnico de la Unidad de Producción Agrícola y/o Pecuaria</legend>
		<div data-linea="6">
			<label>Identificación: </label> <?php echo $filaSolicitud['identificador_representante_tecnico']; ?>						
		</div>
		
		<div data-linea="7">
			<label>Nombres: </label> <?php echo $filaSolicitud['nombre_representante_tecnico']; ?>						
		</div>		
		
		<div data-linea="8"> 
			<label>E-mail: </label> <?php echo $filaSolicitud['correo_representante_tecnico']; ?>				
		</div>
		
		<div data-linea="8">
			<label>Teléfono: </label> <?php echo $filaSolicitud['telefono_representante_tecnico']; ?>						
		</div>		
	</fieldset>
	
	<!-- mostrar tabla con los sitios registrados en la solicitud y su estado -->
	<fieldset>
		<legend><?php echo ($filaSolicitud['es_asociacion']=='Si'?"Sitios de Miembros de la Asociación a Certificar":"Sitios, Áreas y Productos Agregados")?></legend>
		<div data-linea="6">
			<table id="tbSitiosAreasProductos" style="width:100%">
				<thead>
					<tr>
						<th style="width: 10%;">Nº</th>
						<th style="width: 15%;">Propietario Sitio</th>
						<th style="width: 15%;">Nombre Sitio</th>
                        <th style="width: 10%;">Nombre Área</th>
                        <th style="width: 10%;">Producto</th>
                        <th style="width: 10%;">Provincia</th>
                        <th style="width: 10%;">Operación</th>
                        <th style="width: 10%;">Hectáreas</th>
                        <th style="width: 10%;">Estado</th>
					</tr>
				</thead>
				<tbody>
				<?php 
				    $res = $ccb->obtenerDetalleSitiosAreasProductos($conexion, $idSolicitud);
            		$fila = null;
            		$i = 1;
            		
            		      while($fila = pg_fetch_assoc($res)){
            				echo '<tr>'.
                                        '<td>'.$i++.'</td>'.
										'<td>'.$fila['identificador_sitio']. ' - ' . $fila['razon_social'] .'</td>'.
                        				'<td>'.$fila['nombre_sitio'].'</td>'.
                        				'<td>'.$fila['nombre_area'].'</td>'.
                        				'<td>'.($filaSolicitud['tipo_explotacion']=="SA"?$fila['nombre_subtipo_producto']:$fila['nombre_producto']).'</td>'.
                        				'<td>'.$fila['nombre_provincia'].'</td>'.
                        				'<td>'.$fila['nombre_operacion'].'</td>'.
                        				'<td>'.$fila['superficie'].'</td>'.
                        				'<td>'.$fila['estado'].'</td>'.
                    			 '</tr>';
            		      }
            	?>
				</tbody>
			</table>
		</div>
		
	</fieldset>
	
	
	
	<fieldset>
		<legend>Alcance</legend>
		<div data-linea="15">
			<label>Tipo de Certificado: </label> <?php echo $filaSolicitud['tipo_certificado']; ?>						
		</div>
		
		<div data-linea="16">
			<label>Nº de Trabajadores: </label> <?php echo $filaSolicitud['num_trabajadores']; ?>						
		</div>	
		
		<div data-linea="17" class="equivalente"> 
			<label>Código Equivalente: </label> <?php echo $filaSolicitud['codigo_equivalente']; ?>				
		</div>
		
		<div data-linea="18"> 
			<label>Fecha de Solicitud: </label> <?php echo date('Y-m-d',strtotime($filaSolicitud['fecha_creacion'])); ?>				
		</div>	
		
		<div data-linea="19" class="equivalente"> 
			<label>Fecha Inicio: </label> <?php echo $filaSolicitud['fecha_inicio_equivalente']; ?>				
		</div>
		
		<div data-linea="19" class="equivalente"> 
			<label>Fecha Fin: </label> <?php echo $filaSolicitud['fecha_fin_equivalente']; ?>				
		</div>
		
		<div data-linea="20">
			<label>Observaciones: </label> <?php echo $filaSolicitud['observacion_alcance']; ?>						
		</div>	
		
		<hr />
		
		<div data-linea="21">
			<b>Documentos Adjuntos </b>
		</div>
		
		<div data-linea="22" class="equivalente">
			<label>A-. Certificado BPA: </label> 
			<?php echo ($filaSolicitud['ruta_certificado_equivalente']==''? '<span class="alerta">No ha cargado ningún certificado</span>':'<a href="'.$filaSolicitud['ruta_certificado_equivalente'].'" target="_blank" class="archivo_cargado" id="archivo_cargado">Click aquí para ver el archivo</a>')?>					
		</div>
		
		<div data-linea="42" class="nacional">
			<label>A-. Documentos de Apoyo: </label> 
			<?php echo ($filaSolicitud['anexo_nacional']==''? '<span class="alerta">No ha cargado ningún certificado</span>':'<a href="'.$filaSolicitud['anexo_nacional'].'" target="_blank" class="archivo_cargado" id="archivo_cargado">Click aquí para ver el archivo</a>')?>					
		</div>
		
		<hr />
		
		<div data-linea="23">
			<b>Descripción de la población / producto </b>
		</div>
		
		<div data-linea="24">
			<label>Nº de sitios: </label> <?php echo $sitiosCertificar; ?>						
		</div>
		
		<div data-linea="24">
			<label>Nº de fincas a auditar: </label> <?php echo round(sqrt($sitiosCertificar)); ?>						
		</div>
		
		<div data-linea="25">
			<label>Nº Hectáreas a certificar: </label> <?php echo number_format($filaSolicitud['num_hectareas'], 2, '.', ''); ?>							
		</div>
		
		<div data-linea="25" class="num_animales">
			<label>Nº Animales: </label> <?php echo $filaSolicitud['num_animales']; ?>						
		</div>
	</fieldset>
	
	<fieldset>
		<legend>Tipo de Auditoría Solicitada</legend>
		<div data-linea="25" id="contenedorAuditoria">
			<table id="tbAuditorias" style="width:100%">
				<thead>
					<tr>
						<th style="width: 100%;">Tipo de Auditoría</th>
					</tr>
				</thead>
				<tbody>
				<?php 
				    $res = $ccb->obtenerDetalleAuditoriasSolicitadas($conexion, $idSolicitud);
            		$fila = null;
            		$i = 1;
            		$tipoAuditoriaBandera = false;
            		
            		      while($fila = pg_fetch_assoc($res)){
            		          if($fila['fase'] == 'pago'){
            		              $tipoAuditoriaBandera = true;     
            		          }
                				
                			  echo '<tr>'.
                    			  '<td>'.$i++.'. ' .$fila['tipo_auditoria']. '</td>'.
                        	       '</tr>';
            		      }
            		      //Verificar si la solicitud pasó por pago, si es así $tipoAuditoriaBandera = false;
            		      //porque ya seria por subsanación.
            		      if ($filaSolicitud['paso_pago'] == 'Si'){
            		          $tipoAuditoriaBandera = false;
            		      }
            	?>
				</tbody>
			</table>
		</div>
		
		<div data-linea="26" class="auditoriaInspeccion">
			<label>Fecha Auditoría Programada: </label> <?php echo ($filaSolicitud['fecha_auditoria_programada']!= null? $filaSolicitud['fecha_auditoria_programada'] : 'NA'); ?>
		</div>

	</fieldset>
	
	<fieldset class="planAccionUsuario">
		<legend>Plan de Acción</legend>
		<div data-linea="32">
			<label>Plan de Acción: </label> 
			<?php echo ($filaSolicitud['ruta_plan_accion']==''? '<span class="alerta">No ha cargado ningún plan de acción</span>':'<a href="'.$filaSolicitud['ruta_plan_accion'].'" target="_blank" class="archivo_cargado" id="archivo_cargado">Click aquí para ver el archivo</a>')?>					
		</div>
	</fieldset>

	<fieldset class="resultadoRevisionDocumental">
		<legend>Resultado de la Revisión Documental</legend>
	<?php 
	$grupoRevisionDocumental = $ce->obtenerGrupoUltimaRevisionXTipo($conexion, $idSolicitud, 'certificacionBPA', 'Documental');
    	
    	if (pg_num_rows($grupoRevisionDocumental) != 0) {
    	    $revDoc = $ce->listarResultadoInspector($conexion, 'Documental', pg_fetch_result($grupoRevisionDocumental,0,'id_grupo'), 1, $idSolicitud);
    	    
    	    if (pg_num_rows($revDoc) != 0) {
    	        $revisionDocumental = pg_fetch_assoc($revDoc);
    	        
    	        echo '
                        <div data-linea="27">
                			<label>Resultado: </label> '. ($revisionDocumental['estado']=='inspeccion'?'Aprobado':$revisionDocumental['estado']) .
                		'</div>
                		
                		<div data-linea="28">
                			<label>Observaciones: </label> '. $revisionDocumental['observacion'].
                		'</div>
                ';
    	    }
    	}else{
    	    echo '
                        <div data-linea="27">
                			<label>No se dispone de información registrada. </label> '.
                		'</div>';
    	}
	?>	
	</fieldset>
	
	<fieldset class="resultadoInspeccion">
		<legend>Resultado de la Inspección</legend>
	<?php 
    	$grupoInspeccion = $ce->obtenerGrupoUltimaRevisionXTipo($conexion, $idSolicitud, 'certificacionBPA', 'Técnico');
    	
    	if (pg_num_rows($grupoInspeccion) != 0) {
    	    $insp = $ce->listarResultadoInspector($conexion, 'Técnico', pg_fetch_result($grupoInspeccion,0,'id_grupo'), 1, $idSolicitud);
    	    
    	    if (pg_num_rows($revDoc) != 0) {
    	        $inspeccion = pg_fetch_assoc($insp);
    	        
    	        echo '
                    <div data-linea="29">
            			<label>Resultado: </label> '. ($inspeccion['estado']=='aprobacion'?'Aprobado':$inspeccion['estado']) .
            		'</div>
            		
            		<div data-linea="30">
            			<label>Observaciones: </label> '. $inspeccion['observacion'].
            		'</div>
            		
            		<div data-linea="31">
            			<label>% Auditoría: </label> '. $filaSolicitud['porcentaje_auditoria'].
            		'</div>
            		
            		
            		<div data-linea="32">
            			<label>Informe de Auditoría: </label> '. ($inspeccion['ruta_archivo']==''? '<span class="alerta">No ha cargado ningún checklist</span>':'<a href="'.$inspeccion['ruta_archivo'].'" target="_blank" class="archivo_cargado" id="archivo_cargado">Click aquí para ver el archivo</a>') .					
            		'</div>';
    	    }
    	}else{
    	    echo '
                        <div data-linea="30">
                			<label>No se dispone de información registrada. </label> '.
                			'</div>';
    	}
	?>
	</fieldset>
</div>

<div class="pestania">
	<form id="evaluarSolicitudDocumental" data-rutaAplicacion="revisionFormularios" data-opcion="evaluarDocumentosSolicitud" data-accionEnExito ="ACTUALIZAR">
		<input type="hidden" name="inspector" value="<?php echo $_SESSION['usuario'];?>"/> <!-- INSPECTOR -->
		<input type="hidden" name="idSolicitud" value="<?php echo $idSolicitud;?>"/>
		<input type="hidden" name="tipoSolicitud" value="certificacionBPA"/>
		<input type="hidden" name="tipoInspector" value="Documental"/>
		<input type="hidden" name="identificadorOperador" value="<?php echo $filaSolicitud['identificador'];?>"/> <!-- USUARIO OPERADOR -->
		<input type="hidden" name="tipoElemento" value="Productos"/>
		<input type="hidden" name="tipo_solicitud" value="<?php echo $filaSolicitud['tipo_solicitud']; ?>"/>

		<fieldset>
			<legend>Resultado de la Revisión Documental</legend>
					
				<div data-linea="26">
					<label>Resultado: </label>
					<select id="resultadoDocumento" name="resultadoDocumento" required>
						<option value="">Seleccione....</option>
						<option value="<?php echo ($filaSolicitud['tipo_solicitud']=="Equivalente"?"Aprobado":($tipoAuditoriaBandera==true?"pago":"inspeccion"));?>">Aprobar revisión documental</option>
						<option value="subsanacion">Subsanación</option>
						<option value="Rechazado">No habilitado</option>
					</select>
				</div>	
				
				<div data-linea="27" class="fechaAuditoria">
					<label>Fecha Auditoría: </label>
					<input type="text" id="fechaAuditoria" name="fechaAuditoria" required="required" readonly="readonly" 
						value="<?php echo $filaSolicitud['fecha_auditoria']; ?>" <?php //echo ($filaSolicitud['fecha_auditoria']!=null?'disabled="disabled"':''); ?>/>
				</div>
				
				<div data-linea="28">
					<label>Observación: </label>
					<input type="text" id="observacionDocumento" name="observacionDocumento"/>
				</div>
				
				<div data-linea="29" class="resolucion">
					<label>Resolución: </label>
					<select id="resolucion" name="resolucion">
						<option value="">Seleccione....</option>
					</select>
					
					<p id="nombreResolucion"></p>
				</div>
				
				
		</fieldset>
		<button type="submit" class="guardar">Enviar resultado</button>
	</form>
	
	<form id="evaluarSolicitudInspeccion" data-rutaAplicacion="revisionFormularios" data-opcion="evaluarElementosSolicitud" data-accionEnExito ="ACTUALIZAR">
		<input type="hidden" name="inspector" value="<?php echo $_SESSION['usuario'];?>"/> <!-- INSPECTOR -->
		<input type="hidden" name="idSolicitud" value="<?php echo $idSolicitud;?>"/>
		<input type="hidden" name="tipoSolicitud" value="certificacionBPA"/>
		<input type="hidden" name="tipoInspector" value="Técnico"/>
		<input type="hidden" name="identificadorOperador" value="<?php echo $filaSolicitud['identificador'];?>"/> <!-- USUARIO OPERADOR -->
		<input type="hidden" name="tipoElemento" value="Productos"/>
		<input type="hidden" name="tipo_solicitud" value="<?php echo $filaSolicitud['tipo_solicitud']; ?>"/>
		<input type="hidden" name="fecha_creacion" id="fecha_creacion" value="<?php echo $filaSolicitud['fecha_creacion']?>" />

		<fieldset>
			<legend>Resultado de la Inspección</legend>			
							
    			<div data-linea="28" class="auditoriaInspeccion">
        			<label>Fecha Auditoría Programada: </label> <?php echo ($filaSolicitud['fecha_auditoria_programada']!= null? $filaSolicitud['fecha_auditoria_programada'] : 'NA'); ?>
        		</div>
				
				<div data-linea="29" class="porcentajeAuditoria">
					<label>Fecha Auditoría: </label>
					<input type="text" id="fechaAuditoriaRealizada" name="fechaAuditoriaRealizada" required="required" readonly="readonly" 
						value="<?php echo $filaSolicitud['fecha_auditoria']!=null?$filaSolicitud['fecha_auditoria']:$filaSolicitud['fecha_auditoria_programada']; ?>" <?php // value="<?php echo $filaSolicitud['fecha_auditoria']; echo ($filaSolicitud['fecha_auditoria']!=null?'disabled="disabled"':''); ?>/>
				</div>
				
				<div data-linea="29" class="porcentajeAuditoria">
					<label>% Auditoría: </label>
					<input type="number" id="porcentajeAuditoria" name="porcentajeAuditoria" required="required" pattern="^[0-9.]+" onkeydown="return event.keyCode !== 69" step="0.1"
						value="<?php echo $filaSolicitud['porcentaje_auditoria']; ?>" <?php echo ($filaSolicitud['porcentaje_auditoria']!=null?'readonly="readonly"':''); ?> />
				</div>
				
				<div data-linea="30">
					<label>Resultado: </label>
					<select id="resultadoInspeccion" name="resultado" required>
						<option value="">Seleccione....</option>
						<option value="aprobacion">Aprobar inspección</option>
						<option value="subsanacion">Subsanación</option>
						<option value="Rechazado">No habilitado</option>
					</select>
				</div>	
				
				<div data-linea="32">
					<label>Observación: </label>
					<input type="text" id="observacionInspeccion" name="observacion"/>
				</div>
				
				<div data-linea="33">
					<label>Informe de Auditoría: </label>
					
					<input type="file" id="informeChecklist" class="archivo" accept="application/pdf" /> 
        			<input type="hidden" class="rutaArchivo" name="ruta_checklist" id="ruta_checklist" value="<?php echo $filaSolicitud['ruta_checklist'];?>" />
        				
            		<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
            		<button type="button" class="subirArchivo adjunto" data-rutaCarga="<?php echo CERT_BPA_URL_CHECK_TEC . $rutaFecha;?>">Subir archivo</button>
				</div>
				
		</fieldset>
		<button type="submit" class="guardar">Enviar resultado</button>
	</form>
	
	<form id="evaluarSolicitudAprobador" data-rutaAplicacion="revisionFormularios" data-opcion="evaluarElementosSolicitud" data-accionEnExito ="ACTUALIZAR">
		<input type="hidden" name="inspector" value="<?php echo $_SESSION['usuario'];?>"/> <!-- INSPECTOR -->
		<input type="hidden" name="idSolicitud" value="<?php echo $idSolicitud;?>"/>
		<input type="hidden" name="tipoSolicitud" value="certificacionBPA"/>
		<input type="hidden" name="tipoInspector" value="Aprobación"/>
		<input type="hidden" name="identificadorOperador" value="<?php echo $filaSolicitud['identificador'];?>"/> <!-- USUARIO OPERADOR -->
		<input type="hidden" name="tipoElemento" value="Productos"/>
		<input type="hidden" name="tipo_solicitud" value="<?php echo $filaSolicitud['tipo_solicitud']; ?>"/>

		<fieldset>
			<legend>Resultado de la Aprobación</legend>
				
				<div data-linea="34">
					<label>Resultado: </label>
					<select id="resultadoAprobacion" name="resultado" required>
						<option value="">Seleccione....</option>
						<option value="Aprobado">Aprobado</option>
						<option value="inspeccion">Subsanación</option>
						<option value="Rechazado">No habilitado</option>
					</select>
				</div>	
				
				<div data-linea="32">
					<label>Observación: </label>
					<input type="text" id="observacionAprobacion" name="observacion"/>
				</div>
		</fieldset>
		<button type="submit" class="guardar">Enviar resultado</button>
	</form>
</div>
	

<script type="text/javascript">

	var estado= <?php echo json_encode($filaSolicitud['estado']); ?>;
	var asociacion= <?php echo json_encode($filaSolicitud['es_asociacion']); ?>;
	var tipoSolicitud= <?php echo json_encode($filaSolicitud['tipo_solicitud']); ?>;
	var tipoExplotacion= <?php echo json_encode($filaSolicitud['tipo_explotacion']); ?>;
	var planAccionUsuario= <?php echo json_encode($filaSolicitud['ruta_plan_accion']); ?>;
	var array_resoluciones= <?php echo json_encode($resoluciones); ?>;
	
	$("document").ready(function(){
		construirAnimacion($(".pestania"));

		$(".num_animales").hide();
		$(".equivalente").hide();
		$(".nacional").hide();
		$(".fechaAuditoria").hide();
		$(".resolucion").hide();
		$(".auditoriaInspeccion").hide();
		$(".planAccion").hide();
		$(".auditoriaInspeccion").hide();
		$(".planAccion").hide();
		$(".planAccionUsuario").hide();
		$(".resultadoRevisionDocumental").hide();
		$(".resultadoInspeccion").hide();
		
		if(estado === 'enviado'){
			$("#evaluarSolicitudDocumental").show();
			$("#evaluarSolicitudInspeccion").hide();
			$("#evaluarSolicitudAprobador").hide();
			$(".auditoriaInspeccion").hide();
		}else if(estado === 'inspeccion'){
			$("#evaluarSolicitudDocumental").hide();
			$("#evaluarSolicitudInspeccion").show();
			$("#evaluarSolicitudAprobador").hide();
			$(".auditoriaInspeccion").show();
			if(planAccionUsuario == ' ' || planAccionUsuario == '' || planAccionUsuario == '0' || planAccionUsuario == null){
				$(".planAccionUsuario").hide();
			}else{
				$(".planAccionUsuario").show();
			}
		}else if(estado === 'aprobacion'){
			$("#evaluarSolicitudDocumental").hide();
			$("#evaluarSolicitudInspeccion").hide();
			$("#evaluarSolicitudAprobador").show();
			$(".auditoriaInspeccion").show();
			$(".resultadoRevisionDocumental").show();
			$(".resultadoInspeccion").show();
		}

		if(tipoSolicitud === 'Equivalente'){
			$(".equivalente").show();
			$(".nacional").hide();
		}else{
			$(".equivalente").hide();
			$(".nacional").show();
		}

		if(tipoExplotacion === 'SA'){
			$(".num_animales").show();
		}

		for(var i=0; i<array_resoluciones.length; i++){
			$('#resolucion').append(array_resoluciones[i]);
		}

		distribuirLineas();
	});

	$("#fechaAuditoria").datepicker({ 
	    changeMonth: true,
	    changeYear: true,
	    dateFormat: 'yy-mm-dd',
	    minDate: '0',
	    onSelect: function(dateText, inst) {
        	var fecha=new Date($('#fechaAuditoria').datepicker('getDate')); 
	    }
	 });


	 $("#fecha_creacion").datepicker({ 
		    changeMonth: true,
		    changeYear: true,
		    dateFormat: 'yy-mm-dd'
		 });

		$("#fechaAuditoriaRealizada").datepicker({ 
		    changeMonth: true,
		    changeYear: true,
		    dateFormat: 'yy-mm-dd',
		    onSelect: function(dateText, inst) {
		    	$('#fechaAuditoriaRealizada').datepicker('option', 'minDate', $("#fecha_creacion" ).val());
		    }
		 });
			

	$("#resultadoDocumento").change(function(event){ 
		if(tipoSolicitud === 'Nacional'){
			if($("#resultadoDocumento option:selected").val() == 'inspeccion' || $("#resultadoDocumento option:selected").val() == 'pago'){
				$(".fechaAuditoria").show();
				$("#fechaAuditoria").attr('required',"required");

				$(".resolucion").show();
				$("#resolucion").attr('required',"required");
			}else{
				$(".resolucion").hide();
				$("#resolucion").removeAttr('required');

				$(".fechaAuditoria").hide();
				$("#fechaAuditoria").removeAttr('required');
			}
		}else{
			if($("#resultadoDocumento option:selected").val() == 'Aprobado'){
				$(".resolucion").show();
				$("#resolucion").attr('required',"required");

				$(".fechaAuditoria").hide();
				$("#fechaAuditoria").removeAttr('required');
			}else{
				$(".resolucion").hide();
				$("#resolucion").removeAttr('required');

				$(".fechaAuditoria").hide();
				$("#fechaAuditoria").removeAttr('required');
			}
		}
	});

	$("#porcentajeAuditoria").change(function(event){ 
		$(".alerta").removeClass("alerta");
		$("#estado").text('');
		
		if(esCampoValido("#porcentajeAuditoria")){
    		if((Number($("#porcentajeAuditoria").val()) >= Number(75)) && (Number($("#porcentajeAuditoria").val()) < Number(100))){
    			//$(".planAccion").show();
    			cargarValorDefecto("resultado","subsanacion");
    		}else if((Number($("#porcentajeAuditoria").val()) >= Number(0)) && (Number($("#porcentajeAuditoria").val()) < Number(75))){
    			//$(".planAccion").hide();
    			cargarValorDefecto("resultado","Rechazado");
    		}else if (Number($("#porcentajeAuditoria").val()) == Number(100)){
    			//$(".planAccion").hide();
    			cargarValorDefecto("resultado","aprobacion");
    		}else{
    			//$(".planAccion").hide();
    			cargarValorDefecto("resultado","Rechazado");
    		}
		}else{
			error = true;
			$("#porcentajeAuditoria").addClass("alerta");
			$("#estado").text('El valor ingresado no es correcto.').addClass("alerta");
		}
	});

	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}
	

	$("#resolucion").change(function(event){ 
		if($("#resolucion option:selected").val() != ''){
			$("#nombreResolucion").text($("#resolucion option:selected").text());
		}else{
			$("#nombreResolucion").text('');
		}
	});

	//Función para carga de archivo de checklist
    $('button.subirArchivo').click(function (event) {
    	var nombre_archivo = "<?php echo 'InformeAuditoria_' . time(); ?>";
        var boton = $(this);
        var archivo = boton.parent().find(".archivo");
        var rutaArchivo = boton.parent().find(".rutaArchivo");
        var extension = archivo.val().split('.');
        var estado = boton.parent().find(".estadoCarga");

        if (extension[extension.length - 1].toUpperCase() == 'PDF') {

            subirArchivo(
                archivo
                , nombre_archivo
                , boton.attr("data-rutaCarga")
                , rutaArchivo
                , new carga(estado, archivo, boton)
            );

        } else {
            estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
            archivo.val("0");
        }
    });
    

    $("#evaluarSolicitudDocumental").submit(function(event){ 
		event.preventDefault();
		chequearCamposInspeccionDocumental(this);
    });

    function chequearCamposInspeccionDocumental(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#resultadoDocumento").val())){
			error = true;
			$("#resultadoDocumento").addClass("alertaCombo");
		}

		if(tipoSolicitud === 'Nacional'){
			if($("#resultadoDocumento option:selected").val() == 'inspeccion' || $("#resultadoDocumento option:selected").val() == 'pago'){
				if(!$.trim($("#fechaAuditoria").val())){
					error = true;
					$("#fechaAuditoria").addClass("alertaCombo");
				}

				if(!$.trim($("#resolucion").val())){
					error = true;
					$("#resolucion").addClass("alertaCombo");
				}
			}
		}else{
			if($("#resultadoDocumento option:selected").val() == 'Aprobado'){
				if(!$.trim($("#resolucion").val())){
					error = true;
					$("#resolucion").addClass("alertaCombo");
				}
			}
		}

		if(!$.trim($("#observacionDocumento").val())){
			error = true;
			$("#observacionDocumento").addClass("alertaCombo");
		}
		
		if (error){
			$("#estado").html("Por favor revise la información ingresada.").addClass('alerta');
		}else{
			$('#evaluarSolicitudDocumental').attr('data-opcion','evaluarDocumentosSolicitud');
			ejecutarJson(form);

			//Para solicitudes Equivalentes que se aprueban
			if($("#resultadoDocumento option:selected").val()=='Aprobado'){
    			$("#evaluarSolicitudDocumental").attr('data-rutaAplicacion','certificacionesBPA');
    			$("#evaluarSolicitudDocumental").attr('data-opcion','mostrarDocumentoPDF');
    			$("#evaluarSolicitudDocumental").attr('data-destino','detalleItem');
    			abrir($("#evaluarSolicitudDocumental"),event,false);
    		} else{
    			$("#estado").html("Se ha guardado el resultado.").addClass('exito');
    			$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un item para revisarlo.</div>');
    		}
		}
	}	


    $("#evaluarSolicitudInspeccion").submit(function(event){ 
		event.preventDefault();
		chequearCamposInspeccionTecnico(this);
    });

    function chequearCamposInspeccionTecnico(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#fechaAuditoriaRealizada").val())){
			error = true;
			$("#fechaAuditoriaRealizada").addClass("alertaCombo");
		}

		if(!$.trim($("#porcentajeAuditoria").val()) || Number.parseInt($("#porcentajeAuditoria").val()) < Number.parseInt('0') || Number.parseInt($("#porcentajeAuditoria").val()) > Number.parseInt('100') /*|| !esCampoValido("#porcentajeAuditoria")*/){
        	error = true;
    		$("#porcentajeAuditoria").addClass("alertaCombo");
    	}

		if(!$.trim($("#resultadoInspeccion").val())){
			error = true;
			$("#resultadoInspeccion").addClass("alertaCombo");
		}

		//Documentos anexos

		if(!$.trim($("#observacionInspeccion").val())){
			error = true;
			$("#observacionInspeccion").addClass("alertaCombo");
		}
		
		if (error){
			$("#estado").html("Por favor revise la información ingresada.").addClass('alerta');
		}else{
			$('#evaluarSolicitudInspeccion').attr('data-opcion','evaluarElementosSolicitud');
			ejecutarJson(form);
		}
	}	


    $("#evaluarSolicitudAprobador").submit(function(event){ 
		event.preventDefault();
		chequearCamposInspeccionAprobacion(this);
    });

    function chequearCamposInspeccionAprobacion(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#resultadoAprobacion").val())){
			error = true;
			$("#resultadoAprobacion").addClass("alertaCombo");
		}

		if(!$.trim($("#observacionAprobacion").val())){
			error = true;
			$("#observacionAprobacion").addClass("alertaCombo");
		}
		
		if (error){
			$("#estado").html("Por favor revise la información ingresada.").addClass('alerta');
		}else{
			$('#evaluarSolicitudAprobador').attr('data-opcion','evaluarElementosSolicitud');
			ejecutarJson(form);

			if($("#resultadoAprobacion option:selected").val()=='Aprobado'){
    			$("#evaluarSolicitudAprobador").attr('data-rutaAplicacion','certificacionesBPA');
    			$("#evaluarSolicitudAprobador").attr('data-opcion','mostrarDocumentoPDF');
    			$("#evaluarSolicitudAprobador").attr('data-destino','detalleItem');
    			abrir($("#evaluarSolicitudAprobador"),event,false);
    		} else{
    			$("#estado").html("Se ha guardado el resultado.").addClass('exito');
    			$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un item para revisarlo.</div>');
    		}
		}
	}	
	
</script>